<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends CI_Controller {

	public function __construct(){
		parent::__construct();

		if ($this->session->userdata('logged_in')) {
            $session_data=$this->session->userdata('logged_in');
            $data['username']=$session_data['username'];
            $data['type']=$session_data['type'];
            $current_controller = $this->router->fetch_class();
            $this->load->library('Acl');
            if (! $this->acl->is_public($current_controller)){
                if (! $this->acl->is_allowed($current_controller, $data['type'])){
                    echo "<script>alert('You Do not Have Permission to Access This'); </script>";
                    redirect($_SERVER['HTTP_REFERER'],'refresh');
                }
            }
        }else{
            redirect('Home/login','refresh');
        }
	}

	public function index()
	{
		$session_data=$this->session->userdata('logged_in');
        $data['username']=$session_data['username'];
        $data['type']=$session_data['type'];
        $data['pic']=$session_data['pic'];

		$this->load->view('pages/testing',$data);
	}

	public function getList()
	{
		$kompresi = $this->input->post('kompresi');
		if($kompresi == null){
			$kompresi = 0.5;
		}
		$this->db->select('*');    
		$this->db->from('testing');
		$this->db->join('documents', 'testing.id_document = documents.id');
		$this->db->where('kompresi',$kompresi);
		$this->db->order_by('id_testing', 'desc');
		$query = $this->db->get();
		$data['data'] = $query->result();
		echo json_encode($data);
	}



	public function addTesting()
	{
		$id = $this->input->post('document');
		$this->load->helper(array('form','url'));
        // set path to store uploaded files
		$config['upload_path'] = './assets/uploads/manualSummary';
        // set allowed file types
		$config['allowed_types'] = 'pdf';
        // set upload limit, set 0 for no limit
		$config['max_size']    = 0;

        // load upload library with custom config settings
		$this->load->library('upload', $config);

         // if upload failed , display errors

		if (!$this->upload->do_upload('file')){
		 			$data['error'] = $this->upload->display_errors();
			$this->load->view('pages/testing', $data);
		}
		else
		{
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$file_name = $upload_data['file_name'];

			$result = $this->pdf($file_name,$id);

			if($result != null){

				$kompresi = array(0.25,0.5,0.75);

				for ($i=0; $i < 3 ; $i++) { 
					$getSummaryResult[$i] = $this->getSummaryResult($id,$kompresi[$i]);
					// var_dump($getSummaryResult[$i]);
					// exit();
					$precision[$i]		  = $this->precision($getSummaryResult[$i],$result);
					$recall[$i]	   		  = $this->recall($getSummaryResult[$i],$result);
					$f_measure[$i]		  = $this->f_measure($precision[$i],$recall[$i]);
					$accuracy[$i]		  = $this->precision($getSummaryResult[$i],$result,$id);

					$data_test = array(
								'id_document'		=> $id,
								'list_auto'			=> implode(",",$getSummaryResult[$i]),
								'list_manual'		=> implode(",", $result),
								'precision'			=> number_format($precision[$i],3),
								'recall'			=> number_format($recall[$i],3),
								'f_measure'			=> number_format($f_measure[$i],3),
								'accuracy'			=> number_format($accuracy[$i],3),
								'kompresi'			=> $kompresi[$i],
					);
					$insert = $this->db->insert('testing', $data_test);
				}	
			}

			$kompresi = $this->input->post('kompresi');
			if($kompresi == null){
				$kompresi = 0.5;
			}
			$data['data'] = $this->db->query(' SELECT documents.id, testing.recall,testing.precision,testing.f_measure, testing.accuracy FROM `documents` join testing on documents.id = testing.id_document where kompresi = '.$kompresi)->result();	

			echo json_encode($data);
		}  
	}

	public function pdf($file_name = null, $id_doc = null){
		$server_file = base_url('assets/uploads/manualSummary/'.$file_name);

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

			        // Set the boundaries of errors you can accept
			        // E.g., we reject the change if there are 30 or more $no_spacing_error or 150 or more $excessive_spacing_error issues
		        if ($no_spacing_error >= 30 || $excessive_spacing_error >= 150) {
		        	$result = null;

		        } else {
			       	$sentence = $this->db->get_where('sentence',array('fk_documents' => $id_doc))->result();
			        $sentenceManual = $this->paragraph_to_sentences($text,$id_doc);

			        $arrayIdSentenceManual = array();
			        foreach ($sentence as $key => $value) {
			        	foreach ($sentenceManual as $k => $v) {
			        		$a = similar_text( $value->sentence, $v, $percent);
			        		if( $percent >= 95 ){
			        			array_push( $arrayIdSentenceManual, $value->id_sentence);
			        		}
			        	}
			        }

			        $result = $arrayIdSentenceManual;

		        }
		        /* End of additional step */
		        /**************************/

		    } else {
		    	$result = null;
		    }
		} else {
			$result = null;
		}

		return $result;
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

		$sentences_array = array();
		foreach ($result as $k => $v) {
			if (trim($v) == "") {
				continue;
			}
			if (substr($v, -1, 1) == ".") {
				$v = substr($v, 0, -1);
			}
			array_push($sentences_array,$v);
		}

		return $sentences_array;
	}

	public function getSummaryResult($id_doc = null, $kompresi = null){

		$countSentence = count($this->db->where('fk_documents', $id_doc)->get('sentence')->result());
		$sentenceSummary = round($countSentence * $kompresi);

		$result = $this->db->query('SELECT id_sentence FROM sentence
				    JOIN 
				    (
				        SELECT id_sentence as id2
				        FROM sentence 
				        WHERE fk_documents = '.$id_doc.'
				        ORDER BY f1 desc LIMIT '.$sentenceSummary.'
				    ) d
				    ON sentence.id_sentence
				    IN (d.id2)
				    ORDER BY sentence.id_sentence asc')->result();

		foreach ($result as $key => $value) {
			$sentence[$key]	= $value->id_sentence; 	
		}
		
		return $sentence;
	}

	public function precision($summaryAuto = null, $summaryManual = null){
		$array_join = array_intersect($summaryAuto, $summaryManual);
		$tpfp = count($summaryAuto);
		$tp = count($array_join);

		// var_dump($summaryAuto);
		// exit();

		return $precision = $tp / $tpfp;

	}

	public function recall($summaryAuto = null, $summaryManual = null){
		$array_join = array_intersect($summaryAuto, $summaryManual);
		$tpfn = count($summaryManual);
		$tp = count($array_join);

		return $recall = $tp / $tpfn;
		
	}

	public function f_measure($precision = null, $recall = null){
		return $f_measure = 2* ($precision*$recall) / ($precision+$recall);
	}

	public function accuracy($summaryAuto = null, $summaryManual = null, $id_doc = null){

		$array_join = array_intersect($summaryAuto, $summaryManual);
		$tp = count($array_join);

		$sentence = $this->db->select('sentence')->get_where('sentence',array('fk_documents' => $id_doc))->result();

		$join = array_merge($summaryAuto,$summaryManual);
		$join = array_unique($join);

		$sentenceNotInSummary = array();

        $sentenceNotInSummary = array_filter($sentence, function ($key) {
                return !in_array($key, $join);
            });
        // var_dump($sentenceNotInSummary);
        // exit();

        $tn = count($sentenceNotInSummary);

        $fn = count($summaryManual)-$tp;
        $fp = count($summaryAuto)-$tp;

        return $accuracy = ($tp+$tn)/($tp+$tn+$fn+$fp);
    }

    public function grafik(){
    	$kompresi = $this->input->post('kompresi');
		if($kompresi == null){
			$kompresi = 0.5;
		}
		$data['data'] = $this->db->query(' SELECT documents.id, testing.recall,testing.precision,testing.f_measure, testing.accuracy FROM `documents` join testing on documents.id = testing.id_document where kompresi = '.$kompresi)->result();
        echo json_encode($data);
    }

    public function grafikAverage(){
    	$kompresi = $this->input->post('kompresi');
		if($kompresi == null){
			$kompresi = 0.5;
		}
		$data['data'] = $this->db->query(' SELECT documents.id, testing.recall,testing.precision,testing.f_measure, testing.accuracy FROM `documents` join testing on documents.id = testing.id_document where kompresi = '.$kompresi)->result();
        echo json_encode($data);
    }

    public function deleteList(){
		$no = $this->input->post('id');
		$delete = $this->db->where('id_document', $no)->delete('testing');

		$kompresi = $this->input->post('kompresi');
		if($kompresi == null){
			$kompresi = 0.5;
		}
		$data['data'] = $this->db->query(' SELECT documents.id, testing.recall,testing.precision,testing.f_measure, testing.accuracy FROM `documents` join testing on documents.id = testing.id_document where kompresi = '.$kompresi)->result();

		echo json_encode($data);
	}
}

?>