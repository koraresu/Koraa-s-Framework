<?php
	class configuration{
		public $Config;
		public function addItem($name,$value){
			$this->Config[$name]=$value;
		}
		public function getItems(){
			return $this->Config;
		}
		public function getItem($name){
			if(isset($this->Config[$name])){
				return $this->Config[$name];
			}else{
				return false;
			}
			
		}
	}
	$configuration=new configuration;
	class Config{
		public static function SetUrl($url){
			global $configuration;
			$configuration->addItem('Url',$url);
		}
		public static function SetDatabase($type,$host,$user,$password,$name){
			global $configuration;
			$configuration->addItem('dbtype',$type);
			$configuration->addItem('dbhost',$host);
			$configuration->addItem('dbuser',$user);
			$configuration->addItem('dbpass',$password);
			$configuration->addItem('dbname',$name);
		}
		public static function Project($name,$version){
			global $configuration;
			$configuration->addItem('ProjectName',$name);
			$configuration->addItem('ProjectVersion',$version);
		}
		public static function ProjectType($type){
			global $configuration;
			$configuration->addItem('ProjectType',$type);
		}
		public static function Identify($type,$user,$file){
			global $configuration;

			$configuration->addItem('UserType',$type);
			$configuration->addItem('UserID',$user);
			$configuration->addItem('UserFile',$file);
		}
		public static function SetPluginDir($name){
			global $configuration;
			$configuration->addItem('PluginDir',$name);
		}
		public static function SetPluginPath($name){
			global $configuration;
			$configuration->addItem('PluginPath',$name);
		}
		public static function SetControlPath($control){
			global $configuration;
			$configuration->addItem('ControlPath',$control);
		}
		public static function SetModelPath($model){
			global $configuration;
			$configuration->addItem('ModelPath',$model);
		}
		public static function SetViewPath($view){
			global $configuration;
			$configuration->addItem('ViewPath',$view);
		}
		public static function SetScriptDir($dir){
			global $configuration;
			$configuration->addItem('ScriptDir',$dir);
		}
		public static function SetStyleDir($dir){
			global $configuration;
			$configuration->addItem('StyleDir',$dir);
		}
		public static function SetImgDir($dir){
			global $configuration;
			$configuration->addItem('ImgDir',$dir);
		}
		public static function SetRedir($redirection){
			global $configuration;
			$configuration->addItem('redir',$redirection);
		}
		public static function SetVersion($version){
			global $configuration;
			$configuration->addItem('version',$version);
		}
		public static function SetViewTemplate($template){
			global $configuration;
			$configuration->addItem('Template',$template);
		}
		public static function SetViewDir($dir){
			global $configuration;
			$configuration->addItem('ViewDir',$dir);
		}
		public static function SetModuleRedir($redir){
			global $configuration;
			$configuration->addItem('ModuleRedir',$redir);
		}
		public static function SetModulesDir($dir){
			global $configuration;
			$configuration->addItem('ModuleDir',$dir);
		}
		public static function SetLanguage($value){
			global $configuration;
			$configuration->addItem('lang',$value);
		}
		public static function SetManto($file){
			global $configuration;
			$configuration->addItem('MantoBoolean',true);
			$configuration->addItem('MantoFile',$file);
		}
	}
?>