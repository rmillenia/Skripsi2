<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Summarize extends CI_Controller {

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
		$this->load->view('dashboard');
	}

	public function go()
	{
		$parser = new \Smalot\PdfParser\Parser();
        $pdf    = $parser->parseFile(base_url('assets/uploads/news3.pdf'));

        $text = $pdf->getText();  

        $afterString = 'PENDAHULUAN';
        $beforeString = 'II.';

		$text = substr($text, strpos($text, $afterString) + strlen($afterString));    
        $text = strstr($text, $beforeString, true);


        echo $text;
	}
}
