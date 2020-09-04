<?php
#############== Ajxon ==#############
# Developed by : Rohit Chouhan
# Contact      : fb.com/itsrohitofficialprofile
# Github       : github@rohit-chouhan
# LICENCE      : MIT
#######################################
error_reporting(0);
require 'cons/Input.php';
require 'cons/Bootstrap.php';
class Ajxon{
	
   use Input;
   use Bootstrap;
   
   //---------------------------
   
   var $json;
   var $input=array();
   var $js=array();
   var $columns=array();
   var $data=array();
   var $ftype=array();
   var $fr=array();
   var $stype=array();
   var $url;
   var $form_name;
   var $table;
   var $dataType;

    function set($j){
        $this->json=json_decode($j,true);
		if($this->json==null){
			header('location:cons/Err/Invalid.php');
		}
    }
	
	
    function gen(){
            unset($this->input);
            unset($this->js);
            unset($this->columns);
            unset($this->data);
            unset($this->ftype);
            unset($this->fr);
            unset($this->stype);
            $this->url=$this->json['url'];
            $this->form_name=$this->json['form'];
            $this->table=$this->json['table'];
            $this->dataType=$this->json['type'];

            foreach ($this->json['field'] as $key => $value) {
				
			unset($select);
			unset($ss);
			unset($fin);
			
            $arr=explode(":",$value);
            $this->data[$key]=$arr[0];
            $this->ftype[]=$arr[1];
            $this->fr[]=$arr[2]=='true'?'required':'';
			
			if(isset($arr[3])){
			$select[] = explode(",",str_replace(str_split('[]'),"",$arr[3]));
			foreach ($select[0] as $x){
			   $ss[]=explode("|",$x);
		    }
			
			$myinput=empty($this->incls)?'':'class="'.$this->incls.'"';
			$myinputstl=empty($this->instl)?'':'style="'.$this->instl.'"';
		    for($i=0;$i<count($ss);$i++){
				if($arr[1]=='radio') {
					$fin[] = '<br>'. $ss[$i][1].' <input id="'.$ss[$i][0].'" name="'.$arr[0].'" type="radio" value="'.$ss[$i][0].'" '.$this->fr[$i].' />';
				} else if($arr[1]=='select') {
					$fin[] = '<option value="'.$ss[$i][0].'">'.$ss[$i][1].'</option>';
			   }
			}
			
			if($arr[1]=='radio') {
					$this->stype[]=implode("",$fin);
				} else if($arr[1]=='select') {
					$this->stype[]='<select '.$myinput.' '.$myinputstl.' id="'.$arr[0].'">'.implode("",$fin).'</select>';
			   }
			
			} else {
				$this->stype[]='';
			}
			
            }

            $fcount=0;
			
			$myinput=empty($this->incls)?'':'class="'.$this->incls.'"';
			$myinputstl=empty($this->instl)?'':'style="'.$this->instl.'"';
			
            foreach ($this->data as $key => $value) {
				if($this->ftype[$fcount]=='radio') {
					$this->js[]= ' '.$key.':$("#'.$value.':checked").val()';
				} else if ($this->ftype[$fcount]=='select'){
					$this->js[]= ' '.$key.':$("#'.$value.' option:selected").val()';
				} else {
					$this->js[]= ' '.$key.':$("#'.$value.'").val()';
				}
				if($this->stype[$fcount]==''){
					$this->input[]=$this->bootformgroup.''.($this->bootlabel==true?'<label>'.ucfirst(str_replace("_"," ",$key)).'</label>':'').'<input id="'.$value.'" type="'.$this->ftype[$fcount].'" '.$myinput.' '.$myinputstl.' placeholder="Enter '.ucfirst(str_replace("_"," ",$key)).'" '.$this->fr[$fcount].' />'.$this->divclose;
				} else {
				//	$this->input[]=$this->stype[$fcount];
					$this->input[]=$this->bootformgroup.''.($this->bootlabel==true?'<label>'.ucfirst(str_replace("_"," ",$key)).'</label>':'').' '.$this->stype[$fcount].'  '.$this->divclose;
				}
				
                $this->columns[]=$key;
                $this->values[]='\'".$_POST[\''.$key.'\']."\'';
                $fcount+=1;
            }
    }

    function html(){
        $this->gen();
		$mybtn=empty($this->btcls)?'':'class="'.$this->btcls.'"';
		$mybtnstl=empty($this->btstl)?'':'style="'.$this->btstl.'"';
		$mybtnnm=empty($this->btnam)?'Save':''.$this->btnam.'';
        $re='
		'.$this->bootcard.'
        <form id="'.$this->json['form'].'" method="post" name="'.$this->json['form'].'">
        '.implode("\n\t\t",$this->input)."\n".''.$this->bootformgroup.'<button '.$mybtnstl.' '.$mybtn.' type="submit">'.$mybtnnm.'</button>'.$this->divclose.'
         </form>'.$this->divclose;
         echo $re;
    }

    function js(){
        $this->gen();
        if($this->json['include']==''){
            $url='';
        } else {
            $url=$this->json['include']."/".$this->url;
        }
        $re='
            <script>
            $("#'.$this->form_name.'").on("submit", function(event) {
            event.preventDefault();

            var data_ = { 
            '.implode(",\n\t",$this->js).'
            }

            var saveData = $.ajax({
                type: "POST",
                url: "'.$url.'",
                data: data_,
                dataType: "'.$this->dataType.'",
                success: function(resultData) { alert(resultData); }
            });
            saveData.error(function() { alert("Something went wrong"); });
            });
            </script>
        ';
         echo $re;
    }

    function __php(){
        $this->gen();
        $sql='error_reporting(0);'.PHP_EOL;
        $sql.='$conn=mysqli_connect("'.$this->json['db']['host'].'","'.$this->json['db']['username'].'","'.$this->json['db']['password'].'","'.$this->json['db']['database'].'");'.PHP_EOL;
        $sql.='$q=mysqli_query($conn,"INSERT INTO '.$this->table.' ('.implode(",",$this->columns).') VALUES ('.implode(",",$this->values).')");'.PHP_EOL;
        $sql.='if($q){'.PHP_EOL;
        $sql.='echo \'Data has been saved!\';'.PHP_EOL;
        $sql.='} else {'.PHP_EOL;
        $sql.='echo \'Failed to save, try again!\';'.PHP_EOL;
        $sql.='}'.PHP_EOL;
        $re=$sql;
        echo $re;
    }

    function php(){
        $this->gen();
        $sql='<?php'.PHP_EOL;
        $sql.='error_reporting(0);'.PHP_EOL;
        $sql.='$conn=mysqli_connect("'.$this->json['db']['host'].'","'.$this->json['db']['username'].'","'.$this->json['db']['password'].'","'.$this->json['db']['database'].'");'.PHP_EOL;
        $sql.='$q=mysqli_query($conn,"INSERT INTO '.$this->table.' ('.implode(",",$this->columns).') VALUES ('.implode(",",$this->values).')");'.PHP_EOL;
        $sql.='if($q){'.PHP_EOL;
        $sql.='echo \'Data has been saved!\';'.PHP_EOL;
        $sql.='} else {'.PHP_EOL;
        $sql.='echo \'Failed to save, try again!\';'.PHP_EOL;
        $sql.='}'.PHP_EOL;
        $sql.='?>'.PHP_EOL;

        if (!file_exists($this->json['include'])) {
            mkdir($this->json['include'], 0777, true);
        }

        if($this->json['include']==''){
            $url='';
        } else {
            $url=$this->json['include']."/".$this->url;
        }

        $file = $url;
        $fp = fopen($file, "w");
        fwrite($fp, $sql);
        fclose($fp);
    }

}
?>
