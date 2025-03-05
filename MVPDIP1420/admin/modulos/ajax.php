<?php
		include 'functions/security.php'; 
		include '../functions/modulos.php';  

		if(!empty($_POST)){
			$id_seccion=$_POST['id_seccion'];
			$id_empleado=$_POST['id_empleado'];
			if($id_modulo !="x"){
				echo modulosExcluirEmpleado('',$id_seccion,$id_empleado);
			}else{
				echo "<option value='' >Seleccione</option> ";
			}
		}
?>
