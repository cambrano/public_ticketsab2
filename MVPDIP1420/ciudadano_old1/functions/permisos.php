<?php

	function permisos($id_modulo=null,$id_permiso=null) {
			include 'db.php';
			include 'functions/db.php'; 
			$select[$sel]='selected="selected"';
			$return ="<option ".$select[$sel]." value='' >Seleccione</option> ";
			if($id_modulo!=""){
				$sql=("SELECT * FROM modulos WHERE id='{$id_modulo}' ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
				$permisos=$row['detalle'];

				 
				$select[$id_permiso]='selected="selected"';
				
				$sql="SELECT * FROM permisos WHERE 1 = 1 ";
				$sql.=" AND id IN ({$permisos})";

				$result = $conexion->query($sql);  
				 
				while($row=$result->fetch_assoc()){
					$sel=$row['id'];
					if($row['permiso']=="view"){
						$nombre="Vista";
					}
					if($row['permiso']=="insert"){
						$nombre="Inserción";
					}
					if($row['permiso']=="update"){
						$nombre="Modificación";
					}
					if($row['permiso']=="delete"){
						$nombre="Eliminación";
					}
					if($row['permiso']=="download"){
						$nombre="Descarga";
					}
					if($row['permiso']=="all"){
						$nombre="Total";
					}
					$return .="<option ".$select[$sel]." value='".$row['id']."' >".$nombre."</option> ";
				} 
			}
			$conexion->close();
			return $return;
		}
		function permisosChk($id_modulo=null,$id_permiso=null,$arrayPermisos=null) {
			include 'db.php';
			//var_dump($arrayPermisos);
			$return ="";
			if($id_modulo!=""){
				$sql=("SELECT * FROM modulos WHERE id='{$id_modulo}' ");
				$resultado = $conexion->query($sql);
				$row=$resultado->fetch_assoc();
				$permisos=$row['detalle'];

				 
				$select[$id_permiso]='selected="selected"';
				
				$sql="SELECT * FROM permisos WHERE 1 = 1 ";
				$sql.=" AND id IN ({$permisos})";

				$result = $conexion->query($sql);  
				 
				while($row=$result->fetch_assoc()){
					$sel=$row['id'];
					if($row['permiso']=="view"){
						$nombre="Vista";
					}
					if($row['permiso']=="insert"){
						$nombre="Inserción";
					}
					if($row['permiso']=="update"){
						$nombre="Modificación";
					}
					if($row['permiso']=="delete"){
						$nombre="Eliminación";
					}
					if($row['permiso']=="download"){
						$nombre="Descarga";
					}
					if($row['permiso']=="all"){
						$nombre="Total";
					}
					$id=$row['id'];
					$indice = in_array($id, $arrayPermisos);
					if(in_array($id, $arrayPermisos)){
						$chkpermiso='checked="checked"';
					}else{
						$chkpermiso="";
					}

					$return .= '<input '.$chkpermiso.' type="checkbox" name="permiso" id="'.$id.'" value="'.$id.'" />&nbsp;';
					$return .= '<label class="labelForm" id="labeltemaname">'.$nombre.' </label><br><br>';
				} 
			}
			$conexion->close();
			return $return;
		}
		function permisoNombre($id_permiso){
			include 'db.php'; 
			include 'functions/db.php'; 
			$sql=("SELECT * FROM permisos WHERE id='$id_permiso'");
			$resultado = $conexion->query($sql);
			$row=$resultado->fetch_assoc();
			if($row['permiso']=="view"){
				$nombre="Vista";
			}
			if($row['permiso']=="insert"){
				$nombre="Inserción";
			}
			if($row['permiso']=="update"){
				$nombre="Modificación";
			}
			if($row['permiso']=="delete"){
				$nombre="Eliminación";
			}
			if($row['permiso']=="download"){
				$nombre="Descarga";
			}
			if($row['permiso']=="all"){
				$nombre="Total";
			}
			$conexion->close();
			return $nombre;
		}
		function permisosDatos($id_permiso=null){ 
			include 'db.php'; 
			$sql="SELECT * FROM permisos WHERE 1 = 1 ";
			$result = $conexion->query($sql); 
			$num=0; 
			while($row=$result->fetch_assoc()){
				$datos[$num]=$row;
				$num=$num+1;
			}
			if($num==0){
				$datos=null;
			}
			$conexion->close();
			return $datos;
		}
?>