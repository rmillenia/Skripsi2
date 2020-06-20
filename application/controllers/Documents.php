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
		$config['upload_path'] = './assets/uploads/originalDocument';
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
				'file'		=> $file_name
			);
              // print_r($this->upload->data());
			$insert = $this->db->insert('documents', $data_doc);
			$id_doc = $this->db->insert_id();
			// var_dump($id_doc);

			$kompresi = $this->input->post('kompresi');
			$this->pdf($file_name, $id_doc);
			
			echo json_encode($id_doc);
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

	public function deleteDocuments(){
		$no = $this->input->post('ids');
		$data['data'] = $this->db->where_in('id', $no)->delete('documents');
		echo json_encode($data);
	}

	// public function update_doc($id_doc = null){

	// }

	public function pdf($file_name = null, $id_doc = null){
		$server_file = base_url('assets/uploads/originalDocument/'.$file_name);

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

		        $beforeTerdakwa = 'terdakwa';

		        $terdakwa = substr($text, strpos($text, $beforeTerdakwa) + strlen($beforeTerdakwa)); 
				$hasil = implode(" ", array_slice(explode(" ", $terdakwa), 1, 1));

				// echo $hasil;

				$beforePengadilan = 'PENGADILAN';
		        $afterPengadilan = 'I. PENDAHULUAN';

		        $pengadilan = substr($text, strpos($text, $beforePengadilan) + strlen($beforePengadilan)); 
		        $pengadilan = strstr($pengadilan, $afterPengadilan, true);

		        // echo $pengadilan;


				// var_dump($hasil); 


		        $afterString = 'PENDAHULUAN';
		        $beforeString = 'II.';

		        $text = substr($text, strpos($text, $afterString) + strlen($afterString));    
		        $text = strstr($text, $beforeString, true);

		        $data = array(
		        	'no_perkara' => $noPerkara,
		        	'terdakwa'	 => $hasil,
		        	'pengadilan' => 'PENGADILAN '.$pengadilan
		        );

		        $this->db->where('id', $id_doc);
		        $this->db->update('documents', $data);

			        // Set the boundaries of errors you can accept
			        // E.g., we reject the change if there are 30 or more $no_spacing_error or 150 or more $excessive_spacing_error issues
		        if ($no_spacing_error >= 30 || $excessive_spacing_error >= 150) {
		        	$result = "Too many formatting issues<br />";

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
		        	$method_lsa 		= $this->lsa($tfidf[0]['matrix_tfidf'], $id_sentences, $id_doc);
		        	$feature_numeric    = $this->feature_contain_numeric($sentences, $id_doc);

		        	$all_sentences = $this->db->where('fk_documents', $id_doc)->get('sentence')->result();

		        	$bobot_total = [];

		        	foreach ($all_sentences as $key => $value) {
		        		$f1 = $value->f1;
		        		$f2 = $value->f2;
		        		$bobot_total[$key] = $f1+$f2;

		        		$data = array(
		        				'bobot' => $bobot_total[$key]
		       					);

		        		$this->db->where('id_sentence', $id_sentences[$key]);
		    			$this->db->update('sentence', $data);

		        	}

		        	$result = $bobot_total;
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
		    	$result = "No text extracted from PDF.";
		    }
		} else {
			$result = "parseFile fns failed. Not a PDF.";
		}

		return $result;
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

	public function process($text, $casefolding = true, $filtering = true, $stemming = true, $tokenizing = true, $stopword = true)
    {
        // $ret = [];
        // $file_stopword = "assets/stopword/stopword.txt";
        // $GLOBALS['stopwords'] = explode("\n", file_get_contents($file_stopword));

        $stopwords_removal = array();

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

		$array_martix[] = [
			'matrix_tf' => $matrix_tf,
			'text_idfplus1' =>  $text_idfplus1,
			'text_df' => $text_df,
			'text_idf' => $text_idf,
			'matrix_tfidf' => $matrix_tfidf,
			'text_list_word' => $text_list_word
		];

		// var_dump($array_martix[0]['matrix_tfidf']);
  //       exit();

		return $array_martix;

	}

	function lsa($matrix_tfidf = null, $id_sentences = null, $id_doc = null){

		// var_dump($id_sentences);
		
		$errorlevel = error_reporting();
        error_reporting($errorlevel & ~E_NOTICE);

        $this->load->library("singularvaluedecomposition");		
		$svd = $this->singularvaluedecomposition->SVD($matrix_tfidf);

		// $m = count($matrix_tfidf);
  //       $n = count($matrix_tfidf[0]);

		// $matrix_Vt = $this->singularvaluedecomposition->matrixRound($svd['V']);
		// $matrix_S  = $this->singularvaluedecomposition->matrixRound($svd['S']);

		$matrix_Vt = $this->singularvaluedecomposition->matrixRound($svd['V']);
		$matrix_S  = $this->singularvaluedecomposition->matrixRound($svd['S']);



		// var_dump($matrix_Vt);
		// echo "<br><br>";
		// var_dump($matrix_S);

		// exit();



        $rows_matrix_Vt = count($matrix_Vt);
        $cols_matrix_Vt = count($matrix_Vt[0]);
        
        $rows_matrix_S = count($matrix_S);
        $cols_matrix_S = count($matrix_S[0]);

  //       for($i = 0; $i < $rows_matrix_Vt; $i++){
		// 	for($p = 0; $p < $cols_matrix_Vt; $p++){
		// 		$rows_matrix_Vt[$i][$p] = 
		// 	}
		// }
        
        if($cols_matrix_Vt == $rows_matrix_S){
            for($i = 0; $i < $rows_matrix_Vt; $i++){
                for($j = 0; $j < $cols_matrix_S; $j++){
                    for($p = 0; $p < $cols_matrix_Vt; $p++){
                        $matrix_LSA[$i][$j] += $matrix_Vt[$i][$p] * $matrix_S[$p][$j];
                        // if($matrix >= 0){
                        // 	$matrix_LSA[$i][$j] = sqrt($matrix);
                        // }else{
                        // 	$matrix_LSA[$i][$j] = -sqrt(abs($matrix));
                        // }
                    }
                    // $matrix_LSA[$i][$j] = sqrt($matrix_LSA[$i][$j]);
                }
            }
        }

        $lsa_Length = [];

        foreach ($matrix_LSA as $key => $value) {
            foreach ($value as $k => $v) {
            	$lsa_Length[$k] += $v;
            }

        }

        // var_dump($id_sentences);

        foreach ($lsa_Length as $key => $value) {
        	if($value >= 0){
                    $value = sqrt($value);
            }else{
                    $value = -sqrt(abs($value));
            }
        	$data = array(
		        	'f1' => $value
		    );

        	$this->db->where('id_sentence', $id_sentences[$key]);
		    $this->db->update('sentence', $data);
        }

        return $this->db->where('fk_documents', $id_doc)->get('sentence')->result();

	}

	public function feature_contain_numeric($text = null, $id_doc = null){
		
		$tokenizing = [];
		$id_sentences = [];
		foreach ($text as $key => $value) {
			$id_sentences[$key] = $value->id_sentence;
			$tokenizing[$key] = $this->process($value->sentence,false,false,false,true,false);
		}

		$feature_numeric = [];
        foreach ($tokenizing as $key => $value) {
            $numeric_amount = 0;
            foreach ($value as $k => $v) {
                if (is_numeric(preg_replace("/[^a-zA-Z0-9\s .]/", "", $v))) {
                    $numeric_amount++;
                }
            }
            $feature_numeric[$key] = $numeric_amount / count($value);

            $data = array(
		        	'f2' => $feature_numeric[$key]
		    );

            $this->db->where('id_sentence', $id_sentences[$key]);
		    $this->db->update('sentence', $data);
        }



        return $this->db->where('fk_documents', $id_doc)->get('sentence')->result();
	}

	public function getSummaryDocuments(){
		$id = $this->input->post('id');
		$kompresi = $this->input->post('kompresi');

		$countSentence = count($this->db->where('fk_documents', $id)->get('sentence')->result());
		$sentenceSummary = round($countSentence * $kompresi);

		$result['data'] = $this->db->query('SELECT id_sentence,sentence FROM sentence
				    JOIN 
				    (
				        SELECT id_sentence as id2
				        FROM sentence 
				        WHERE fk_documents = '.$id.'
				        ORDER BY bobot desc LIMIT '.$sentenceSummary.'
				    ) d
				    ON sentence.id_sentence
				    IN (d.id2)
				    ORDER BY sentence.id_sentence asc')->result();
		echo json_encode($result);
	}

	public function getSentenceDocuments(){
		$id = $this->input->post('id');
		$result['data'] = $this->db->select('id_sentence,sentence')->where('fk_documents', $id)->get('sentence')->result();

		echo json_encode($result);		
	}

	public function goProcess($id){
		$data['ids'] = $id;

		$plain = $this->db->where('fk_documents', $id)->get('sentence')->result();
		$data['noPerkara'] = $this->db->select('no_perkara')->where('id', $id)->get('documents')->result_array();

		$preprocess = $this->pre_processing($plain, $id);
		$a = $this->tf_idf($preprocess, $id);

		$data['matrix_tf'] 		= $a[0]['matrix_tf'];
		$data['text_df'] 		= $a[0]['text_df'];
		$data['text_idf'] 		= $a[0]['text_idf'];
		$data['text_idfplus1'] 	= $a[0]['text_idfplus1'];
		$data['matrix_tfidf'] 	= $a[0]['matrix_tfidf'];
		$data['text_list_word'] = $a[0]['text_list_word'];

		$data['count'] = 0;

		foreach ($a[0]['matrix_tf'] as $key => $value) {
			$data['count'] = count($value);
			break;
		}
		$this->load->view('pages/process',$data);
	}

	public function getPreprocess($id){

		$plain = $this->db->where('fk_documents', $id)->get('sentence')->result();
		// $result['data'] = $this->db->select('id_sentence,sentence')->where('fk_documents', $id)->get('sentence')->result();

		// var_dump($plain);

		$preprocess = $this->pre_processing($plain, $id);
		$prepro =  [];
		$stopwords = [];

		foreach ($preprocess[0]['preprocessing_sentences'] as $k => $v) {
			// $stopwords[$k] = $v[$k];
			$arr = implode(" ",$v);
			$stopwords[$k] = explode(" ", $arr);			
		}

		foreach ($preprocess[0]['filtering_sentence'] as $key => $value) {
			$prepro[$key]['plain']			= $plain[$key]->sentence;
			$prepro[$key]['casefolding']	= $preprocess[0]['filtering_sentence'][$key];
			$prepro[$key]['filtering']	= $preprocess[0]['filtering_sentence'][$key];
			$prepro[$key]['stemming']	= $preprocess[0]['stemming_sentence'][$key];
			$prepro[$key]['tokenizing']	= $preprocess[0]['tokenizing_sentence'][$key];
			$prepro[$key]['stopwords']	= $stopwords[$key];
		}

		$result['data'] = $prepro;
		
		echo json_encode($result);
	}

	public function eigenValue(){

		$errorlevel = error_reporting();
        error_reporting($errorlevel & ~E_NOTICE);

		$id = 1;
		$plain = $this->db->where('fk_documents', $id)->get('sentence')->result();
		$data['noPerkara'] = $this->db->select('no_perkara')->where('id', $id)->get('documents')->result_array();

		$preprocess = $this->pre_processing($plain, $id);
		$a = $this->tf_idf($preprocess, $id);
		$tfidf = $a[0]['matrix_tfidf'];

		// $m = count($matrix);
  //       $n = count($matrix[0]);
        
  //       for($i = 0; $i < $m; $i++){
  //           for($j = 0; $j < $n; $j++){
  //               var_dump($tfidf[$i][$j]);
  //           }

  //           echo "<br>";
  //       }

		// $matrix_tfidf = [];
		// foreach ($tfidf as $key => $value) {
		// 	foreach ($value as $k => $v) {
		// 		echo $tfidf[$key][$k]." ";
		// 	}
		// 	echo "<br>";
		// }

		// echo "<br>";
		// echo "<br>";

		foreach ($tfidf as $key => $value) {
			foreach ($value as $k => $v) {
				$tranposeTfidf[$k][$key] = $v;
				// echo $tranpose[$k][$key]." ";
			}
			// echo "<br>";
		}

		// var_dump($tfidf);

		// echo "<br>";
		// echo "<br>";

		// var_dump($tranposeTfidf);

		$this->load->library("singularvaluedecomposition");		
		$mmult = $this->singularvaluedecomposition->matrixMultiplication($tranposeTfidf,$tfidf);

		var_dump($mmult);

		foreach ($mmult as $key => $value) {
			foreach ($value as $k => $v) {
				if($key == $k)
				echo $tfidf[$key][$k]." ";
			}
			echo "<br>";
		}





		// var_dump($tfidf);

		// echo "<br>";

		// var_dump($tranpose);

        // // var_dump($m);
        // // var_dump($n);
        
        // for($i = 0; $i < $m; $i++){
        //     for($j = 0; $j < $n; $j++){
        //         echo $tfidf[0][0];
        //     }

        //     echo "<br>";
        // }



        // var_dump($tfidf);

  //       for($i=0;$i<$m;$i=$i++)
  //       {
  //       	for($j=0;$j<$n;$j=$j++)
  //       	{
  //       		// $a[$i][$j]=$tfidf[$j][$i]; //assigning the values of elements of each column to each row
  //       		 echo $i;
  //       		 echo $j;


  //  			}
   		
  //  		echo "<br>"; //here this is creating a new line 
		// }

        // var_dump($matrixT);
	}
	// public function getTfidf($id){

	// 	$plain = $this->db->where('fk_documents', $id)->get('sentence')->result();
	// 	// $result['data'] = $this->db->select('id_sentence,sentence')->where('fk_documents', $id)->get('sentence')->result();

	// 	// var_dump($plain);

	// 	$preprocess = $this->pre_processing($plain, $id);
	// 	$matrix = $this->tf_idf($preprocess, $id);
	// 	$result['tfidf']

	// 	// $a = [];


	// 	// foreach ($result['data'] as $k => $v) {
	// 	// 	// $stopwords[$k] = $v[$k];
	// 	// 	$arr = implode(" ",$v);
	// 	// 	$a[$k] = explode(" ", $arr);			
	// 	// }

	// 	// $result['data'] = $a;



	// 	// var_dump($result['count']);
		
	// 	echo json_encode($result);
	// }

	public function getMethod($id){

		$result['data'] = $this->db->where('fk_documents', $id)->get('sentence')->result();	
		echo json_encode($result);
	}




	// public function bobot_total($text = null, $id_doc = null){
		
	// }



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


