<?php
/*
$log = new Log('Entrada de Anticipo');
	$log->message('Ho se ingreso a la cuenta {$orden} el valor de {$valor1}, anteriormente era {$valor2}');
	$log->array(array(
		'orden'=>$orden,
		'valor1'=>$anticipo,
		'valor2'=>$anterior
	));
	$log->valorAnterior($anterior);
	|$log->valorNuevo($anticipo);
	| o
	|$log->valorAgregado($anticipo);
	$log->load();
*/
	class log{
		var $message;
		var $array;
		var $valorAnterior;
		var $valor;
		var $type;
		var $name;
		var $id;
		function name($name){
			$this->name=$name;
		}
		function message($message){
			$this->message= $message;
		}
		function vector($array){
			$this->array=serialize($array);
		}
		function valorAnterior($valor){
			$this->valorAnterior=$valor;
		}
		function valorAgregado($valor){
			$this->type='Agregado';
			$this->valor=$valor;
		}
		function valorNuevo($valor){
			$this->type='Nuevo';
			$this->valor=$valor;
		}
		function setID($id){
			$this->id=$id;
		}
		function database(){}
		function extendsarray(){}
		function load(){
			global $db,$user;
			$mensaje=array(
				'mensaje'=>$this->message,
				'nombre'=>$this->name,
				'fecha'=>time(),
				'tipoValor'=>'None',
				'user'=>serialize($user->data()),
				'filter_ID'=>$this->id
			);
				if(!empty($this->array)){
					$array=array_merge($mensaje,array('valores'=>$this->array,'tipoValor'=>$this->type,'valor'=>$this->valor));
				}else{
					$array=$mensaje;
				}
			$array=array_merge($array,$this->extendsarray());
			return $db->insert($this->database(),$array);
		}
		function ReadFilterId($name,$id){
			return $this->Read($name,'',$id);
		}
		function ReadID($name,$id){
			return $this->Read($name,$id);
		}
		function Read($name='',$filterID='',$id=''){
			global $db;
			$db->variable('name',$name);
			$db->variable('filter',$filterID);
			$db->variable('id',$id);
			$filter=array();
			if(!empty($name)){
				$filter[]='nombre="{name}"';	
			}
			if(!empty($filterID)){
				$filter[]='filter_ID={filter}';
			}
			if(!empty($id)){
				$filter[]='ID={id}';
			}
			$query=$db->query('plugin_log',implode(' AND ',$filter),'','',false,array('valores','user'));
			return $query;
		}
	}
	class SystemLog extends log{
		function database(){
			return 'system_log';
		}
		function extendsarray(){
			return array('plugins');
		}
	}
	class PluginLog extends log{
		private $plugin;
		function __construct($plugin){
			$this->plugin=$plugin;
		}
		function database(){
			return 'plugin_log';
		}
		function extendsarray(){
			return array('plugin'=>$this->plugin);
		}
	}
?>