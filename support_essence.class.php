<?php
if(!isset($_SESSION)){ session_start(); }
/**************************************
	KD Support Essentials 8
	By Ken Mason
	Created 5.08.2023
	Modified 5.08.2023
	Version maynot be PHP4 Clean
**************************************/

// -- SUPPORT_ESSENCE ---- //
// ---------// ---------//////
// lprint($msg,$var="EMPTY VAR")
// myimplode($arr,$enclose=" | ")
// die_right($msg,$row=array())
// pmsg($title,$msg)
// --> debug_msg($msg,"Debug: ".$title)
// --> lprint($title,$msg)
// display_msg($msg="",$params=array())
// kd_display_msg($title,$msg,$code="",$style="")
// get_kd_box($title,$msg,$style="")
// lod($classfile,$classname)
// load_class($myclass)
// get_sqlmsg($msg)
// get_userid()
// get_procid() -> classid.'_'.date("Y-m-d") !todo
// result_tf($tf,$msg,$row="")
// $result=$this->result_array
// is_blank($value)
// left($string, $count)
// right($str, $length)
// __toString()
// class_method_exists_tf($class,$methodname="")
// class_method_exists($class,$methodname="")
// mclean($var="")
// todo($msg,$type=""): type=msg,die,fatal,todo

CLASS SUPPORT_ESSENCE
{
	public $my_style_loaded=false;
	public $result_array=array('result'=>false, 'msg'=>"", 'ct'=>0, 'data'=>array());
	public static $version="20230508";
	public static $classname="SUPPORT ESS8";
	public $classid="SUPPORT_ESS8";
	public $debugme=false;
	public $msglog=array();
    public $steps=array();
	
	// --------- GENERAL HELPERS // ---------///////////

	function __construct()
	{ 


	}
	
	function get_version()
	{
		return self::$version;
	}

	function write_error_log($msg,$action)
	{
		// todo create this method!
	}

	// Fatal Error Write log & Die Gracefully
	function die_right($msg,$row=array())
	{
		global $dbo;
		$action=$this->classid." :Fatal Error:";
		$this->write_error_log($msg,$action);
		if(count($this->steps)>0){
			$this->lprint("Processing Steps",$this->steps);
		}
		$dbo->close();
		die($msg);
	}
		
	// Print MSG if Module Debugme==true
	function pmsg($title,$msg,$code="")
	{
	        if($this->debugme){
	            $this->debug_msg($msg,"Debug: ".$title,$code);
	        } else {
	          $this->lprint($title,$msg);
	        }		
	}
	
	// 20230508
	function log_error($action,$msg,$classid="",$app="")
	{
		global $dbo,$kip;
		// `error_log`(`id`, `date`, `time`, `userid`, `module`, `ipaddr`, `action`, `app`, `msg`)
		if(!isset($classid) || $classid==""){ $classid=$this->classid; }
		if(!isset($app) || $app==""){ $app=$this->classid; }
		if(!isset($kip) || trim($kip)==""){ $kip=$this->get_real_ip(); }
		$userid=$this->get_userid();
		$sql='insert into error_log ( `date`, `time`, `userid`, `module`, `ipaddr`, `action`, `app`, `msg`) ';
		$sql .='values (now(),now(),"'.$userid.'","'.$classid.'","'.$kip.'","'.$app.'","'.$msg.'")';
		$res=$dbo->query($sql);
		return $res;
	}
	
    function debug_msg($msg,$action,$code="")
    {
        if($this->debugme){
            $params=array("title"=>$action, "code"=>$code);
            $this->display_msg($msg,$params);
        }
        return true;
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

	// load_or_die("dbo","dbimin.class.php","DBIMIN"):class instance
	function lod($classfile,$classname)
	{
		$classid=$this->classid;
		if (!class_exists($classname)) { 
			$res = $this->load_class($classfile);
			if(!$res){
				die($this->classid." Cannot Load [".$classfile."]");
			} else {
				$this->msglog[]=' loaded ['.$classfile.']';
			}
		} 
		
		if (class_exists($classname)) {
			$instance = new $classname();
			return $instance;
		} else {
			die($this->classid.' Cannot find class['.$classname.'] in CLASSFILE: ['.$classfile."]");
		}
		return false;	
	}

	function load_class($classfile,$classname="")
	{
		global $dbo;
		$myfile2 = dirname(__FILE__) . '/'.$classfile;
		if(file_exists($classfile)){
			include_once $classfile;
		} elseif(file_exists('classes/'.$classfile)){
			include_once 'classes/'.$classfile;
		} elseif (file_exists('includes/'.$classfile)){
			include_once 'includes/'.$classfile;
		} elseif(file_exists('../classes/'.$classfile)){
			include_once '../classes/'.$classfile;
		} elseif(file_exists('../includes/'.$classfile)){
			include_once '../includes/'.$classfile;
		} elseif(file_exists($myfile2)){
			include_once $myfile2;
		} else { 			
            $msg="[720]".$this->$classid.": Can't Load [".$myclass."].";
            return $this->die_right($msg);		
		}				
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
		$userid = isset($_SESSION['uid']) ? $_SESSION['uid'] : $this->classid;
		return $userid;
	}

	// ---------////

	function is_blank($value) 
	{
		return !isset($value) || trim($value) === '';
	}
	 
	function left($string, $count)
	{
		return substr($string, 0, $count);
	}

	function right($str, $length) 
	{
		 return substr($str, -$length);
	}

	function __toString()
	{
		return $this->classname;
	}

	// --------- RESULT HELPERS // ---------///////////

	function class_method_exists_tf($class,$methodname="")
	{
		if(method_exists($class,$methodname)){
			return true;
		}
		if($this->debugme==true){
			$msg=$this->classid.':ERROR 995: Method ['.$methodname.'] does not Exist in Class ['.$class.']';
			echo '<div class="error">'.$msg.'</div>';
		}
		return false;
	}

	function class_method_exists($class,$methodname="")
	{
		$result=array('result'=>false,'msg'=>"",'data'=>array());
		$classname=get_class($class);
		if(method_exists($class,$methodname)){
			$msg=$this->classid.'Success Method ['.$methodname.'] for Class ['.$classname.']';
			$data['version']=isset($class->version) ? $class->version :"[no version]";
			$msg .=' version ['.$data['version'].'] ';
			return $this->result_tf(true,$msg,$data);
		} else {
			$msg=$this->classid.'Error: Invalid Method ['.$methodname.'] for Class ['.$classname.']';
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
        // todo Fixme update this class
		$var=str_replace("'","",$var);
		return $var;
	}

	function get_real_ip()
	{
		global $kip;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		  $kip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		  $kip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
		  $kip = $_SERVER['HTTP_X_REAL_IP'];
		} else {
		  $kip = $_SERVER['REMOTE_ADDR']; 
		}
		return $kip;
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

	
    function display_msg($msg="",$params=array())
    {
		$code=$style="";
		$title=$this->classid.' Message ';
		if(is_array($params)){
			if(isset($params['title'])){
				$title=$msg['title'];
			}
			if(isset($msg['code'])){ $code=$msg['code']; }
			if(isset($msg['style'])){ $style=$msg['style']; }
		}
		return $this->kd_display_msg($title,$msg,$code,$style);
	}

	// 20230313
	// kd_display_msg($title,$msg,$code="",$style="")
	// kd_display_msg($msg="",$params=array(title,code,style))
    function kd_display_msg($title,$msg,$code="",$style="")
    {
        global $config;
		if(is_array($msg)){
			// is $msg array actually params
			if(isset($msg['title'])){
				// $msg is $params so $title is $msg
				$the_msg=$title;
				$title=$msg['title'];
				if(isset($msg['code'])){ $code=$msg['code']; }
				if(isset($msg['style'])){ $style=$msg['style']; }
				$msg=$the_msg;
			}
		}
		if($code<>""){
			$title .=' [CODE: '.$code.']';
		}
		$out =$this->get_kd_box($title,$msg,$style);
		echo $out;
	}
	
	// 20230313
	function get_kd_box($title,$msg,$style="")
	{
		$out='<div style="text-align: center; border-style: double; width: 64%;margin-left: 10%;">';
		$out .='<p style=" text-align: center; color:blue;">'.$title.'</p>';
		$out .='<p style=" text-align: center; color: green">';
		if(!is_array($msg)){
			$out .=$msg;
		} else {
			$out .=$this->myimplode($msg);
		}
		$out .='</p></div>';
		return $out;
	}

}  // --------- End Essentials Class ///


?>