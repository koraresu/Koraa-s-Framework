<?php
class izanami_view{
	private $params=array();
	function __construct(){
		
	}
	public function assign($name,$value){
		$this->params[$name]=$value;
	}
	public function fetch($archivo){
		if(file_exists($archivo)){
			$new_text = file_get_contents($archivo);	
		}else{
			$new_text = '';
		}
		
		
		foreach($this->params as $name=>$value){
			if(is_array($value)){
				$new_text=$this->replace_array($name,$value,$new_text);
			}else{
				$new_text=str_replace('{$'.$name.'}',$value,$new_text);
			}
			
		}
		$new_text=$this->eliminar_params($new_text);
		return $new_text;
	}
	public function replace_array($name,$array,$texto){
		foreach($array as $n_v => $v_v){
			$texto=str_replace('{$'.$name.'.'.$n_v.'}',$v_v,$texto);
		}
		return $texto;
	}
	private function eliminar_params($text){
		
		return $text;
	}
}
?>