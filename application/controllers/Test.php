<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	public function index()
	{
		$A = array(array(1,1),array(3,5));
		$a = $this->getEigenValues($A);
	}

	public function getEigenValues($A){


	}

	public function calculateBForEquation($matrix){
		 return (matrix[0][0] + matrix[1][1]) * -1;
	}

	public function calculateCForEquation($matrix) {
        return matrix[0][0] * matrix[1][1] - matrix[0][1] * matrix[1][0];
    }

    public function solveDeterminant($b, $c) {
        return sqrt(pow($b, 2) - 4 * $c);
    }



}

?>