<?php
		include 'functions/security.php'; 
		include '../functions/permisos.php';  

		if(!empty($_POST)){
			//var_dump($_POST);
			$id_modulo=$_POST['id_modulo'];
			$tipo=$_POST['tipo'];
			//if($id_modulo !="x"){
			//	echo permisosChk($id_modulo,$id_permiso);
			//}
			
			if($tipo =="select"){
				echo permisos($id_modulo,$id_permiso,'SIN');
			}
			if($tipo =="chbox"){
				echo permisosChk($id_modulo,$id_permiso);
			}
		}
?>
