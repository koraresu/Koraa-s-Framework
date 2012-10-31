<?php
class navigation{
	private $menu;
	public function __construct($menu){
		$this->menu=$menu;
	}
	public function cargar(){
		$menu=menus::getMenus($this->menu);
		$name=$menu['name'];
		$return='';
		foreach($menu['items'] as $item){
			$return.='<a href="'.$this->link($item['data']).'">'.$item['name'].'</a>';
		}
		return $return;
	}
	private function link($data){
		global $configuration;

		$data = str_replace('{','',$data);
		$data = str_replace('}','',$data);

		$array = explode(':',$data);

		$redir = $array[0];
		if(isset($array[1])){
			$value = $array[1];	
		}else{
			$value='';
		}
		
		switch($redir){	
			case 'inicio':
				return $configuration->getItem('Url');
			break;
			case 'redir':
				if(redirects::exists($data)){
					$dir='?'.$configuration->getItem('redir').'='.$value;
					return $dir;
				}else{
					return '';
				}
			break;
			default:
				return $data;
			break;
		}
	}
}
?>