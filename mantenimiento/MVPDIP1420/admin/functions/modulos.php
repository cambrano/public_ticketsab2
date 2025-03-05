<?php
		function modulos($id_modulo=null,$id_seccion=null) {
			include 'db.php'; 
			include '../functions/db.php'; 
			include '../functions/elecciones.php'; 
			$select[$id_modulo]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM modulos WHERE status=1 ";
			$sql.=" AND id_seccion='{$id_seccion}' ";
			$sql.=" ORDER BY grupo,orden ASC ;";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$datos[$row['grupo']][]=$row;
			}
			foreach ($datos as $key => $value) {
				$mostrar = false;
				$pos = strpos($key,'*20');
				if ($pos !== false) {
					$elecciones = elecciones();
					$ano_array = explode("*", $key);
					$ano = $ano_array[1];
					$eleccion = $elecciones[$ano];
					unset($eleccion_array);
					if($eleccion['municipios_show'] == 1){
						$eleccion_array[] = $eleccion['municipios'];
					}
					if($eleccion['distritos_locales_show'] == 1){
						$eleccion_array[] = $eleccion['distritos_locales'];
					}
					if($eleccion['distritos_federales_show'] == 1){
						$eleccion_array[] = $eleccion['distritos_federales'];
					}
					// Elimina los elementos duplicados
					$uniqueArray = array_unique($eleccion_array);

					$texto = implode(',',$uniqueArray);
					if($texto != ""){
						$mostrar = true;
					}
					$modificar = $ano_array[0].' '.$texto;
				} else {
					$mostrar = true;
					$modificar = $key;
				}
				if($mostrar == true){
					$return .="<optgroup label='".ucwords($modificar)."' data-max-options='2'>";
					foreach ($value as $keyT => $valueT) {
						$elecciones = elecciones();
						$ano_array = explode("*", $valueT['vista']);
						$ano = $ano_array[1];
						$eleccion = $elecciones[$ano];
						unset($eleccion_array);
						if($eleccion['municipios_show'] == 1){
							$eleccion_array[] = $eleccion['municipios'];
						}
						if($eleccion['distritos_locales_show'] == 1){
							$eleccion_array[] = $eleccion['distritos_locales'];
						}
						if($eleccion['distritos_federales_show'] == 1){
							$eleccion_array[] = $eleccion['distritos_federales'];
						}
						// Elimina los elementos duplicados
						$uniqueArray = array_unique($eleccion_array);
						$texto = implode(',',$uniqueArray);
						$modificar = $ano_array[0].' '.$texto;
						$num=$num+1;
						$return .="<option  value='".$valueT['id']."' >".$valueT['orden'].' - '.ucwords($modificar)."</option> ";
					}
				}
				$return .="</optgroup>";
			}
			return $return;
		}
		function modulosold($id_modulo=null,$id_seccion=null) {
			include 'db.php'; 
			include '../functions/db.php'; 
			$select[$id_modulo]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			$sql="SELECT * FROM modulos WHERE status=1 ";
			$sql.=" AND id_seccion='{$id_seccion}' ";

			$result = $conexion->query($sql);  

			while($row=$result->fetch_assoc()){
				$sel=$row['id'];
				$nombre=str_replace("_"," ",$row['vista']);
				$return .="<option ".$select[$sel]." value='".$row['id']."' >".ucwords($nombre)."</option> ";
			} 
			$conexion->close();
			return $return;
		}
		function modulosDatos($id_modulo=null,$id_seccion=null,$status = null) {
			include 'db.php'; 
			include '../functions/db.php'; 
			$sql="SELECT * FROM modulos WHERE status=1 ";
			$sql.=" AND id_seccion='{$id_seccion}' ";
			$sql.=" AND status='{$status}' ";

			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$return[] = $row;
			} 
			$conexion->close();
			return $return;
		}
		function moduloNombre($id_modulo=null){
			include 'db.php'; 
			include '../functions/db.php'; 
			$sql=("SELECT * FROM modulos WHERE id='$id_modulo'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			$nombre=str_replace("_"," ",$row['modulo']);
			$conexion->close();
			return $nombre;
		}
		function modulosExcluirEmpleado($id_modulo=null,$id_seccion=null,$id_empleado=null) {
			include 'db.php'; 
			include '../functions/db.php'; 
			include '../functions/elecciones.php'; 
			$select[$id_modulo]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			
			$sql="SELECT * FROM modulos m WHERE m.status=1 AND m.id_seccion = '".$id_seccion."' AND 
					NOT EXISTS (
					SELECT * FROM usuarios_modulos um WHERE um.id_modulo = m.id AND um.id_empleado = '".$id_empleado."'
					) ";
			
			$sql.=" ORDER BY grupo,orden ASC ;";
			$result = $conexion->query($sql);  
			while($row=$result->fetch_assoc()){
				$datos[$row['grupo']][]=$row;
			}
			foreach ($datos as $key => $value) {
				$mostrar = false;
				$pos = strpos($key,'*20');
				if ($pos !== false) {
					$elecciones = elecciones();
					$ano_array = explode("*", $key);
					$ano = $ano_array[1];
					$eleccion = $elecciones[$ano];
					unset($eleccion_array);
					if($eleccion['municipios_show'] == 1){
						$eleccion_array[] = $eleccion['municipios'];
					}
					if($eleccion['distritos_locales_show'] == 1){
						$eleccion_array[] = $eleccion['distritos_locales'];
					}
					if($eleccion['distritos_federales_show'] == 1){
						$eleccion_array[] = $eleccion['distritos_federales'];
					}
					// Elimina los elementos duplicados
					$uniqueArray = array_unique($eleccion_array);

					$texto = implode(',',$uniqueArray);
					if($texto != ""){
						$mostrar = true;
					}
					$modificar = $ano_array[0].' '.$texto;
				} else {
					$mostrar = true;
					$modificar = $key;
				}
				if($mostrar == true){
					$return .="<optgroup label='".ucwords($modificar)."' data-max-options='2'>";
					foreach ($value as $keyT => $valueT) {
						$elecciones = elecciones();
						$ano_array = explode("*", $valueT['vista']);
						$ano = $ano_array[1];
						$eleccion = $elecciones[$ano];
						unset($eleccion_array);
						if($eleccion['municipios_show'] == 1){
							$eleccion_array[] = $eleccion['municipios'];
						}
						if($eleccion['distritos_locales_show'] == 1){
							$eleccion_array[] = $eleccion['distritos_locales'];
						}
						if($eleccion['distritos_federales_show'] == 1){
							$eleccion_array[] = $eleccion['distritos_federales'];
						}
						// Elimina los elementos duplicados
						$uniqueArray = array_unique($eleccion_array);
						$texto = implode(',',$uniqueArray);
						$modificar = $ano_array[0].' '.$texto;
						$num=$num+1;
						$return .="<option  value='".$valueT['id']."' >".$valueT['orden'].' - '.ucwords($modificar)."</option> ";
					}
				}
				$return .="</optgroup>";
			}
			return $return;
		}
?>