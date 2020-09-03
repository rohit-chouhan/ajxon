<?php
#############== Ajxon ==#############
# Developed by : Rohit Chouhan
# Contact      : fb.com/itsrohitofficialprofile
# Github       : github@rohit-chouhan
# LICENCE      : MIT
#######################################
error_reporting(0);
class Ajxon {
   var $json;
   var $input=array();
   var $js=array();
   var $columns=array();
   var $data=array();
   var $ftype=array();
   var $fr=array();
   var $url;
   var $form_name;
   var $table;
   var $dataType;

    function set($j){
        $this->json=json_decode($j,true);
    }
    function gen(){
            unset($this->input);
            unset($this->js);
            unset($this->columns);
            unset($this->data);
            unset($this->ftype);
            unset($this->fr);
            $this->url=$this->json['url'];
            $this->form_name=$this->json['form'];
            $this->table=$this->json['table'];
            $this->dataType=$this->json['type'];

            foreach ($this->json['field'] as $key => $value) {
            $arr=explode(":",$value);
            $this->data[$key]=$arr[0];
            $this->ftype[]=$arr[1];
            $this->fr[]=$arr[2]=='true'?'required':'';
            }

            $fcount=0;
            foreach ($this->data as $key => $value) {
                $this->js[]= ' '.$key.':$("#'.$value.'").val()';
                $this->input[]='<input id="'.$value.'" type="'.$this->ftype[$fcount].'" placeholder="Enter '.$value.'" '.$this->fr[$fcount].' />';
                $this->columns[]=$key;
                $this->values[]='\'".$_POST[\''.$key.'\']."\'';
                $fcount+=1;
            }
    }

    function html(){
        $this->gen();
        $re='
        <form id="'.$this->json['form'].'" method="post" name="'.$this->json['form'].'">
        '.implode("\n\t\t",$this->input)."\n".'<button type="submit">Save</button>
         </form>';
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
        $fp = fopen($file, "w") or die("Couldn't open $file for writing!");
        fwrite($fp, $sql) or die("Couldn't write values to file!");
        fclose($fp);
    }

}
?>