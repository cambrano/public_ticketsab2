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

			$tipo_actividad=$row['tipo_actividad'];
			$forma_tipo_actividad=$row['forma_tipo_actividad'];
			if($tipo=="tipos_actividades"){
				//servidor_correo
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_actividad);
				$textual=$tipo_actividad;
				$countTotal=strlen($tipo_actividad);
				$tabla=$tipo;
				$forma=$forma_tipo_actividad;
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

			$red_social=$row['red_social'];
			$forma_red_social=$row['forma_red_social'];
			if($tipo=="redes_sociales"){
				//red_social
				$clave=preg_replace("/\((.*?)\)/i", "", $red_social);
				$textual=$red_social;
				$countTotal=strlen($red_social);
				$tabla=$tipo;
				$forma=$forma_red_social;
			}

			$identidad=$row['identidad'];
			$forma_identidad=$row['forma_identidad'];
			if($tipo=="identidades"){
				//identidad
				$clave=preg_replace("/\((.*?)\)/i", "", $identidad);
				$textual=$identidad;
				$countTotal=strlen($identidad);
				$tabla=$tipo;
				$forma=$forma_identidad;
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

			$cuenta_red_social=$row['cuenta_red_social'];
			$forma_cuenta_red_social=$row['forma_cuenta_red_social'];
			if($tipo=="cuentas_redes_sociales"){
				//cuenta_red_social
				$clave=preg_replace("/\((.*?)\)/i", "", $cuenta_red_social);
				$textual=$cuenta_red_social;
				$countTotal=strlen($cuenta_red_social);
				$tabla=$tipo;
				$forma=$forma_cuenta_red_social;
			}

			$cuenta_red_social_actividad=$row['cuenta_red_social_actividad'];
			$forma_cuenta_red_social_actividad=$row['forma_cuenta_red_social_actividad'];
			if($tipo=="cuentas_redes_sociales_actividades"){
				//cuenta_red_social_actividad
				$clave=preg_replace("/\((.*?)\)/i", "", $cuenta_red_social_actividad);
				$textual=$cuenta_red_social_actividad;
				$countTotal=strlen($cuenta_red_social_actividad);
				$tabla=$tipo;
				$forma=$forma_cuenta_red_social_actividad;
			}

			$seccion_ine=$row['seccion_ine'];
			$forma_seccion_ine=$row['forma_seccion_ine'];
			if($tipo=="secciones_ine"){
				//seccion_ine
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine);
				$textual=$seccion_ine;
				$countTotal=strlen($seccion_ine);
				$tabla=$tipo;
				$forma=$forma_seccion_ine;
			}

			$partido_2018=$row['partido_2018'];
			$forma_partido_2018=$row['forma_partido_2018'];
			if($tipo=="partidos_2018"){
				//partido
				$clave=preg_replace("/\((.*?)\)/i", "", $partido_2018);
				$textual=$partido_2018;
				$countTotal=strlen($partido_2018);
				$tabla=$tipo;
				$forma=$forma_partido_2018;
			}

			$tipo_casilla=$row['tipo_casilla'];
			$forma_tipo_casilla=$row['forma_tipo_casilla'];
			if($tipo=="tipos_casillas"){
				//tipo_casilla
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_casilla);
				$textual=$tipo_casilla;
				$countTotal=strlen($tipo_casilla);
				$tabla=$tipo;
				$forma=$forma_tipo_casilla;
			}

			$casilla_voto_2018=$row['casilla_voto_2018'];
			$forma_casilla_voto_2018=$row['forma_casilla_voto_2018'];
			if($tipo=="casillas_votos_2018"){
				//casilla_voto_2018
				$clave=preg_replace("/\((.*?)\)/i", "", $casilla_voto_2018);
				$textual=$casilla_voto_2018;
				$countTotal=strlen($casilla_voto_2018);
				$tabla=$tipo;
				$forma=$forma_casilla_voto_2018;
			}

			$seccion_ine_ciudadano=$row['seccion_ine_ciudadano'];
			$forma_seccion_ine_ciudadano=$row['forma_seccion_ine_ciudadano'];
			if($tipo=="secciones_ine_ciudadanos"){
				//seccion_ine_ciudadano
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_ciudadano);
				$textual=$seccion_ine_ciudadano;
				$countTotal=strlen($seccion_ine_ciudadano);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_ciudadano;
			}

			$seccion_ine_actividad=$row['seccion_ine_actividad'];
			$forma_seccion_ine_actividad=$row['forma_seccion_ine_actividad'];
			if($tipo=="secciones_ine_actividades"){
				//seccion_ine_actividad
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_actividad);
				$textual=$seccion_ine_actividad;
				$countTotal=strlen($seccion_ine_actividad);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_actividad;
			}

			$partido_2021=$row['partido_2021'];
			$forma_partido_2021=$row['forma_partido_2021'];
			if($tipo=="partidos_2021"){
				//partido
				$clave=preg_replace("/\((.*?)\)/i", "", $partido_2021);
				$textual=$partido_2021;
				$countTotal=strlen($partido_2021);
				$tabla=$tipo;
				$forma=$forma_partido_2021;
			}

			$casilla_voto_2021=$row['casilla_voto_2021'];
			$forma_casilla_voto_2021=$row['forma_casilla_voto_2021'];
			if($tipo=="casillas_votos_2021"){
				//casilla_voto_2021
				$clave=preg_replace("/\((.*?)\)/i", "", $casilla_voto_2021);
				$textual=$casilla_voto_2021;
				$countTotal=strlen($casilla_voto_2021);
				$tabla=$tipo;
				$forma=$forma_casilla_voto_2021;
			}

			$tipo_ciudadano=$row['tipo_ciudadano'];
			$forma_tipo_ciudadano=$row['forma_tipo_ciudadano'];
			if($tipo=="tipos_ciudadanos"){
				//tipo_ciudadano
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_ciudadano);
				$textual=$tipo_ciudadano;
				$countTotal=strlen($tipo_ciudadano);
				$tabla=$tipo;
				$forma=$forma_tipo_ciudadano;
			}

			$distrito_local=$row['distrito_local'];
			$forma_distrito_local=$row['forma_distrito_local'];
			if($tipo=="distritos_locales"){
				//distrito_local
				$clave=preg_replace("/\((.*?)\)/i", "", $distrito_local);
				$textual=$distrito_local;
				$countTotal=strlen($distrito_local);
				$tabla=$tipo;
				$forma=$forma_distrito_local;
			}

			$distrito_federal=$row['distrito_federal'];
			$forma_distrito_federal=$row['forma_distrito_federal'];
			if($tipo=="distritos_federales"){
				//distrito_federal
				$clave=preg_replace("/\((.*?)\)/i", "", $distrito_federal);
				$textual=$distrito_federal;
				$countTotal=strlen($distrito_federal);
				$tabla=$tipo;
				$forma=$forma_distrito_federal;
			}

			$tipo_categoria_ciudadano=$row['tipo_categoria_ciudadano'];
			$forma_tipo_categoria_ciudadano=$row['forma_tipo_categoria_ciudadano'];
			if($tipo=="tipos_categorias_ciudadanos"){
				//tipo_categoria_ciudadano
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_categoria_ciudadano);
				$textual=$tipo_categoria_ciudadano;
				$countTotal=strlen($tipo_categoria_ciudadano);
				$tabla=$tipo;
				$forma=$forma_tipo_categoria_ciudadano;
			}

			$tipo_territorio=$row['tipo_territorio'];
			$forma_tipo_territorio=$row['forma_tipo_territorio'];
			if($tipo=="tipos_territorios"){
				//tipo_territorio
				$clave=preg_replace("/\((.*?)\)/i", "", $tipo_territorio);
				$textual=$tipo_territorio;
				$countTotal=strlen($tipo_territorio);
				$tabla=$tipo;
				$forma=$forma_tipo_territorio;
			}

			$categoria_programa_apoyo=$row['categoria_programa_apoyo'];
			$forma_categoria_programa_apoyo=$row['forma_categoria_programa_apoyo'];
			if($tipo=="categorias_programas_apoyos"){
				//categoria_programa_apoyo
				$clave=preg_replace("/\((.*?)\)/i", "", $categoria_programa_apoyo);
				$textual=$categoria_programa_apoyo;
				$countTotal=strlen($categoria_programa_apoyo);
				$tabla=$tipo;
				$forma=$forma_categoria_programa_apoyo;
			}

			$programa_apoyo=$row['programa_apoyo'];
			$forma_programa_apoyo=$row['forma_programa_apoyo'];
			if($tipo=="programas_apoyos"){
				//programa_apoyo
				$clave=preg_replace("/\((.*?)\)/i", "", $programa_apoyo);
				$textual=$programa_apoyo;
				$countTotal=strlen($programa_apoyo);
				$tabla=$tipo;
				$forma=$forma_programa_apoyo;
			}

			$cuartel=$row['cuartel'];
			$forma_cuartel=$row['forma_cuartel'];
			if($tipo=="cuarteles"){
				//cuartel
				$clave=preg_replace("/\((.*?)\)/i", "", $cuartel);
				$textual=$cuartel;
				$countTotal=strlen($cuartel);
				$tabla=$tipo;
				$forma=$forma_cuartel;
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
			$partido_legado=$row['partido_legado'];
			$forma_partido_legado=$row['forma_partido_legado'];
			if($tipo=="partidos_legados"){
				//partido_legado
				$clave=preg_replace("/\((.*?)\)/i", "", $partido_legado);
				$textual=$partido_legado;
				$countTotal=strlen($partido_legado);
				$tabla=$tipo;
				$forma=$forma_partido_legado;
			}

			$seccion_ine_ciudadano_programa_apoyo=$row['seccion_ine_ciudadano_programa_apoyo'];
			$forma_seccion_ine_ciudadano_programa_apoyo=$row['forma_seccion_ine_ciudadano_programa_apoyo'];
			if($tipo=="secciones_ine_ciudadanos_programas_apoyos"){
				//seccion_ine_ciudadano_programa_apoyo
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_ciudadano_programa_apoyo);
				$textual=$seccion_ine_ciudadano_programa_apoyo;
				$countTotal=strlen($seccion_ine_ciudadano_programa_apoyo);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_ciudadano_programa_apoyo;
			}

			$militante_partido=$row['militante_partido'];
			$forma_militante_partido=$row['forma_militante_partido'];
			if($tipo=="militantes_partidos"){
				//militante_partido
				$clave=preg_replace("/\((.*?)\)/i", "", $militante_partido);
				$textual=$militante_partido;
				$countTotal=strlen($militante_partido);
				$tabla=$tipo;
				$forma=$forma_militante_partido;
			}

			$seccion_ine_grupo=$row['seccion_ine_grupo'];
			$forma_seccion_ine_grupo=$row['forma_seccion_ine_grupo'];
			if($tipo=="secciones_ine_grupos"){
				//seccion_ine_grupo
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_grupo);
				$textual=$seccion_ine_grupo;
				$countTotal=strlen($seccion_ine_grupo);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_grupo;
			}

			$seccion_ine_ciudadano_grupo=$row['seccion_ine_ciudadano_grupo'];
			$forma_seccion_ine_ciudadano_grupo=$row['forma_seccion_ine_ciudadano_grupo'];
			if($tipo=="secciones_ine_ciudadanos_grupos"){
				//seccion_ine_ciudadano_grupo
				$clave=preg_replace("/\((.*?)\)/i", "", $seccion_ine_ciudadano_grupo);
				$textual=$seccion_ine_ciudadano_grupo;
				$countTotal=strlen($seccion_ine_ciudadano_grupo);
				$tabla=$tipo;
				$forma=$forma_seccion_ine_ciudadano_grupo;
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