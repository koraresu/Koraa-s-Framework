<?php
/**
* Clase Ajax
*
* Manejo de Carga de Metodos en Ajax
*
* Maneja la carga de los metodos
* en los archivos ajax, que sirven para
* la entrega de informacion
*
* @author Juan Rael Corrales Arellano
* @author http://labs.contactopv.com
*
*/
class ajax {
	/**
    * Este metodo sirve para recibir como va a reaccionar el archivo Ajax
    * que se emplean en los proyectos. Este metodo responde llamando a la
    * funcion que se esta empleando.
    *
    * @param string $condition esto recibe la condicion
    */
	Public function select($condition){
		if(method_exists($this, $condition)){
			//Aqui se llama al metodo de la llamada para activar la accion
			$this->$condition();	
		}
	}
}
?>