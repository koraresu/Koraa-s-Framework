<?php
	class PluginModel{
		private static $modules=array();
		public static function add($name){
			self::$modules[$name]['name'] = $name;
			self::$modules[$name]['modules'] = array();
		}
		public static function getPlugin($name){
			return self::$modules[$name]['name'];
		}
		public static function pluginExists($name){
			if(isset(self::$modules[$name]['name'])){
				return true;
			}else{
				return false;
			}
    	}
    	public static function AddModule($plugin,$module,$link){
    		if(isset(self::$modules[$plugin])){
    			self::$modules[$plugin]['modules'][$link]=array('Name'=>$module);
                self::$modules[$plugin]['modules'][$link]['type']='InPermision';
    		}
    	}
        public static function DefaultModule($plugin,$module){
            if(isset(self::$modules[$plugin])){
                self::$modules[$plugin]['default']=$module;
            }
        }
    	public static function GetLinkModule($plugin,$link){
            if(empty($link)){
                if(isset(self::$modules[$plugin]['default'])){
                    return self::$modules[$plugin]['default'];
                }else{
                    return false;
                }
            }else{
        		if(isset(self::$modules[$plugin]['modules'][$link])){
        			return self::$modules[$plugin]['modules'][$link]['Name'];
        		}else{
        			return false;
        		}
            }
    	}
    	public static function ModuleExists($plugin,$module){
    		if(isset(self::$modules[$plugin])){
    			if(isset(self::$modules[$plugin]['modules'][$module])){
    				return true;
    			}else{
    				return false;
    			}
    		}
    	}
    	public static function getAll(){
    		return self::$modules;
    	}
	}
?>