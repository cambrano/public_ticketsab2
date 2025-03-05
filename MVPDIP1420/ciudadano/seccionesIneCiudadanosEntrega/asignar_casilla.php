<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){
		$id = $_POST['casilla_voto'][0]['id_casilla_voto_2024'];
		setcookie("id_casilla_voto",$id,time()+(60*60*8),"/",false);
		if($_COOKIE["id_casilla_voto"]==""){
			echo "Error, refresque";
		}else{
			echo "Casilla Asignada,Gracias.";
		}
	}