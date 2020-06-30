<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			$session_data=$this->session->userdata('logged_in');
			$data['username']=$session_data['username'];
			$data['type']=$session_data['type'];
			$data['pic']=$session_data['pic'];
			$current_controller = $this->router->fetch_class();
			$this->load->library('Acl');
			if (! $this->acl->is_public($current_controller)){
				if (! $this->acl->is_allowed($current_controller, $data['type'])){
					echo "<script>alert('You Do not Have Permission to Access This'); </script>";
					redirect($_SERVER['HTTP_REFERER'],'refresh');
				}else{
					$data['history'] = $this->db->select('*')->from('history')->join('documents', 'history.fk_document = documents.id')->join('users', 'history.fk_user = users.id_user')->order_by('history.date_time','desc')->limit(5)->get()->result();
					$data['count_documents'] = count($this->db->get('documents')->result());
					$data['count_users'] = count($this->db->get('users')->result());
					$this->load->view('pages/dashboard',$data);
				}
			}
		}else{
			redirect('Home/login','refresh');
		}
	}

	public function login()
	{
		$this->load->view('pages/login');
	}

	public function register()
	{
		$this->load->view('pages/register');
	}

	public function cekRegister(){
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('pages/register');
		} else {
			$array = array(
				'fullname' => $this->input->post('fullname'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'pic' 	   => 'user-no-photo.png',
				'type' 	   => 'Pegawai',
				'status' => 1
			 );
			$data['data'] = $this->db->insert('users', $array);
			echo json_encode($data);
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		redirect('Home/login','refresh');
	}

	public function cekDb($password)
	{
		$username = $this->input->post('username'); 

		$this->db->select('id_user,username,password,type,pic,status');
		$this->db->from('users');
		$this->db->where('username', $username);
		$this->db->where('password', MD5($password));
		$result = $this->db->get()->result();

		if(count($result) == 1){
			if($result[0]->status==2){
				$session_array = array();
				foreach ($result as $key) {
					$session_array = array(
						'id'=>$key->id_user,
						'username'=>$key->username,
						'type'=>$key->type,
						'pic'=>$key->pic
					);
					$this->session->set_userdata('logged_in',$session_array);
				}
				return true;
			}else{
				$this->form_validation->set_message('cekDb',"Login Failed, User Don't Have Permission. Please Contact The Administrator");
			return false;
			}
		}else{
			$this->form_validation->set_message('cekDb',"Login Failed");
			return false;
		}
	}

	public function cekLogin()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|callback_cekDb');
		if ($this->form_validation->run() == FALSE) {
			$this->load->view('pages/login');
		} else {
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$data['type'] = $session_data['type'];
			if ($data['type']=='pegawai' or $data['type']=='admin') {
				redirect('Home','refresh');
			}else{
				$this->load->view('403-error');
			} 
		}
	}

	public function fetch_report()
	{
		$id = $this->input->post('id');

		if($id == 1){
			$sql ="SELECT COUNT(id) as total, WEEKDAY(history.date_time) as value FROM documents INNER JOIN history on documents.id = history.id_history WHERE WEEK(history.date_time) = WEEK(CURRENT_TIMESTAMP) GROUP BY WEEKDAY(history.date_time) ORDER BY WEEKDAY(history.date_time)";
		}else if($id == 2){
			$sql ="SELECT count(id) as total, DATE_FORMAT(date_time,'%M') as value FROM documents inner join history on documents.id = history.fk_document GROUP BY DATE_FORMAT(`date_time`, '%m%')";
		}else{
			$sql ="SELECT count(id) as total, DATE_FORMAT(date_time,'%Y') as value FROM documents inner join history on documents.id = history.fk_document GROUP BY DATE_FORMAT(`date_time`, '%Y%')";
		}
 
		$data['data'] = $this->db->query($sql)->result();
		echo json_encode($data);
	}


}
