<?php
		include 'functions/security.php'; 
		include '../functions/modulos.php';  

		if(!empty($_POST)){
			$id_seccion=$_POST['id_seccion'];
			if($id_modulo !="x"){
				echo modulos($id_modulo,$id_seccion);
			}else{
				echo "<option value='' >Seleccione</option> ";
			}
		}
?>
