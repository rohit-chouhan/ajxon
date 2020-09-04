<?php	

	trait Input {
		// inheritance to import this class to anither
		var $incls;
		var $btcls;
		var $btnam;
		var $instl;
		var $btstl;
		
		function inputclass($x){
			$this->incls = $x;
		}
		
		function inputstyle($x){
			$this->instl = $x;
		}
		
		function btnclass($x){
			$this->btcls = $x;
		}
		
		function btnstyle($x){
			$this->btstl = $x;
		}
		
		function btnname($x){
			$this->btnam = $x;
		}
			
	}

?>