<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

	public function index()
	{
		$this->load->view('pages/upload');
	}

	public function upload()
	{
		$this->load->helper(array('form','url'));
        // set path to store uploaded files
		$config['upload_path'] = './assets/uploads/';
        // set allowed file types
		$config['allowed_types'] = 'pdf';
        // set upload limit, set 0 for no limit
		$config['max_size']    = 0;

        // load upload library with custom config settings
		$this->load->library('upload', $config);

         // if upload failed , display errors

		if (!$this->upload->do_upload('file'))
		{
			$data['error'] = $this->upload->display_errors();
			$this->load->view('pages/upload', $data);
		}
		else
		{
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$file_name = $upload_data['file_name'];
			$data_doc = array(
				'file'	=> $file_name);
              // print_r($this->upload->data());
			$insert = $this->db->insert('documents', $data_doc);
			$id_doc = $this->db->insert_id();
			// var_dump($id_doc);
			$this->pdf($file_name, $id_doc);
             // print uploaded file data
		}
	}

	public function doc(){
		$this->load->view('pages/documents');
	}

	public function getDocuments(){
		$data['data'] = $this->db->order_by('id', 'desc')->get('documents')->result();
		echo json_encode($data);
	}

	// public function update_doc($id_doc = null){

	// }

	public function pdf($file_name = null, $id_doc = null){
		$server_file = base_url('assets/uploads/'.$file_name);

		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($server_file);
		if ($pdf != "") {
			$original_text = $pdf->getText();
			if ($original_text != "") {
		        $text = nl2br($original_text); // Paragraphs and line break formatting
		        $text = $this->clean_ascii_characters($text); // Check special characters
		        $text = str_replace(array("<br /> <br /> <br />", "<br> <br> <br>"), "<br /> <br />", $text); // Optional
		        $text = addslashes($text); // Backslashes for single quotes     
		        $text = stripslashes($text);
		        $text = strip_tags($text);

		        /**********************************************/
		        /* Additional step to check formatting issues */
		        // There may be some PDF formatting issues. I'm trying to check if the words are:
		        // (a) Join. E.g., HelloWorld!Thereisnospacingbetweenwords
		        // (b) splitted. E.g., H e l l o W o r l d ! E x c e s s i v e s p a c i n g
		        $check_text = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

		        $no_spacing_error = 0;
		        $excessive_spacing_error = 0;
		        foreach($check_text as $word_key => $word) {
		            if (strlen($word) >= 30) { // 30 is a limit that I set for a word length, assuming that no word would be 30 length long
		            	$no_spacing_error++;
		            } else if (strlen($word) == 1) { // To check if the word is 1 word length
		                if (preg_match('/^[A-Za-z]+$/', $word)) { // Only consider alphabetical words and ignore numbers.
		                	$excessive_spacing_error++;
		                }
		            }
		        }

		        $beforeNo = 'NOMOR:';
		        $afterNo = 'PENGADILAN';

		        $noPerkara = substr($text, strpos($text, $beforeNo) + strlen($beforeNo)); 
		        $noPerkara = strstr($noPerkara, $afterNo, true);   


		        $afterString = 'PENDAHULUAN';
		        $beforeString = 'II.';

		        $text = substr($text, strpos($text, $afterString) + strlen($afterString));    
		        $text = strstr($text, $beforeString, true);

		        $data = array(
		        	'no_perkara' => $noPerkara
		        );

		        $this->db->where('id', $id_doc);
		        $this->db->update('documents', $data);

			        // Set the boundaries of errors you can accept
			        // E.g., we reject the change if there are 30 or more $no_spacing_error or 150 or more $excessive_spacing_error issues
		        if ($no_spacing_error >= 30 || $excessive_spacing_error >= 150) {
		        	echo "Too many formatting issues<br />";
		        	echo $text;

		        } else {
			        	// echo "Success!<br />";
			        	// echo $noPerkara;

			        	// return $text;
			        	// echo $text;

			        	// $subject = 'abc sdfs.    def ghi; this is an.email@addre.ss! asdasdasd? abc xyz';
						// split on whitespace between sentences preceded by a punctuation mark

		        	$sentences 		= $this->paragraph_to_sentences($text, $id_doc);
		        	$preprocess 	= $this->pre_processing($sentences, $id_doc);
		        	$tfidf 			= $this->tf_idf($preprocess, $id_doc);
		        	$id_sentences	= $preprocess[0]['id_sentences'];
		        	// var_dump($id_sentences);
		        	$method 		= $this->lsa($tfidf, $id_sentences, $id_doc);
		        	// var_dump($method);
		        	// var_dump($preprocess['0']['preprocessing_sentences']);
		        	

					// foreach ($tfidf as $key => $value) {
					//     foreach ($value as $k => $v) {
					//         echo $tfidf[$key][$k];
					//         echo "&nbsp";
					//     }
					//     echo "<br>";
					// }
		        	



						// $a = 0;
						// foreach ($preprocess[0]['data_id_sentence'] as $key => $value) {
						// 	echo $a.")"; 
						// 	echo $value;
						// 	echo " ";
						// 	$a++;
						// }





						// print_r($result);

		        }
		        /* End of additional step */
		        /**************************/

		    } else {
		    	echo "No text extracted from PDF.";
		    }
		} else {
			echo "parseFile fns failed. Not a PDF.";
		}
	}

	// Common function
	function clean_ascii_characters($string) {
		$string = str_replace(array('-', '–'), '-', $string);
		$string = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);  
		return $string;
	}

	// function call_method($sentences = null, $id_doc = null){

	// 	if ($sentences == null && $id_doc != null) {

	// 		$sentences = se
	// 	} 
	// 	$preprocess = $this->pre_processing($sentences, $id_doc);
	// 	$tfidf = $this->tf_idf($preprocess, $id_doc);
	// }

		// foreach ($tfidf as $key => $value) {
		//     foreach ($value as $k => $v) {
		//         echo $tfidf[$key][$k];
		//         echo "&nbsp";
		//     }
		//     echo "<br>";
		// }

	function paragraph_to_sentences($text = null, $id_doc = null){

		// untuk mecah perkalimat
		$result = preg_split('/(?<!Th.|No.|Perk.|Reg.|Rp.)(?<=[.?!;])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

		$sentences_array = [];
		foreach ($result as $k => $v) {
			if (trim($v) == "") {
				continue;
			}
			if (substr($v, -1, 1) == ".") {
				$v = substr($v, 0, -1);
			}
			$sentences_array[] = [
				'sentence' => $v,
				'fk_documents' => $id_doc
			];
		}

		$this->db->insert_batch('sentence', $sentences_array);

		return $this->db->where('fk_documents', $id_doc)->get('sentence')->result();
	}

	function pre_processing($text = null, $id_doc = null){
		// $this->load->library('preprocessing');
		$id_sentences = [];
		$casefolding = [];
		foreach ($text as $key => $value) {
			$id_sentences[$key] = $value->id_sentence;
			$casefolding[$key] = $this->process($value->sentence,true,false,false,false,false);
		}
 
		$filtering = [];
		foreach ($text as $key => $value) {
			$filtering[$key] = $this->process($value->sentence,true,true,false,false,false);
		}

		$stemming = [];
		foreach ($text as $key => $value) {
			$stemming[$key] = $this->process($value->sentence,true,true,true,false,false);
		}

		$tokenizing = [];
		foreach ($text as $key => $value) {
			$tokenizing[$key] = $this->process($value->sentence,true,true,true,true,false);
		}

		$stopword = [];
		foreach ($text as $key => $value) {
			$stopword[$key] = $this->process($value->sentence,true,true,true,true,true);
		}

		$array_preprocess_result[] = [
			'id_sentences' =>  $id_sentences,
			'casefolding_sentence' => $casefolding,
			'filtering_sentence'=> $filtering,
			'stemming_sentence' => $stemming,
			'tokenizing_sentence' => $tokenizing,
			'preprocessing_sentences' => $stopword
		];

		return $array_preprocess_result;

	}

	function tf_idf($text = null, $id_doc = null){
		// foreach ($preprocess[0]['data_id_sentence'] as $key => $value) {
						// 	echo $a.")"; 
						// 	echo $value;
						// 	echo " ";
						// 	$a++;
						// }
		$text_list_word = [];
		foreach ($text[0]['preprocessing_sentences'] as $key => $value) {
			$text_list_word = array_merge($text_list_word, $value);
		}
		$text_list_word = array_unique($text_list_word);

		$matrix_tf = [];
		foreach ($text_list_word as $key_word => $word) {

			foreach ($text[0]['preprocessing_sentences'] as $key_text => $value_text) {
				$count_word = 0;
				foreach ($value_text as $k => $v) {
					if ($word == $v) {
						$count_word++;
					}
				}
				$matrix_tf[$key_word][$key_text] = $count_word;
			}
		}


		$text_df = [];
		foreach ($matrix_tf as $key => $value) {
			$df = 0;
			foreach ($value as $k => $v) {
				if ($v > 0) {
					$df++;
				}
			}
			$text_df[$key] = $df;
		}

		$text_dperdf = [];
		$text_idf = [];
		$text_idfplus1 = [];
		$word_count = count($text[0]['preprocessing_sentences']);
		foreach ($text_df as $key => $value) {
			$dperdf = $word_count / $value;
			$text_dperdf[$key] = $dperdf;
			$idf = log10($dperdf);
			$text_idf[$key] = $idf;
			$text_idfplus1[$key] = $idf + 1;
		}

		$matrix_tfidf = [];
		foreach ($matrix_tf as $key => $value) {
			foreach ($value as $k => $v) {
				$matrix_tfidf[$key][$k] = $v * $text_idfplus1[$key];
			}
		}

		return $matrix_tfidf;

	}

	function lsa($matrix_tfidf = null, $id_sentences = null, $id_doc = null){

		var_dump($id_sentences);
		
		$errorlevel = error_reporting();
        error_reporting($errorlevel & ~E_NOTICE);

        $this->load->library("singularvaluedecomposition");		
		$svd = $this->singularvaluedecomposition->SVD($matrix_tfidf);

		// $m = count($matrix_tfidf);
  //       $n = count($matrix_tfidf[0]);

		// $matrix_Vt = $this->singularvaluedecomposition->matrixRound($svd['V']);
		// $matrix_S  = $this->singularvaluedecomposition->matrixRound($svd['S']);

		$matrix_Vt = $svd['V'];
		$matrix_S  = $svd['S'];
		// var_dump($matrix_Vt);
		// var_dump($matrix_S);

        $rows_matrix_Vt = count($matrix_Vt);
        $cols_matrix_Vt = count($matrix_Vt[0]);
        
        $rows_matrix_S = count($matrix_S);
        $cols_matrix_S = count($matrix_S[0]);
        
        if($cols_matrix_Vt == $rows_matrix_S){
            for($i = 0; $i < $rows_matrix_Vt; $i++){
                for($j = 0; $j < $cols_matrix_S; $j++){
                    for($p = 0; $p < $cols_matrix_Vt; $p++){
                        $matrix = $matrix_Vt[$i][$p] * $matrix_S[$p][$j];
                        if($matrix >= 0){
                        	$matrix_LSA[$i][$j] += sqrt($matrix);
                        }else{
                        	$matrix_LSA[$i][$j] += -sqrt(abs($matrix));
                        }
                    }
                }
            }
        }

        // var_dump($matrix_LSA);


        $lsa_Length = [];

        foreach ($matrix_LSA as $key => $value) {
            foreach ($value as $k => $v) {
            	$lsa_Length[$k] += $v;
            }

        }

        // var_dump($id_sentences);

        foreach ($lsa_Length as $key => $value) {
        	$data = array(
		        	'f1' => $value
		    );

        	$this->db->where('id_sentence', $id_sentences[$key]);
		    $this->db->update('sentence', $data);
        }

        return $this->db->where('fk_documents', $id_doc)->get('sentence')->result();;

	}

	public function process($text, $casefolding = true, $filtering = true, $stemming = true, $tokenizing = true, $stopword = true)
    {
        // $ret = [];
        // $file_stopword = "assets/stopword/stopword.txt";
        // $GLOBALS['stopwords'] = explode("\n", file_get_contents($file_stopword));

        $stopwords_removal = [] ;

    	foreach ($this->db->select('stopword')->get('stopword_list')->result() as $key => $value) {
    		$stopwords_removal[$key] = $value->stopword;
    	}

    	$GLOBALS['stopwords'] = $stopwords_removal;

        if ($casefolding) {
            $text = strtolower($text);
        }

        if ($filtering) {
            $text = preg_replace("/[^a-zA-Z0-9\s- .]/", "", $text);
        }
        
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
        $stemmer  = $stemmerFactory->createStemmer();

        if ($casefolding && $stemming) {
            $text = $stemmer->stem($text);
        }

        // echo $text_segment_stem;
        // echo "<br>";

        if ($tokenizing) {
        	$text = explode(" ", $text);
        }

        // var_dump($text_tokenization);

        if ($stopword) {
            $text = array_filter($text, function ($key) {
                return !in_array($key, $GLOBALS['stopwords']);
            });
        }

        // var_dump($text_stopwordremove);


  		//  $array_preprocess[] = [
		// 	'casefolding' =>  $text_lower,
		// 	'filter' => $text_filtered,
		// 	'stemming' => $text_segment_stem,
		// 	'tokenizing' => $text_tokenization,
		// 	'stopword' => $text_stopwordremove
		// ];

        return $text;
    }

	// public function go()
	// {
	// 	$parser = new \Smalot\PdfParser\Parser();
	// 	$pdf    = $parser->parseFile(base_url('assets/uploads/news3.pdf'));

	// 	$text = $pdf->getText();  

	// 	$afterString = 'PENDAHULUAN';
	// 	$beforeString = 'II.';

	// 	$text = substr($text, strpos($text, $afterString) + strlen($afterString));    
	// 	$text = strstr($text, $beforeString, true);


	// 	echo $text;
	// }

	// public function html()
	// {
	// 	// use Gufy\PdfToHtml\Config;
	// 	// change pdftohtml bin location
	// 	// Config::set('pdftohtml.bin', 'C:/poppler-0.37/bin/pdftohtml.exe');

	// 	// // change pdfinfo bin location
	// 	// Config::set('pdfinfo.bin', 'C:/poppler-0.37/bin/pdfinfo.exe');
	// 	// initiate
	// 	$pdf = new Gufy\PdfToHtml\Pdf('file.pdf');

	// 	// convert to html and return it as [Dom Object](https://github.com/paquettg/php-html-parser)
	// 	$html = $pdf->html();

	// 	// check if your pdf has more than one pages
	// 	$total_pages = $pdf->getPages();

	// 	// Your pdf happen to have more than one pages and you want to go another page? Got it. use this command to change the current page to page 3
	// 	$html->goToPage(3);

	// 	// and then you can do as you please with that dom, you can find any element you want
	// 	$paragraphs = $html->find('body > p');


	// 	echo $total_pages;
	// }




}


