<?php
	define('ReportPath',$CPath.'Report'.DS);
	include(ReportPath.'fpdf.php');
	include(ReportPath.'fpdi.php');

	class Imprimir extends FPDI{

		var $javascript; 
    	var $n_js; 

		function Imprimir($orientation='P', $unit='mm', $size='A4'){
			parent::FPDI($orientation,$unit,$size);
		}
		function Recta($a,$b,$y,$decorativo=true){
			if($decorativo){
				$this->Line($a,$y,$a,($y-1));
				$this->Line($b,$y,$b,($y-1));
			}
			$this->Line($a,$y,$b,$y);
		}
		function input($horizontal,$vertical,$text,$value,$extraa=0,$extrab=0){
			$this->Text(($horizontal),($vertical),utf8_decode($text));
				$this->Recta(($horizontal+15.5+$extraa),($horizontal+29+$extrab),($vertical+0.5));
				$this->Text(($horizontal+16.5+$extraa),($vertical),utf8_decode($value));
		}
		function lista($horizontal,$vertical,$text,$value,$extraa=0,$extrab=0){
			$this->Text(($horizontal),($vertical),utf8_decode($text));
			$vertical+=3.5;
			foreach($value as $i=>$v){
				$this->Text(($horizontal+2),($vertical),utf8_decode($v));
				$vertical+=3.5;
			}
		}
		function fila($horizontal,$vertical,$value,$text='',$extra=0,$extrab=0){
			if(!empty($text)){
				$this->Text(($horizontal),($vertical),utf8_decode($text));	
			}
			if(is_array($value)){
				$texto=implode(',',$value);
			}else{
				$texto=$value;
			}
			$this->Text(($horizontal),($vertical),utf8_decode($texto));
		}
		function Colum($x,$y,$tam,$text){
			$total=strlen($text);
			for($int=0;$int<=$total;$int+=$tam){
				$get=substr($text,$int,$int+$tam);
				$this->Text($x,$y,$get);
				$y=$y+4;
			}
		}
		function IncludeJS($script) { 
			$this->javascript=$script; 
		}
		function _putjavascript() { 
			$this->_newobj(); 
			$this->n_js=$this->n; 
			$this->_out('<<'); 
			$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R ]'); 
			$this->_out('>>'); 
			$this->_out('endobj'); 
			$this->_newobj(); 
			$this->_out('<<'); 
			$this->_out('/S /JavaScript'); 
			$this->_out('/JS '.$this->_textstring($this->javascript)); 
			$this->_out('>>'); 
			$this->_out('endobj'); 
		}
		function _putresources() {
			parent::_putresources();
			if (!empty($this->javascript)) {
				$this->_putjavascript();
			}
		}
		function _putcatalog() {
			parent::_putcatalog();
			if (isset($this->javascript)) {
				$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
			}
		}
		function AutoPrint($dialog=false){
			$param=($dialog ? 'true' : 'false');
			$script="print($param);";
			$this->IncludeJS($script);
		}
	}
?>