<?php
		function clave($tipo){ 
			include 'db.php';
			$sql="SELECT * FROM claves WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$empleado=$row['empleado'];
			$forma_empleado=$row['forma_empleado'];
			if($tipo=="empleados"){
				//empleado
				$clave=preg_replace("/\((.*?)\)/i", "", $empleado);
				$textual=$empleado;
				$countTotal=strlen($empleado);
				$tabla=$tipo;
				$forma=$forma_empleado;
			}

			

			$servidor_correo=$row['servidor_correo'];
			$forma_servidor_correo=$row['forma_servidor_correo'];
			if($tipo=="servidores_correos"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $servidor_correo);
				$textual=$servidor_correo;
				$countTotal=strlen($servidor_correo);
				$tabla=$tipo;
				$forma=$forma_servidor_correo;
			}

			$correo_electronico=$row['correo_electronico'];
			$forma_correo_electronico=$row['forma_correo_electronico'];
			if($tipo=="correos_electronicos"){
				//correo_electronico
				$clave=preg_replace("/\((.*?)\)/i", "", $correo_electronico);
				$textual=$correo_electronico;
				$countTotal=strlen($correo_electronico);
				$tabla=$tipo;
				$forma=$forma_correo_electronico;
			}

			$dependencia=$row['dependencia'];
			$forma_dependencia=$row['forma_dependencia'];
			if($tipo=="dependencias"){
				//dependencia
				$clave=preg_replace("/\((.*?)\)/i", "", $dependencia);
				$textual=$dependencia;
				$countTotal=strlen($dependencia);
				$tabla=$tipo;
				$forma=$forma_dependencia;
			}
			$sub_dependencia=$row['dependencia'];
			$forma_sub_dependencia=$row['forma_dependencia'];
			
			if($tipo=="sub_dependencias"){
				//dependencia
				$clave="S".preg_replace("/\((.*?)\)/i", "", $sub_dependencia);
				$textual=$sub_dependencia;
				$countTotal=strlen($sub_dependencia);
				$tabla=$tipo;
				$forma=$forma_sub_dependencia;
			}


			$titulo_personal=$row['titulo_personal'];
			$forma_titulo_personal=$row['forma_titulo_personal'];
			if($tipo=="titulos_personales"){
				//titulo_personal
				$clave=preg_replace("/\((.*?)\)/i", "", $titulo_personal);
				$textual=$titulo_personal;
				$countTotal=strlen($titulo_personal);
				$tabla=$tipo;
				$forma=$forma_titulo_personal;
			}

			$ubicacion=$row['ubicacion'];
			$forma_ubicacion=$row['forma_ubicacion'];
			if($tipo=="ubicaciones"){
				//ubicacion
				$clave=preg_replace("/\((.*?)\)/i", "", $ubicacion);
				$textual=$ubicacion;
				$countTotal=strlen($ubicacion);
				$tabla=$tipo;
				$forma=$forma_ubicacion;
			}
			$directorio=$row['directorio'];
			$forma_directorio=$row['forma_directorio'];
			if($tipo=="directorios"){
				//directorio
				$clave=preg_replace("/\((.*?)\)/i", "", $directorio);
				$textual=$directorio;
				$countTotal=strlen($directorio);
				$tabla=$tipo;
				$forma=$forma_directorio;
			}
			
			$tipo_equipo=$row['tipo_equipo'];
			$forma_tipo_equipo=$row['forma_tipo_equipo'];
			if($tipo=="tipos_equipos"){
				//tipo_equipo
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_equipo);
				$textual=$tipo_equipo;
				$countTotal=strlen($tipo_equipo);
				$tabla=$tipo;
				$forma=$forma_tipo_equipo;
			}

			$responsable_equipo=$row['responsable_equipo'];
			$forma_responsable_equipo=$row['forma_responsable_equipo'];
			if($tipo=="responsables_equipos"){
				//responsable_equipo
				$clave=preg_replace("/\((.*?)\)/i", "", $responsable_equipo);
				$textual=$responsable_equipo;
				$countTotal=strlen($responsable_equipo);
				$tabla=$tipo;
				$forma=$forma_responsable_equipo;
			}

			$sistema_operativo=$row['sistema_operativo'];
			$forma_sistema_operativo=$row['forma_sistema_operativo'];
			if($tipo=="sistemas_operativos"){
				//sistema_operativo
				$clave=preg_replace("/\((.*?)\)/i", "", $sistema_operativo);
				$textual=$sistema_operativo;
				$countTotal=strlen($sistema_operativo);
				$tabla=$tipo;
				$forma=$forma_sistema_operativo;
			}

			$software=$row['software'];
			$forma_software=$row['forma_software'];
			if($tipo=="softwares"){
				//software
				$clave=preg_replace("/\((.*?)\)/i", "", $software);
				$textual=$software;
				$countTotal=strlen($software);
				$tabla=$tipo;
				$forma=$forma_software;
			}

			$equipo=$row['equipo'];
			$forma_equipo=$row['forma_equipo'];
			if($tipo=="equipos"){
				//equipo
				$clave=preg_replace("/\((.*?)\)/i", "", $equipo);
				$textual=$equipo;
				$countTotal=strlen($equipo);
				$tabla=$tipo;
				$forma=$forma_equipo;
			}

			
			

			$clave; 
			preg_match('/\((.+)\)/', $textual, $coincidencias);
			$coincidencias[1]; 
			strlen($coincidencias[1]); 

			//preg_replace("/\[(.*?)\]/i", "", $empleado);


			$sql="SELECT * FROM {$tabla} WHERE clave LIKE '%{$clave}%'  ";
			$result = $conexion->query($sql);
			while($row=$result->fetch_assoc()){
				$row['clave'];
				$tablaCalve = str_replace($clave,"", $row['clave']);
				if(strlen($coincidencias[1]) == strlen($tablaCalve)){
					$arrayNumero[]= $tablaCalve;
				} 
			}
			sort($arrayNumero);
			foreach ($arrayNumero as $key => $value) {
				$numero= $value; 
			}
			$numero=$numero+1; 
			
			$forma = strtolower($forma);
			$arrayClave['tipo']=$forma;
			if($forma=='automatico'){
				$arrayClave['input']='disabled="disabled"';
				$arrayClave['clave']=$clave.str_pad($numero, strlen($coincidencias[1]),0,STR_PAD_LEFT);
			}else{
				$arrayClave['input']='';
				$arrayClave['clave']='';
			}
			$conexion->close();
			return $arrayClave;
		}

		function claveValidadorSistema($clave=null,$tipo=null,$id_diferente=null){ 
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE clave = '{$clave}' ";
			if($id_diferente!=""){
				$sql.=" AND id != {$id_diferente} ";
			}
			$sql;
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']==""){
				$existe=false;
			}else{
				$existe=true;
			}
			$conexion->close();
			return $existe; 
		}
		function claveId($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE 1 = 1 ";
			if($clave !=""){
				$sql.=" AND clave = '{$clave}' ";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']=="" && $clave==""){
				$existe=false;
			}else{
				$existe=$row['id'];
			}
			$conexion->close();
			return $existe; 
		}

		function claveIdDatos($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE 1 = 1 ";
			if($clave !=""){
				$sql.=" AND clave = '{$clave}' ";
			}
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			$existe=$row;
			$conexion->close();
			return $existe; 
		}


		function claveDenominacion($clave=null,$tipo=null){
			include 'db.php';
			$sql="SELECT * FROM {$tipo} WHERE clave = '{$clave}'  ";
			$resultado = $conexion->query($sql); 
			$row=$resultado->fetch_assoc();
			if($row['id']==""){
				$existe=false;
			}else{
				$existe=$row['denominacion'];
			}
			$conexion->close();
			return $existe; 
		}
		function claveDatos(){
			include 'db.php';
			$sql="SELECT * FROM claves WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$datos=$row;
			$conexion->close();
			return $datos; 
		}
?>