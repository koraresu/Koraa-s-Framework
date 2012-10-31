<?php
	include(FPath.'bootstrap.php');
	$CPath=$configuration->getItem('ControlPath').DS;
	$MPath=$configuration->getItem('ModelPath').DS;
	include($CPath.'functions.php');
	include($CPath.'control.php');
	include($MPath.'model.php');
	include($CPath.'izanamiView.php');
	include($CPath.'navigation.php');
	include($CPath.'html.php');
	include($CPath.'view.php');
	include($CPath.'user.php');
	include($CPath.'plugin.php');
	include($CPath.'ajax.php');
	include($CPath.'report.php');
	include($CPath.'log.php');
	include($MPath.'Langs'.DS.$configuration->getItem('lang').DS.'system.php');
	is_database();
	$db=new database();
	$db->conn();
	$control=new control();
	$user = new user();
?>