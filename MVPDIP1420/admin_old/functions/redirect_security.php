<?php
	function redirectSecurity($id=null,$tabla,$folder,$file){
		include 'db.php'; 
		if($id==""){
			echo $return = "<script type='text/javascript'>$('#homebody').load('{$folder}/{$file}.php');</script>";
			die;
		}
		$sql="SELECT * FROM {$tabla} WHERE id='{$id}' ";
		$resultado = $conexion->query($sql); 
		$row=$resultado->fetch_assoc();
		if($row['id']==""){
			//$return;
			$return = "<script type='text/javascript'>$('#homebody').load('{$folder}/{$file}.php');</script>";
		}else{
			$return="";
		}
		$conexion->close();
		return $return;
	}
?>