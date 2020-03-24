<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hello extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	public function index()
	{
		$this->load->model('biodata');
		$data['biodata_array']= $this->biodata->getBiodataQueryArray();
		$data['biodata_object']= $this->biodata->getBiodataQueryObject();
		$data['biodatabuilder_array']= $this->biodata->getBiodataBuilderArray();
		$data['biodatabuilder_object']= $this->biodata->getBiodataBuilderObject();
		$this->load->view('hello',$data);
	}

	public function about(){
		$this->load->view('about');
	}

	public function news(){
		$this->load->view('news');
	}

	public function contact(){
		$this->load->view('contact');
	}

	public function dataPegawai(){
		$where = $this->uri->segment(2);
		$this->load->model('biodataPegawai');
		$data['biodata']= $this->biodataPegawai->getBiodata($where);
		$data['biodataPendidikan']= $this->biodataPegawai->getPendidikan($where);
		$data['biodataJumlah']= $this->biodataPegawai->getJumlah($where);
		$this->load->view('biodata',$data);
	}
	public function home(){
		redirect(base_url().'index.php/pegawai/1');
	}


}

/* End of file Hello.php */
/* Location: ./application/controllers/Hello.php */