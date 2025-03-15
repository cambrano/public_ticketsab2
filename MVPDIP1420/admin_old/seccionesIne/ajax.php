<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/clientes.php";

	if(!empty($_POST)){
		$id_cliente=$_POST['id_cliente'];
		if($id_cliente !="x"){
			echo clientes($id_cliente);
		}else{
			echo "<option value='' >Seleccione</option> ";
		} 
	}
?>
