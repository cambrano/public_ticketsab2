<?php
		include '../functions/security.php'; 
		if(!empty($_POST)){
			include '../functions/secciones_ine.php'; 
			if($_POST['tipo']=='id_secciones_ine_array'){
				$id_municipio=$_POST['id_municipio'];
				$id_secciones_ine=$_POST['id_secciones_ine'];
				echo secciones_ineIn($id_secciones_ine,$id_municipio,'','','SIN');
			}
		}