<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stopword extends CI_Controller {

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
		
		$this->load->view('pages/stopword',$data);
	}

	public function getList(){
		$data['data'] = $this->db->order_by('id', 'asc')->get('stopword_list')->result();
		echo json_encode($data);
	}

	public function insertList(){
		$array = array(
				'stopword' => $this->input->post('stopword')
				);

		$data['data'] = $this->db->insert('stopword_list', $array);
		echo json_encode($data);
	}

	public function updateList(){
		$where = array('id' => $this->input->post('idStopword'));
		$array = array(
				'stopword' => $this->input->post('stopwordList')
				);

		$data['data'] = $this->db->where($where)->update('stopword_list', $array);
		echo json_encode($data);
	}

	public function deleteList(){
		$no = $this->input->post('id');
		$data['data'] = $this->db->where('id', $no)->delete('stopword_list');
		echo json_encode($data);
	}



}
