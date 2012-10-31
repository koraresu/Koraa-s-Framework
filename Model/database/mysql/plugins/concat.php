<?php
	class concat extends database{
		private $tablas=array();
		private $campos=array();
		private $concatenar=array();
		private $where='';
		private $limit='';
		public function Table($tabla,$campos){
				$this->tablas[]=$tabla;
				$this->campos[$tabla]=$campos;
		}
		public function concatenar($campos,$id){
			$this->concatenar[$id]='CONCAT('.implode(',',$campos).')';
		}
		public function lefJoin($joinTable,$palTable){
			$this->join[]=' left join '.$joinTable['tabla'].' on '.$joinTable['tabla'].'.'.$joinTable['campo'].'='.$palTable['tabla'].'.'.$palTable['campo'];
		}
		public function where($where,$variable){
			$where = str_replace('{variable}',$this->clear_variable($variable),$where);
			$this->where='where '.$where;
		}
		public function limit($show,$ini=''){
			if(!empty($ini)){
				$this->limit=' limit '.$ini.','.$show;
			}else{
				$this->limit=' limit '.$show;
			}
		}
		public function arraySpec($array,$div, $format='{val}'){
			$campos=array();
			$default=$format;
			$as=$format.' as {index}';
			foreach($array as $index=>$val){
				if(is_int($index)){$m=$default;}else{$m=$as;}
				$m=str_replace('{index}',$index,$m);
				$m=str_replace('{val}',$val,$m);
				$campos[]=$m;
			}

			return implode(',',$campos);
		}
		public function load(){
			if(!empty($this->tablas)){
				$campos='';
				$x=0;
				$total=count($this->campos);
				foreach($this->campos as $i=>$v){
					$campos.=$this->arraySpec($v,',',$i.'.{val}');
					if($x<$total){
						$campos.=',';
					}
					$x++;
				}
				$campos.=$this->arraySpec($this->concatenar,',','{val}');
				$sql = 'SELECT '.$campos.' FROM '.$this->tablas[0];
				foreach($this->join as $tabla){
					$sql.=' '.$tabla.' ';
				}
			}

			return $sql.$this->where.' '.$this->limit;
			//$sql='SELECT o.ID as ordenID,'.'CONCAT( Nombre, Apellidos ) as cliente,o.concepto,o.Anticipo as anticipo,o.Importe as importe,o.Saldo as saldo FROM `orden` o left join crm_persona p on p.ID=o.Cliente';
		}
	}
?>