<?php
	class control{
		public function contenido(){
			$content=new contenido;
			return $content->load();
		}
		public function sidebar(){
			$sidebar=new sidebar;
			return $sidebar->load();
		}
		public function menu(){

		}
		public function mantenimiento($index){
			global $configuration,$control;

			if($configuration->getItem('MantoBoolean')){
				$file=$configuration->getItem('MantoFile');
				if(file_exists($file)){
					include($file);
				}
			}else{
				include($index);
			}
		}
		public function import($file){
			global $configuration;
			ob_start();			
			if(file_exists($file)){
				include($file);
			}
			$contenido = ob_get_contents();
			ob_end_clean();
			return $contenido;
		}
		public function modulePresent($plugin,$module){
			global $configuration;

			$module=PluginModel::GetLinkModule($plugin,getParameter($module));
			
			$path=$configuration->getItem('PluginPath').DS.$plugin.DS.$configuration->getItem('ModuleDir');
			echo $this->import($path.DS.$module.'.php');
		}
	}
	class contenido{
		private $plugin;
		public function load(){
			global $configuration;
			$redir=$configuration->getItem('redir');
			define('_Plugin',redirects::get(getParameter($redir)));
			$this->plugin();
			if(!empty($redir) && isset($_GET[$redir])){
				return $this->redir(getParameter($redir));
			}else{
				return $this->frontend();
			}
		}
		private function plugin(){

			$plugin=new plugin(_Plugin);
			$this->plugin=$plugin;
		}
		private function redir($redir){
			$index=$this->plugin->loadIndex();
			return $index;
		}
		private function frontend(){
			if(file_exists(PPath.'frontend.php')){
				obstart();
				include(PPath.'frontend.php');
				return ob_get_contents();
			}else{
				return false;
			}
		}
	}
	class sidebar{
		public function load(){

		}
	
	}
?>