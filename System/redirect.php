<?php
	class redirects{
		private static $redir;
		public static function add($redir,$plugin){
			self::$redir[$redir] = $plugin;
	    }
	    public static function get($r){
	    	if(isset(self::$redir[$r])){
	    		return self::$redir[$r];
	    	}
	    }
	    public static function exists($redir){
	        list($tag,$red) = explode(':',$redir);
	        if(isset(self::$redir[$red])){
	            return true;
	        }else{
	            return false;
	        }
	    }
	}
?>