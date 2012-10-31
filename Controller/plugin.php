<?php
	class plugin{
		private $plugin;
		public function __construct($plugin){
			$this->plugin=$plugin;
		}
		public function loadIndex(){
			global $configuration,$control;
			$path=$configuration->getItem('PluginPath');
			$file=$path.DS.$this->plugin.DS.'index.php';
			return $control->import($file);
		}
	}
?>