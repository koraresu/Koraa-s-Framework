<?php
	include(FPath.'System/config.php');
	include(FPath.'System/plugin.php');
	include(FPath.'System/menu.php');
	include(FPath.'System/permision.php');
	include(FPath.'System/redirect.php');
	include(FPath.'config.php');
	function is_database(){
		global $configuration;
		$model=$configuration->getItem('ModelPath');
		$type=$configuration->getItem('dbtype');
		$host=$configuration->getItem('dbhost');
		if(!empty($type)){
			if(!empty($host)){
				include($model.DS.'database'.DS.$type.DS.'database.php');
			}
		}
	}
	include('config.php');
	
?>