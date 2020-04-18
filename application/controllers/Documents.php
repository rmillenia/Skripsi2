<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
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

						$this->paragraph_to_sentences($text, $id_doc);

						


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

	function paragraph_to_sentences($text = null, $id_doc = null){

		// untuk mecah perkalimat
		$result = preg_split('/(?<!Th.|No.|Perk.|Reg.|Rp.)(?<=[.?!;])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

		$arr_sentence = [];
		foreach ($result as $k => $v) {
                if (trim($v) == "") {
                    continue;
                }
                if (substr($v, -1, 1) == ".") {
                    $v = substr($v, 0, -1);
                }
                $arr_sentence[] = [
                    'sentence' => $v,
                    'fk_documents' => $id_doc
                ];
        }
 
        return $this->db->insert_batch('sentence', $arr_sentence);
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


