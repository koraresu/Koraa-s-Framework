<?php
function sidebar(){

}
function content(){

}
function title(){

}
function nav(){
	
}
function getParameter($val,$type='all'){
	switch($type){
		case'all':
			if(isset($_POST[$val])){
				return $_POST[$val];
			}else if(isset($_GET[$val])){
				return $_GET[$val];
			}else{
				return false;
			}
		break;
		case 'post':
			if(isset($_POST[$val])){
				return $_POST[$val];
			}else{
				return false;
			}
		break;
		case 'get':
			if(isset($_GET[$val])){
				return $_GET[$val];
			}else{
				return false;
			}
		break;
		case 'cookie':
			if(isset($_COOKIE[$val])){
				return $_COOKIE[$val];
			}else{
				return false;
			}
		break;
	}
}
function PlantillatoJson($plantilla){
	$plantilla=str_replace("\n","",$plantilla);
	$plantilla=str_replace("\t","",$plantilla);

	$plantilla=htmlentities($plantilla);
	return $plantilla;
}
function ArrayDeleteItem($array,$item){
	$ar=array();
	foreach($array as $Ei=>$Ev){
		if($Ev!=$item){
			$ar[$Ei]=$Ev;	
		}
	}
	return $ar;
}
function dateFormat($date,$format='small'){
	switch($format){
		case 'small':
			$fecha=date('d/n/Y',$date);
		break;
		case 'medium':
		break;
		case 'large':
		break;
	}
	return $fecha;
}
function todate($date){
	$array=explode('/',$date);
	if(count($array)>2){
		list($day,$month,$year)=$array;
		return mktime(0,0,0,$month,$day,$year);
	}else{
		return false;
	}
}
function message($type){
	global $Lang;
	if(isset($Lang[$type])){
		echo json_encode(array('Message'=>$Lang[$type]));
	}
}
function AjaxLogin(){
	firstLogin('message');
}
function isUser(){
	global $user;
	if($user->Exists()){
		return true;
	}else{
		return false;
	}
}
function user($type){
	global $user;
	$data=$user->data();
	if(isset($data[$type])){
		return $data[$type];
	}
}
function importPlugin($plugin,$type,$file){
	global $configuration;
	if(PluginModel::pluginExists($plugin)){
		$plug=$configuration->getItem('PluginPath').DS.$plugin;
		if(file_exists($plug)){
			$model=$plug.DS.$type;
			if(file_exists($model)){
				if(file_exists($model.DS.$file.'.php')){
					include($model.DS.$file.'.php');
				}
			}
		}
	}
}
function MantoLoad(){
	global $configuration;
	if($configuration->getItem('MantoBoolean')){
		if(file_exists($configuration->getItem('MantoFile'))){
			include($configuration->getItem('MantoFile'));
			exit();
		}
	}
}
?>