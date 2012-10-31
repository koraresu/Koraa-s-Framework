<?php

    class sessiones{
        var $session=false;
        Public function sessiones($session=false){
            if($session){
                $this->session=true;
                session_start();
            }
        }
        
        Public function set($name,$value,$expire=''){
            if($this->session){
                $this->set_session($name, $value);
            }else{
                $this->set_cookie($name, $value, $expire);
            }
        }
        Public function destroy($name){
            if($this->session){
                $this->destroy_session($name);
            }else{
                $this->destroy_cookie($name);
            }
        }
        Public function get($name){
            if($this->session){
                $return = $this->get_session($name);
            }else{
                $return = $this->get_cookie($name);
            }
            return $return;
        }
        
        Private function set_cookie($name,$value,$expire){
            if(!isset($_COOKIE[$name])){
                setcookie($name, $value);
            }
        }
        Private function destroy_cookie($name){

            if(isset($_COOKIE[$name])){
                setcookie($name, '', time()-3600);
            }
        }
        Private function get_cookie($name){
            if(isset($_COOKIE[$name])){
                return $_COOKIE[$name];
            }else{
                return false;
            }
        }
        
        Private function set_session($name, $value){
            $_SESSION[$name]=$value;
        }
        Private function destroy_session($name){
            unset($_SESSION[$name]);  
        }
        Private function get_session($name){
            return $_SESSION[$name];
        }
    }
/**
* User
*
* Manejo de usuarios
*
* Administra a los Usuarios y las Conexiones de estos
* Existen funciones para crear contraseñas aleatorias y mas
*
* @author Juan Rael Corrales Arellano
* @author http://labs.contactopv.com
*
* @package Izanami_Controller_Classes
*/
class user{
    /**
    * Esta funcion crea la conexion y en todo caso la Cookie o Session dependiendo de que se haya declarado
    *
    * @return int Regresa 1 si es Correcto, 2 Si el usuario no existe y 0 si hay problemas con los datos
    * @param string $uname es el nombre de usuario
    * @param string $password es la contraseña del usuario
    * @param boolean $remember esta opcion esta ahi por si se desea que se recuerde la conexion para futuros dias
    *
    */
    function login($uname, $password, $remember = false) {
        global $CFG,$db;
            if(!empty($uname) && !empty($password)){
                $db->variable('login',$uname);
                $db->variable('password',$password);
                $query=$db->query('usuario','login="{login}" AND password="{password}"');
                if(count($query)>0){
                    $cookie = new sessiones;
                    if($remember){
                        $rem=time()+2592000;
                    }else{
                        $rem=0;
                    }
                    $cookie->set('user',$uname,$rem);
                    return 1;// OK
                }else{
                    return 2;// Incorrecto
                }
            }else{
                return 0; //Vacio
            }
    }
    function logout() {
        $cookie = getParameter('user', 'cookie');
        if (!empty($cookie)) {
            $cookies = new sessiones;
                $cookies->destroy('user');
            $new=getParameter('user', 'cookie');
            if(!empty($new)){
                return false;
            }
            return true;
        } else {
            return false;
        }
    }
    /**
        * Verifica si el usuario existen en la base de datos
        *
        * @return boolean true En caso de que el usuarios exista
        * @param mixed $user Nombre de usuario o ID del usuario, en caso de no existir, se toma al usuario conectado.
        *
        */
    function Exists($user=''){
        if(empty($user)){
            $ses=new sessiones();
            $user=$ses->get('user');
        }
        $data=$this->data($user);
        if($data){
            return true;
        }else{
            return false;
        }
    }
       /**
        * Regresa los datos de un usuario
        *
        * @return array Datos del usuario
        * @param mixed $user nombre de usuario o id del usuario para obtener los datos, en caso de no existir toma al usuario que esta conectado.
        *
        */
    function data($user='') {
        global $db;
        
        if (empty($user)) {
            $u = getParameter('user', 'cookie');
            $user = $u;
        }
        if (is_numeric($user)) {
            $where = "id=$user";
        } else {
            $where = "login='$user'";
        }
        $us = $db->query('usuario', $where);
        

        if ($us) {
            $user=$us[0];
            unset($user['password']);
            return $user;
        } else {
            return false;
        }
    }
}

class Login{
    private $type;
    private $action;
    function template($template){
        $this->type=$template;
    }
    function action($url){
        $this->action=$url;
    }
    function load(){
        global $configuration;
            $user=new user;
            if(!$user->Exists()){
                $view = new view($this->type);
                $header = new header();
                    $header->style('login');
                $view->addparameter('action',$this->action);
                $view->addparameter('header',$header->cargar());
                $view->cargar();
                exit();        
            }
    }
}
?>