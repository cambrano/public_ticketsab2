<?php
		function clave2($tipo){ 
			include 'db.php';
			$sql="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$empresa_adjudicada=$row['empresa_adjudicada'];
			$forma_empresa_adjudicada=$row['forma_empresa_adjudicada'];
			if($tipo=="empresas_adjudicadas"){
				//empresa_adjudicada
				$clave=preg_replace("/\((.*?)\)/i", "", $empresa_adjudicada);
				$textual=$empresa_adjudicada;
				$countTotal=strlen($empresa_adjudicada);
				$tabla=$tipo;
				$forma=$forma_empresa_adjudicada;
			}

			$supervisor=$row['supervisor'];
			$forma_supervisor=$row['forma_supervisor'];
			if($tipo=="supervisores"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $supervisor);
				$textual=$supervisor;
				$countTotal=strlen($supervisor);
				$tabla=$tipo;
				$forma=$forma_supervisor;
			}

			$seccion_ine_gira=$row['seccion_ine_gira'];
			$forma_seccion_ine_gira=$row['forma_seccion_ine_gira'];
			if($tipo=="secciones_ine_giras"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_gira);
				$textual=$seccion_ine_gira;
				$countTotal=strlen($seccion_ine_gira);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_gira;
			}

			$seccion_ine_gira=$row['seccion_ine_ciudadano_gira'];
			$forma_seccion_ine_gira=$row['forma_seccion_ine_ciudadano_gira'];
			if($tipo=="secciones_ine_ciudadanos_giras"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_gira);
				$textual=$seccion_ine_gira;
				$countTotal=strlen($seccion_ine_gira);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_gira;
			}

			$clave; 
			preg_match('/\((.+)\)/', $textual, $coincidencias);
			$coincidencias[1]; 
			strlen($coincidencias[1]); 

			//preg_replace("/\[(.*?)\]/i", "", $empleado);
			$partido_2016=$row['partido_2016'];
			$forma_partido_2016=$row['forma_partido_2016'];
			if($tipo=="partidos_2016"){
				//partido
				$clave=preg_replace("/\((.*?)\)/i", "", $partido_2016);
				$textual=$partido_2016;
				$countTotal=strlen($partido_2016);
				$tabla=$tipo;
				$forma=$forma_partido_2016;
			}

			$casilla_voto_2016=$row['casilla_voto_2016'];
			$forma_casilla_voto_2016=$row['forma_casilla_voto_2016'];
			if($tipo=="casillas_votos_2016"){
				//casilla_voto_2016
				$clave=preg_replace("/\((.*?)\)/i", "", $casilla_voto_2016);
				$textual=$casilla_voto_2016;
				$countTotal=strlen($casilla_voto_2016);
				$tabla=$tipo;
				$forma=$forma_casilla_voto_2016;
			}

			$partido_2024=$row['partido_2024'];
			$forma_partido_2024=$row['forma_partido_2024'];
			if($tipo=="partidos_2024"){
				//partido
				$clave=preg_replace("/\((.*?)\)/i", "", $partido_2024);
				$textual=$partido_2024;
				$countTotal=strlen($partido_2024);
				$tabla=$tipo;
				$forma=$forma_partido_2024;
			}

			$casilla_voto_2024=$row['casilla_voto_2024'];
			$forma_casilla_voto_2024=$row['forma_casilla_voto_2024'];
			if($tipo=="casillas_votos_2024"){
				//casilla_voto_2024
				$clave=preg_replace("/\((.*?)\)/i", "", $casilla_voto_2024);
				$textual=$casilla_voto_2024;
				$countTotal=strlen($casilla_voto_2024);
				$tabla=$tipo;
				$forma=$forma_casilla_voto_2024;
			}



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

		function clave2ValidadorSistema($clave=null,$tipo=null,$id_diferente=null){ 
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
		function clave2Id($clave=null,$tipo=null){
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

		function clave2IdDatos($clave=null,$tipo=null){
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


		function clave2Denominacion($clave=null,$tipo=null){
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
		function clave2Datos(){
			include 'db.php';
			$sql="SELECT * FROM claves_2 WHERE 1 = 1 ";
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			
			$datos=$row;
			$conexion->close();
			return $datos; 
		}
?>