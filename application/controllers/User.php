<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
		
		$this->load->view('pages/user',$data);
	}

	public function getList(){
		$data['data'] = $this->db->order_by('id_user', 'asc')->get('users')->result();
		echo json_encode($data);
	}

	public function insertList(){
		$array = array(
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('username')),
				'fullname' => $this->input->post('fullname'),
				'pic' => 'user-no-photo.png',
				'type' => $this->input->post('type'),
				'status' => 2
				);
		$data['data'] = $this->db->insert('users', $array);
		echo json_encode($data);
	}

	// public function updateList(){
	// 	$where = array('id' => $this->input->post('id'));
	// 	$array = array(
	// 			'stopword' => $this->input->post('stopword')
	// 			);

	// 	$data['data'] = $this->db->where($where)->update('stopword_list', $array);
	// 	echo json_encode($data);
	// }

	public function updateList(){
        $id = 1;

		$result = $this->db->get_where('users',array('id_user' => $id))->result();

		if($result[0]->status == 1){
			$data = array(
		        'status' => 2
			);
		}else{
			$data = array(
		        'status' => 1
			);
		}

		$this->db->where('id_user', $id);
		$this->db->update('users', $data);

		echo json_encode($data);
	}

	public function resetPass(){
		$id = $this->input->post('id');
		$username = $this->input->post('username');

		$data = array(
		        'password' => md5($username)
		);

		$this->db->where('id_user', $id)->update('users',$data);

		echo json_encode($data);
	}



}
