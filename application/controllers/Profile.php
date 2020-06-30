<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

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
		$data['id']=$session_data['id'];
		$data['username']=$session_data['username'];
		$data['type']=$session_data['type'];
		$data['pic']=$session_data['pic'];

		$this->load->view('pages/profile',$data);
	}

	public function getList(){
		$session_data=$this->session->userdata('logged_in');
		$data['id']=$session_data['id'];
		$where = array('id' => $data['id']);
		$data['data'] = get_where('users',$where)->result();
		echo json_encode($data);
	}

	public function updateList(){
		$session_data=$this->session->userdata('logged_in');
		$id = $session_data['id'];
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$fullname = $this->input->post('fullname');

		// var_dump($id);
		// var_dump($username);
		// var_dump($fullname);
		// var_dump($password);
		// var_dump($this->input->post('file'));

		$this->load->helper(array('form','url'));

		$config['upload_path'] = './assets/uploads/pic';
        // set allowed file types
		$config['allowed_types'] = 'png|jpg';
        	// set upload limit, set 0 for no limit
		$config['max_size']    = 0;

		if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])){

        	// load upload library with custom config settings
			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('file'))
			{
				$data['error'] = $this->upload->display_errors();
				$this->load->view('pages/profile', $data);
			}
			else
			{
			$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
			$file_name = $upload_data['file_name'];

			$data = array(
				'username' => $username,
				'fullname' => $fullname,
				'pic' => $file_name		
			);
			
			$session_array = array(
                        'id'=> $session_data['id'],
                        'username'=>$username,
                        'type'=> $session_data['type'],
                        'pic' =>  $file_name
                   		);
        	$this->session->unset_userdata('logged_in');
        	$this->session->set_userdata('logged_in',$session_array);

            // var_dump($session_data['pic']);
			$this->db->where('id_user', $id)->update('users',$data);
			}
		}else{

			$data = array(
				'username' => $username,
				'fullname' => $fullname
			);

			$session_array = array(
		                        'id'=> $session_data['id'],
		                        'username'=>$username,
		                        'type'=> $session_data['type']
		                   		);
        	$this->session->unset_userdata('logged_in');
        	$this->session->set_userdata('logged_in',$session_array);

			$this->db->where('id_user', $id)->update('users',$data);

		}

		if($this->input->post('password')){
			$data1 = array(
				'password' => md5($password)
			);

			$this->db->where('id_user', $id)->update('users',$data1);
		}


		redirect('Profile','refresh');
	}

}
