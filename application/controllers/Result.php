<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends CI_Controller {

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
		
		$this->load->view('pages/result',$data);
	}

	public function getResult(){
		$data['data'] = $this->db->select('*')->from('documents')->join('history', 'documents.id = history.fk_document')->order_by('id','asc')->get()->result();
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
				        WHERE fk_documents = '.$id.'
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
