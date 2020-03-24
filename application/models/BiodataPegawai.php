<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BiodataPegawai extends CI_Model {
	public function getPendidikan($no)
	{
		$query = $this->db->query("select * from pegawai inner join pendidikan where idpegawai=fk_pegawai having idpegawai=$no order by tahunLulus desc");
		return $query->result();
	}
	public function getJumlah($no)
	{
		$query = $this->db->query("select count(fk_pegawai) as jumlahPendidikan from pendidikan where fk_pegawai=$no");
		return $query->result();
	}
	public function getBiodata($no)
	{
		$query = $this->db->query("select * from pegawai where idpegawai=$no");
		return $query->result();
	}



}

/* End of file modelName.php */
/* Location: ./application/models/modelName.php */