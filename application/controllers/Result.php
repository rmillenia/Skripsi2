<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends CI_Controller {

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
		$this->load->view('pages/result');
	}

	public function getResult(){
		$data['data'] = $this->db->order_by('id', 'asc')->get('documents')->result();
		echo json_encode($data);
	}

	public function getSummaryResult(){
		$id = $this->input->post('id');
		$kompresi = $this->input->post('kompresi');

		$countSentence = count($this->db->where('fk_documents', $id)->get('sentence')->result());
		$sentenceSummary = round($countSentence * $kompresi);

		$result['data'] = $this->db->query('SELECT id_sentence,sentence FROM sentence
				    JOIN 
				    (
				        SELECT id_sentence as id2
				        FROM sentence 
				        WHERE fk_documents = 21
				        ORDER BY f1 desc LIMIT '.$sentenceSummary.'
				    ) d
				    ON sentence.id_sentence
				    IN (d.id2)
				    ORDER BY sentence.id_sentence asc')->result();
		
		echo json_encode($result);
	}

	public function getSentence(){
		$id = $this->input->post('id');
		$result['data'] = $this->db->select('id_sentence,sentence')->where('fk_documents', $id)->get('sentence')->result();

		echo json_encode($result);		
	}


	
}
