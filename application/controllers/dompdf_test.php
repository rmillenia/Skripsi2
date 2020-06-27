<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dompdf_test extends CI_Controller {

	/**
	 * Example: DOMPDF 
	 *
	 * Documentation: 
	 * http://code.google.com/p/dompdf/wiki/Usage
	 *
	 */
	public function index() {	
		// Load all views as normal
		
		// Get output html		
		// Load library
		$this->load->library('pdf');
        $this->pdf->load_view('elements/template');

        // $this->load->view('elements/template');
		
	}
}
