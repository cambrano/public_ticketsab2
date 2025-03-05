<?php
	include __DIR__."/../functions/security.php";
	if(!empty($_POST)){

		$id = $_POST['casilla_voto'][0]['id_casilla_voto_2024'];
		$tipo = $_POST['casilla_voto'][0]['tipo'];
		if($tipo=='x'){
			$tipo=0;
		}
		
		setcookie("id_casilla_voto_partidos",$id,time()+(60*60*8),"/",false);
		setcookie("id_casilla_voto_partidos_tipo",$tipo,time()+(60*60*8),"/",false);
		if($_COOKIE["id_casilla_voto_partidos"]==""){
			echo "Error, refresque";
		}else{
			echo "Casilla Asignada,Gracias.";
		}
	}