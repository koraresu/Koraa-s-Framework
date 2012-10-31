<?php
	class html{

	}
	class header{
		private $head=array();
		private $style;
		private $script;
		public function __construct(){
			$this->style=new Style();
			$this->style->ReturnForm();
			$this->script=new Script();
			$this->script->ReturnForm();
		}
		public function meta($type,$value){
			switch($type){
				case'charset':
				$return='<meta charset="'.$value.'">';
				break;
				case'X-UA-Compatible':
				$return='<meta http-equiv="'.$type.'" content="'.$value.'">';
				break;
				case'description':
				$return='<meta name="description" content="'.$value.'">';
				break;
				case'author':
				$return='<meta name="author" content="'.$value.'">';
				break;
				case'viewport':
				$return='<meta name="viewport" cotent="'.$value.'">';
				break;
			}
			$this->head[]=$return;
		}
		public function title($title){
			$return='<title>'.$title.'</title>';
			$this->head[]=$return;
		}
		public function link($type,$value){
			$return='';
			switch($type){
				case 'icon':
					$return='<link rel="shortcut icon" href="'.$value.'">';
				break;
				case 'apple-icon':
					$return='<link rel="apple-touch-icon" href="/apple-touch-icon.png">';
				break;
			}
			$this->head[]=$return;
		}
		public function style($file,$type='default'){
			global $configuration;
			$this->head[]=$this->style->load($file,$type);
		}
		public function script($file,$type='default'){
			$this->head[]=$this->script->load($file,$type);
		}
		public function Ui($file='jquery.tools.min'){
			global $configuration;
			$framework=$configuration->getItem('Url').$configuration->getItem('ViewDir').DSu.$configuration->getItem('Template').DSu.'framework'.DSu;
			$this->head[]=$this->script->load($framework.'script'.DSu.'jquery.tools.min.js','url');
			$this->head[]=$this->style->load($framework.'style'.DSu.'style.css','url');
		}
		public function cargar(){
			$return='';
			foreach($this->head as $head){
				$return.=$head;
			}
			return $return;
		}
	}
	class Style{
		private $styles=array();
		private $form=true;
		public function load($file,$type='default',$value=''){
			global $configuration;
			$url = $configuration->getItem('Url');
			switch($type){
				case'plugin':
					$url.=$configuration->getItem('PluginDir').DSu.$value.DSu.$configuration->getItem('ViewDir').DSu.$configuration->getItem('StyleDir').DSu.$file.'.css';
				break;
				case'default':
					$url.=$configuration->getItem('ViewDir').DSu.$configuration->getItem('Template').DSu.$configuration->getItem('StyleDir').DSu.$file.'.css';
				break;
				case'url':
					$url=$file;
				break;
			}
			if($this->form){
				$this->styles[]='<link rel="stylesheet" href="'.$url.'">';
			}else{
				return '<link rel="stylesheet" href="'.$url.'">';
			}
		}
		public function ReturnForm(){
			$this->form=false;
		}
		public function cargar(){
			return implode('',$this->styles);
		}
	}
	class Script{
		private $scripts=array();
		private $form=true;
		public function load($file,$type='default',$value=''){
			global $configuration;
			$url = $configuration->getItem('Url');
			switch($type){
				case'plugin':
					$url.=$configuration->getItem('PluginDir').DSu.$value.DSu.$configuration->getItem('ViewDir').DSu.$configuration->getItem('ScriptDir').DSu.$file.'.js';
				break;
				case'default':
					$url.=$configuration->getItem('ViewDir').DSu.$configuration->getItem('Template').DSu.$configuration->getItem('ScriptDir').DSu.$file.'.js';
				break;
				case'url':
					$url=$file;
				break;
			}
			if($this->form){
				$this->styles[]='<script src="'.$url.'"></script>';
			}else{
				return '<script src="'.$url.'"></script>';
			}
		}
		public function ReturnForm(){
			$this->form=false;
		}
		public function cargar(){
			return implode('',$this->styles);
		}
	}
?>