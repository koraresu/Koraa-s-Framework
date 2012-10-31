<?php
class menus{
   private static $menus=array();
    public static function addMenu($id,$name){
        self::$menus[$id]=array('name'=>$name,'items'=>array());
    }
    public static function addItem($menu,$name,$data){
        $item=array(
            'name'=>$name,
            'data'=>$data
        );
        self::$menus[$menu]['items'][]=$item;
    }
    private static function getID($name){
        foreach(self::$menus as $id=>$menu){
            if($name == $menu['name']){
                return $id;
            }
        }
    }
    public static function getMenus($name){
        $id=self::getID($name);
        return self::$menus[$id];
    }
    public static function DB2Text(){
        global $db;
        $menu = $db->query('menu');
        foreach($menu as $m){
            menus::addMenu($m['menu_id'],$m['nombre']);
            $db->variable('itemid',$m['menu_id']);
            $menuItem = $db->query('menu_item','menu = {itemid}');
            foreach($menuItem as $item){
                menus::addItem($item['menu'],$item['item_name'],$item['item_data']);
            }
        }
    }

}
?>