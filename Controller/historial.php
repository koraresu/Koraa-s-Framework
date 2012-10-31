<?php
	class history{

	}
	$history=new history;
		$history->insert(serialize(array('antes'=>$Orden,'despues'=>$newMod)));//Historial 

?>