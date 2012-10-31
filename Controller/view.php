<?php
class view {

    private $object;
    private $archivo;

    private $FoView;
    public function view($archivo, $theme='', $folder='', $format='') {
        $this->object= new izanami_view();
        $this->FoView = $folder;
        $this->archivo = $this->get_archivo($archivo, $theme, $folder, $format);
    }
    public function addparameter($name, $value='') {
        $this->object->assign($name,$value);
    }
    public function menu($menuName,$Classes=array(),$menuTag='menu'){
        $getMenu=get_menu($menuName,$Classes,$this->FoView);
        $this->addparameter($menuTag,$getMenu);
    }
    public function showFile(){
        echo $this->archivo;
    }
    public function cargar($return=false) {
        $this->tags();
        $back=$this->object->fetch($this->archivo);
        if($return){
            return $back;
        }else{
            echo $back;
        }
    }
    private function ViewType($call,$view=''){
        global $configuration;
        
        switch($call){
            case 'basic':
                $return = $configuration->getItem('ViewPath').DS.$configuration->getItem('Template');
            break;
            case 'plugin':
                $return = $configuration->getItem('PluginPath').DS.$view.DS.$configuration->getItem('ViewDir');
            break;
        }
        return $return;
    }
    private function get_archivo($archivo, $theme='', $folder='', $format='') {
        global $CFG;
        $view = $CFG['dirview'];
        switch (true) {
            case ($theme == 'vista'):
                $dir = $this->ViewType('vista',$folder);
            break;
            case ($theme == 'plugin'):
                $dir = $this->ViewType('plugin',$folder);
            break;
            default:
                $dir = $this->ViewType('basic');
                break;
        }
        $file = (empty($format)) ? $archivo . '.html' : $archivo . '.' . $format;
        $include = $dir .DS. $file;
        if (file_exists($include)) {

            return $include;
        } else {
            return false;
        }
    }
    private function archivo($nombre, $folder) {
        $array = files($folder);
        foreach ($array as $files) {
            if ($nombre == $files[0]) {
                $formato=(isset($files[1]))?$files[1]:'html';
                return $files[0] . '.' . $formato;
            }
        }
        return 0;
    }
    private function tags(){
        global $configuration;
        foreach($configuration->getItems() as $lol => $tumama){
            $this->addparameter($lol,$tumama);
        }
    }
}

?>