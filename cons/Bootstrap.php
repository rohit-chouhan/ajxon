<?php
trait Bootstrap {
	var $divclose;
	var $bootformgroup;
	var $bootcard;
	var $bootlabel;
	function bootstrap(){
		$this->divclose = '</div>';
		$this->bootformgroup = '<div class="form-group">';
		$this->bootcard = '<div class="card p-4">';
		$this->bootlabel = true;
	}
}
?>
