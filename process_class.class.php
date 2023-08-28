<?php

/***
			select 
inv.id, school, av_num, serial_num, SerialNumber
, User_Name,  IP_Addresses, LastPolicyRequest
from cid_sccm_import scm, inventory inv 
where fk_inv_id = inv.id
and lower(User_Name) =lower("wmason")
order by LastPolicyRequest desc, scm.id desc	
***/





CLASS MPROCESS extends BASE_CLASS
{
	Var $result_array=array('result'=>false, 'msg'=>"", 'ct'=>0, 'data'=>array());
	var $version="20230215";
	var $name="ROCKISLE0";
	var $moduleid="ROCK_ISLE0";
	var $debugme=false;
	var $procid="";
	var $msglog=array();
	var $steps=array();
	var $limit="";

	
	function write_error_log($msg,$action)
	{
		// todo create this method!
	}

	// $fstran go
	// $fstran fixall 
	// $fstran fixone [$wd3]
	// $fstran preview [$wd3]
	// $fstran hist [$wd3]
	// $fstran error [$wd3]
	// $fstran version
	function command($wd2,$wd3="",$wd4="")
	{
		//$wd2 => -view, -list, -error, -hist, go | -go
		// $wd2 => fixall, fixone [$wd3], skip, hist
		$preview_alias=array("prev","-pre","list","-lis","view","-vie");
		$wd2=trim(strtolower($wd2)); $this->cmd=$wd2;
		$wd24=$this->left($wd2,4);
		$this->steps=array(); // process steps
		$this->daywalker=false;
		$this->steps[]='Processing CMD ['.$wd2.' '.$wd3.' '.$wd4.']'; // process steps
		if($wd2=="go" || $wd2=="-go"){
			$sql=$this->fixall($wd3); 
			return $this->return_sql($sql,$wd2);
		} else if($wd2=="fixall" || $wd2=="-fixall"){ 
			// echo '>>>> WD3 >>>> '.$wd3." <br><br>";
			$sql=$this->fixall($wd3); 
			return $this->return_sql($sql);
		} else if($wd2=="fixone" || $wd2=="-fixone"){ 
			// echo '>>>> WD3 >>>> '.$wd3." <br><br>";
			$sql=$this->fixone($wd3); 
			return $this->return_sql($sql,$wd2);
		} else if(in_array($wd24,$preview_alias)){
			// PREVIEW considered COMMAND ////////
			// $sql=$this->get_preview_sql($wd3); 
			$sql=$this->get_records_sql($wd3);
		} else if($wd24=="hist" || $wd24=="-his"){
			// List records Processed log
			$sql=$this->get_processed_sql($wd3); 
			
		} else if($wd2=="error" || $wd24=="-err"){
			// List records were errors
			$sql=$this->get_error_sql($wd3); 
			
		} else if($wd24=="vers" || $wd24=="-ver"){ // version
			if(!isset($this->modulename)){ $this->modulename="NONAME"; }
			$msg=$this->modulename.' ['.$this->moduleid.'] '.$this->version.' ';
			$sql=$this->get_sqlmsg($msg); 
		} else if($wd2=="set" || $wd24=="-set"){ // set [what] [to] [what]
			if($wd3=="limit" && (int)$wd4 > 0){
				$this->limit=$wd4;
				$msg=$this->moduleid.' [limit]  is set to ('.$this->limit.') ';
				$sql=$this->get_sqlmsg($msg); 
			} else {
				$msg=$this->moduleid.' ['.$wd3.'] cannot be set ('.$wd4.') ';
				$sql=$this->get_sqlmsg($msg);
			}
		} else {
			$sql=$this->get_processed_sql($wd3); // 20230202
		}
		return $this->return_sql($sql,$wd2);
	}
	


	function return_sql($sql,$cmd="")
	{
		// insure we only return sql
		if(is_array($sql)){
			if(isset($sql['sql'])){
				return $sql['sql']; // 20230313
			}
			$this->warning('CMD['.$cmd.'] returned SQL as array -converted ',$sql);
			$cv=$this->myimplode($sql);
			$sql=$this->get_sqlmsg($cv);
		} if(strtolower($this->left($sql,6))<>"select"){
			$this->warning('CMD['.$cmd.'] returned Non Select SQL -converted ',$sql);
			$sql=$this->get_sqlmsg($sql);
		}
		$sql=trim($sql);
		return $sql;
	}
	
	
	function get_records_sql($wd3)
	{
		
	}

	function get_processed_sql($wd3)
	{
		
	}

	function get_error_sql($wd3)
	{
		
	}

	function fixall($wd3)
	{
		
	}	

	function fixone($wd3)
	{
		
	}


	function process_one($record)
	{
		
	}




	/////////////////
	
	function is_blank($value) {
		return !isset($value) || trim($value) === '';
	}
 
	function left($string, $count){
		return substr($string, 0, $count);
	}

	function right($str, $length) {
		 return substr($str, -$length);
	}

	function __toString()
	{
		return $this->classname;
	}
	
	///////////// RESULT HELPERS ////////////////////////

	function class_method_exists_tf($class,$methodname="")
	{
		if(method_exists($class,$methodname)){
			return true;
		}
		if($this->debugme==true){
			$msg=$this->moduleid.':ERROR 995: Method ['.$methodname.'] does not Exist in Class ['.$class.']';
			echo '<div class="error">'.$msg.'</div>';
		}
		return false;
	}
	
	function class_method_exists($class,$methodname="")
	{
		$result=array('result'=>false,'msg'=>"",'data'=>array());
		$classname=get_class($class);
		if(method_exists($class,$methodname)){
			$msg=$this->moduleid.'Success Method ['.$methodname.'] for Class ['.$classname.']';
			$data['version']=isset($class->version) ? $class->version :"[no version]";
			$msg .=' version ['.$data['version'].'] ';
			return $this->result_tf(true,$msg,$data);
		} else {
			$msg=$this->moduleid.'Error: Invalid Method ['.$methodname.'] for Class ['.$classname.']';
			$data['version']=isset($class->version) ? $class->version :"[no version]";
			$msg .=' version ['.$data['version'].'] ';		
		}
		if($this->debugme==true){
			echo '<div class="error">'.$msg.'</div>';
		}
		return $this->result_tf(false,$msg,$data);;
	}
	
	function result_tf($tf,$msg,$row="")
	{
		$result=$this->result_array;
		if($tf==false){
			$result['result']=false;
		} else {
			$result['result']=true;
		}
		$result['msg']=$msg;
		$result['data']=$row;
		return $result;
	}
	
	function mclean($var="")
	{
		if(function_exists("mclean")){
			$var=mclean($var);
		}
		$var=str_replace("'","",$var);
		return $var;
	}
	

	function todo($msg,$type="todo")
	{
		if($type=="todo"||$type=="info"){$type="msg"; }	
		// always do message //
		$title='ToDo Notification ['.$type.']';
		$dmsg=$title.'-> '.$msg;
		if($type=="msg"){ // soft die error message
			$this->pmsg($title,$msg);
		} else if($type=="die"){ // soft die error message
			$this->die_right($dmsg);
		} else if($type=="fatal"){ // die error no recovery
			$this->die_right($dmsg);
		} else {
			$title='ToDo Illegal Option ['.$type.']';
			$dmsg=$title.'-> '.$msg;
			$this->die_right($dmsg);
		}
	}

	// Fatal Error Write log & Die Gracefully
	function die_right($msg,$row=array())
	{
		global $dbo;
		$action=$this->moduleid." :Fatal Error:";
		$this->write_error_log($msg,$action);
		if(count($this->steps)>0){
			$this->lprint("Processing Steps",$this->steps);
		}
		$dbo->close();
		die($msg);
	}
	
	// load_or_die("dbo","dbimin.class.php","DBIMIN"):class instance
	function lod($classfile,$classname)
	{
		$moduleid=$this->moduleid;
		if (!class_exists($classname)) { 
			$res = $this->load_class($classfile);
			if(!$res){
				die($this->moduleid." Cannot Load [".$classfile."]");
			} else {
				$this->msglog[]=' loaded ['.$classfile.']';
			}
		} 
		
		if (class_exists($classname)) {
			$instance = new $classname();
			return $instance;
		} else {
			die($this->moduleid.' Cannot find class['.$classname.'] in CLASSFILE: ['.$classfile."]");
		}
		return false;	
	}
	
	function load_class($myclass)
	{
		// 20190423 Ken -> re-wrote loadclass
		$myfile ="../classes/".$myclass;
		$myfile2 = dirname(__FILE__) . '/'.$myclass;
		if(file_exists($myfile)){
			include $myfile;
		} else if(file_exists($myfile2)){
			include $myfile2;	
		} else {
            $msg="..".$this->$moduleid.": Can't Load [".$myclass."]...72...";
			return $this->die_right($msg);
		}
		return true;
	}

	// 20230202 Ken -> get_sqlmsg($msg)
	function get_sqlmsg($msg)
	{
		$sql='select id as "id",curdate() as "date",DATE_FORMAT(now(),"%h:%i %p") as "time" ';
		// $sql .=',"'.$msg.'" as "message" from inventory where id in (145427,145427,145434,240426) '; // dual for generic
		$sql .=',"'.$msg.'" as "message" from inventory where id > 10 limit 4 '; // dual for generic
		return $sql;
	}

	function get_userid()
	{
		$userid = isset($_SESSION['uid']) ? $_SESSION['uid'] : $this->moduleid;
		return $userid;
	}

	function get_moduleid()
	{
		$this->moduleid = isset($this->moduleid) ? $this->moduleid : 'BASECLASS158';		
		return $this->moduleid;
	}

	function warning($msg,$var="EMPTY VAR")
	{
		echo "<br>".'<div class="warn">'.$msg.'=> ';
		if(is_array($var)){
			print_r($var);
		} else {
			echo $var;
		}
		echo '</div>'."<br>";
	}

	function lprint($msg,$var="EMPTY VAR")
	{
		echo '<br> >>>'.$msg.'=> ';
		if(is_array($var)){
			print_r($var);
		} else {
			echo $var;
		}
		echo '<<<<<< <br>';
	}	
	
	// 20230306
	function myimplode($arr,$enclose=" | ")
	{
		$out="";
		if(!is_array($arr)){
			return $arr;
		}
		foreach($arr as $key => $value){
			$out .= $key.'='.$value.' '.$enclose;
		}
		return $out;		
	}
	
} /////////////////////  END OF CLASS ///////////

?>