<?php
/**
* Clase Database
*
* Es el ORM que se usa en el framework para Mysql
*
* Esta clase es la capa de conexion entre el Framework y
* la base de datos. Esta clase te da todos los metodos
* necesarios para crear, modificar, eliminar y consultar
* en la base de datos.
*
* @author Juan Rael Corrales Arellano
* @author http://labs.contactopv.com
*
* @package Izanami_Controller_Classes
*/
class database {
    var $host;		// Direccion del Servidor
    var $user;		// Usuario de Base de Datos
    var $pass;		// Password de Base de Datos
    var $db;		// Dase de Datos
    var $db_link;	// Variable donde se Guarda la conexion para el Manejo de la clase
    var $conn = false;
    var $persistant = false;
    public $error = false;
    var $variable=array();
	/**
    * 
    */
	public function setpersistance(){
		$this->persistant=true;
	}
	/**
    * Este metodo es el encargado de crear la conexion, y recoger los datos
    * para tal proposito
    */
    public function conn(){
		global $configuration;

        $this->host = $configuration->getItem('dbhost');
        $this->user = $configuration->getItem('dbuser');
        $this->pass = $configuration->getItem('dbpass');
        $this->db = $configuration->getItem('dbname');

        
        if ($this->persistant)
            $this->db_link = mysql_pconnect($this->host, $this->user, $this->pass, true);
        else 
            $this->db_link = mysql_connect($this->host, $this->user, $this->pass, true);

        if (!$this->db_link) {
            if ($this->error) {
                $this->error($type=1);
            }
            return false;
        }else {
			if (empty($this->db)) {
				if ($this->error) {
					$this->error($type=2);
				}
			}else {
				$db = mysql_select_db($this->db, $this->db_link);
				if (!$db) {
					if ($this->error) {
						$this->error($type=2);
					}
					return false;
				}
				$this -> conn = true;
			}
			return $this->db_link;
        }
    }
    /**
    * Este metodo cierra la conexion de la Base de datos
    */
    function close() {
        if ($this -> conn){
            if ($this->persistant) {
                $this -> conn = false;
            }else {
                mysql_close($this->db_link);
                $this -> conn = false;
            }
        }else {
			if ($this->error) {
				return $this->error($type=4);
            }
        }
    }
    /**
    * En caso de algun error en la base de datos, este es el metodo
    * que responde ante ellos
    *
    * @param int $type el tipo de error, y muestra el mensaje
    */
    public function error($type=''){
        if (empty($type)) {
            return false;
        }
        else {
			switch($type){
				case 1:
					_e('DBnotConnect');
				break;
				case 2:
					_e('MysqlError',mysql_error());
				break;
				case 3:
					_e('MysqlProcessStoped');
				break;
				default:
					_e('MysqlENoConnect');
				break;
			}
        }
    }
    /**
    * Es el metodo permite realizar las consultas en lenguaje sql
    *
    * @param string $sql consulta sql.
    */
    public function sql($sql,$unserialize=array()){
    	$conexion=$this->db_link;
		$data=array();
		$consulta = @mysql_query($sql, $conexion);
		if(!$consulta){
			return false;
		}else{
			while ($fila = mysql_fetch_assoc($consulta)) {
                if(count($unserialize)>0){
                    foreach($unserialize as $us){
                        if(isset($fila[$us])){
                            $fila[$us]=unserialize($fila[$us]);
                        }
                    }
                }
                $data[]=$fila;
			}
			return $data;
		}
    }
    /**
    * Este metodo es interior, y es el encargado de el manejo de filtros
    * para la correcta utilizacion de la seguridad del ORM
    *
    * @param string $value el valor enviado por los metodos publicos
    */
    private function filter($value){
    	if(!empty($value)){
    		$value=$this->SetVar($value);
    		return 'where '.$value;	
    	}else{
    		return '';
    	}
    }
    /**
    * Este Remplaza las variables en los filtros.
    *
    * @param string $data variable que se remplazara
    */
    private function SetVar($data){
    	foreach($this->variable as $var => $val){
    		$data=str_replace('{'.$var.'}',$val,$data);	
    	}
    	return $data;
    }
    /**
    * Este metodo es el encargado de manejar el orden de el resultado
    * de una consulta
    *
    * @param array $val es un arreglo con los 2 datos (1) Columna de orden, y (2)Metodo(ASC,DESC)
    */
    private function order($val){
    	if(!empty($val)){
    		if(is_array($val)){
	    		$table=$val['table'];
	    		$data = $val['orden'];
	    		$order = ' ORDER BY `'.$table.'` '.$data;
    		}else{
    			$order = ' '.$val;
    		}

    		return $order;
    	}else{
    		return '';
    	}
    }
    /**
    * Este es el metodo interno que maneja los limites de la consulta
    * y el alcance de estas.
    *
    * @param array $limit valores que se necesitan para limitar
    */
    private function limit($limit){
    	$limited = '';
    	if(!empty($limit['inicio']) || !empty($limit['numero'])){
			if(count($limit)>1){
				$limited=' limit '.$limit['inicio'].','.$limit['numero'];
			}else if(count($limit)>0){
				$limited=' limit '.$limit['inicio'];
			}
		}
		return $limited;
    }
    /**
    * Este metodo es el ecargargo de enviar variables a los filtros
    * y asi poder sanitizarlos.
    *
    * @param string $variable variable para distinguir el dato dentro de la consulta
    * @param string $value dato que se envia para ser sanitizado
    */
    public function variable($variable,$value){
    	$conexion = $this->db_link;
    	$return=htmlspecialchars($value,ENT_QUOTES);
    	$this->variable[$variable]=$return;
    }
    /**
    * Este metodo es el encargado de crear el nombre de la tabla en el INNER JOIN
    *
    * @param string $condition esto recibe la condicion
    * @return string Regresa el Nombre de la tabla en la union formado "tabla t".
    */
    private function unionTab($number,$tabassoc){
    	if($number=='1' || $number=='2'){
    		return $tabassoc['table'].' '.$tabassoc['assoc'];
    	}else{
    		return '';
    	}
    }
    /**
    * Este metodo sirve para recibir como va a reaccionar el archivo Ajax
    * que se emplean en los proyectos. Este metodo responde llamando a la
    * funcion que se esta empleando.
    *
    * @param string $condition esto recibe la condicion
    * @return string Regresa la Comparacion de las tablas en la consulta INNER JOIN "u.id=a.b"
    */
    private function ConUnion($UnionCols){
    	$add = array();
    	foreach($UnionCols as $assoc => $value){
    		$add[] = $assoc.'.'.$value;
    	}
    	return implode('=',$add);
    }
    /**
    * Esta funcion es la encargada de crear un Consulta JOIN
    *
    * @param string $condition esto recibe la condicion
    */
    public function union($tables,$UnionCols,$show='*',$filter='',$order='',$limit='',$mostrar=false){

    	if(is_array($tables) && is_array($UnionCols)){
    		$sql = '';
    		// Select * from usuario u inner join casas c on u.id=c.user;
    		$x = 0;
    		$tabl=array();
    		foreach($tables as $assoc => $value){
    			$tabl[]=array(
    				'table'=>$value,
    				'assoc'=>$assoc
    			);
    		}
    		$firstTable = $this->unionTab('1',$tabl[0]);
    		$secondTable = $this->uniontab('2',$tabl[1]);

    		$sql = 'Select '.$show.' from '.$firstTable;
    		$sql.=' INNER JOIN '.$secondTable.' ON '.$this->ConUnion($UnionCols);
    	}

    	$filter = $this->filter($filter);
		$orden = $this->order($order);
		$limit = $this->limit($limit);
		$sql.=' '.$filter.$order.' '.$limit;
		if($mostrar){
			echo $sql;
		}
    	$consulta = $this->sql($sql);
    	return $consulta;
    }
    /**
    * Este metodo es el encargado de realizar las consultas basicas
    *
    * @param string $table se da el nombre de la tabla
    * @param string $filter es la condicion con la que se filtrara la consulta
    * @param array $order recibe el metodo de ordenamiento de los resultados de la consulta
    * @param boolean $mostrar se usa en produccion para ver las consultas sql realizadas
    * @return array regresa un arreglo multidimensional con los datos de la tabla
    */
    public function query($table,$filter='',$order='',$limit='',$mostrar='',$unserialize=array()){
		$conexion = $this->db_link;

		$sql = 'Select * from '.$table;
		$filter = $this->filter($filter);
		$orden = $this->order($order);
		$limit = $this->limit($limit);
		$sql.=' '.$filter.$orden.' '.$limit;
		
		if(!empty($mostrar)){
			echo $sql;
		}
		$consulta = $this->sql($sql,$unserialize);
		return $consulta;
	}
	/**
    * Obtienes el registro del valor mas grande de una columna.
    *
    * @param string $table se da el nombre de la tabla
    * @param string $id es la columna en la que buscaras el mayor registro
    * @param string $filter es la condicion con la que se filtrara la consulta
    * @param boolean $mostrar se usa en produccion para ver las consultas sql realizadas
    * @return array regresa un arreglo multidimensional con los datos de la tabla
    */
	public function last($table,$id,$filter='',$mostrar=''){
		$sql = 'Select max('.$id.')as ultimo from '.$table;
		$filter = $this->filter($filter);
		$sql.=' '.$filter;
		if(!empty($mostrar)){
			echo $sql;
		}
		$consulta = $this->sql($sql);
		
		if(isset($consulta[0])){
			$data=$consulta[0];	
			$this->variable('id',$data['ultimo']);
			$query=$this->query($table,$id.'={id}');
		}
		return $query;
	}
	/**
    * Insertar un registro en una tabla
    *
    * @param string $table se da el nombre de la tabla
    * @param array $array Es el arreglo de datos que se insertaran
    * @param boolean $mostrar se usa en produccion para ver las consultas sql realizadas
    * @return int Regresa el identificador del registro que se ha creado
    */
	public function insert($table,$array,$mostrar=false){
		$conexion=$this->db_link;
		$rows=array();
		$datas=array();
		$error=array();
		foreach($array as $key=>$value){
			$rows[]="`".$key."`";
			$datas[]=(is_string($value) || empty($value))?"'".$value."'":$value;
		}
		$row=implode(',',$rows);
		$data=implode(',',$datas);
		$sql="INSERT INTO `$table` ($row) VALUES ($data);";
		if($mostrar){
			echo $sql.'<br>';
		}
		mysql_query($sql, $conexion) or $error[]=$sql;
		return mysql_insert_id($conexion);
	}
    public function status($table){
        $sql='SHOW TABLE STATUS  where Name LIKE "'.$table.'"';
        $query=$this->sql($sql);
        if(isset($query[0])){
            return $query[0];
        }else{
            return false;
        }
    }
	/**
    * Se modifica el registro de cierta tabla.
    *
    * @param string $table se da el nombre de la tabla
    * @param array $array Es el arreglo de datos que se insertaran
    * @param boolean $mostrar se usa en produccion para ver las consultas sql realizadas
    * @return int Regresa el identificador del registro que se ha creado
    */
	public function update($table,$array,$id){
		$conexion=$this->db_link;
		$filter=$id['row'].'={value}';
		$this->variable('value',$id['value']);
		$query=$this->query($table,$filter,'','1');
		$q=$query[0];
		$return=true;
		foreach($array as $key=>$value){
			if($q[$key]!=$value){
				$ret=$this->upquery($table,$key,$value,$id['row'],$id['value']);
				if(!$ret){$return=false;}
			}
		}
		return $return;
	}
	/**
    * Este metodo crea el sql necesario para crear las consultas para modificar
    *
    * @param string $table se da el nombre de la tabla
    * @param string $campo Columna que se modificara
    * @param string $value El nuevo valor que se le dara al campo
    * @param string $campoid El nombre de la columna con identificador unico(llave primaria)
    * @param int $id  El valor de la llave primaria del registro que se modificara
    * @return mixed Resultados de la conexion realizada
    */
	private function upquery($table,$campo,$value,$campoid,$id){
		$conexion=$this->db_link;
        if(is_string($value)){
            $value='"'.$value.'"';
        }
		$sql='UPDATE  `'.$table.'` SET  `'.$campo.'` =  '.$value.' where '.$campoid.'='.$id.' limit 1;';
		$con=@mysql_query($sql, $conexion);
		return $con;
	}
	/**
    * Este metodo sirve para eliminar un registro en la tabla
    *
    * @param string $table se da el nombre de la tabla
    * @param string $col El nombre de la columna con identificador unico(llave primaria)
    * @param array $id El valor de la llave primaria del registro que se modificara
    */
	public function delete($table,$col,$id){
		$conexion=$this->db_link;
		$where = $col.'='.$id;
		$sql='DELETE FROM '.$table.' where '.$where.' LIMIT 1;';

		mysql_query($sql, $conexion) or $error[]=$sql;
	}
    public function total($table){
        $sql='SELECT count(id) as total from '.$table.';';
        $query=$this->sql($sql);
        return $query[0]['total'];
    }
    public function CheckError(){
        if(!empty($this->error)){
            echo $this->error;
        }else{
            echo 'No hay error';
        }
    }
}
?>