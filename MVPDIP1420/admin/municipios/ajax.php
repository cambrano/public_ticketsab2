<?php
		include 'functions/security.php'; 
		include '../functions/municipios.php';  

		if(!empty($_POST)){
			$id_estado=$_POST['id_estado'];
			$id_municipio=null;
			if($id_estado !="x"){
				echo municipios($id_municipio,$id_estado,'');
			}else{
				echo "<option value='' >Seleccione</option> ";
			}
			
			
			
			 
		}
?>
