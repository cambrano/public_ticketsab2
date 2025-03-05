<?php
		include '../functions/security.php'; 
		include '../functions/localidades.php'; 
		if(!empty($_POST)){
			if($_POST['tipo']=='municipio_array'){
				$id_estado=$_POST['id_estado'];
				$id_municipio=$_POST['id_municipio'];
				$id_localidad=null;
				if($id_estado !="x"){
					echo localidades($id_localidad,$id_municipio,$id_estado,'');
				}else{
					echo "<option value='' >Seleccione</option> ";
				}
			}else{
				$id_estado=$_POST['id_estado'];
				$id_municipio=$_POST['id_municipio'];
				
				if($_POST['id_localidad']>0){
					$id_localidad=$_POST['id_localidad'];
				}else{
					$id_localidad=null;
				}
				
				if($id_estado !="x"){
					echo localidades($id_localidad,$id_municipio,$id_estado,'');
				}else{
					echo "<option value='' >Seleccione</option> ";
				}
			}
		}