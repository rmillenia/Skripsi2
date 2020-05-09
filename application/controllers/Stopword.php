<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stopword extends CI_Controller {

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
		$this->load->view('pages/stopword');
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

	// public function updateList(){
	// 	$where = array('id' => $this->input->post('id'));
	// 	$array = array(
	// 			'stopword' => $this->input->post('stopword')
	// 			);

	// 	$data['data'] = $this->db->where($where)->update('stopword_list', $array);
	// 	echo json_encode($data);
	// }

	public function deleteList(){
		$no = $this->input->post('id');
		$data['data'] = $this->db->where('id', $no)->delete('stopword_list');
		echo json_encode($data);
	}



}
