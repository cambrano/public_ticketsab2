<?php
	@session_start();
	if(!empty($_POST)){
		echo "<pre>";
		var_dump($_POST['genPeriodo']);
		echo "</pre>";
		$fecha_inicial = $_POST['genPeriodo'][0]['fecha_inicial'];
		$fecha_final = $_POST['genPeriodo'][0]['fecha_final'];
		$tipo = $_POST['genPeriodo'][0]['tipo'];
		$ano = $_POST['genPeriodo'][0]['ano'];
		$_SESSION['matriz_rentabilidad_periodo'][$tipo][$ano]['fecha_inicial'] = $fecha_inicial;
		$_SESSION['matriz_rentabilidad_periodo'][$tipo][$ano]['fecha_final'] = $fecha_final;
	}
?>