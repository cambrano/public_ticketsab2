<?php
	include __DIR__.'/functions/switch_operaciones.php';
	include __DIR__.'/functions/secciones_ine_ciudadanos_permisos.php';
	include __DIR__.'/functions/security.php';
	$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['registro']){
		$registro = 1;
	}else{
		$registro = 0;
	}

	if($switch_operacionesPermisos['entrega'] && $seccion_ine_ciudadano_permisosDatos['entrega'] == "1"){
		$entrega = 1;
	}else{
		$entrega = 0;
	}

	if($switch_operacionesPermisos['recibe'] && $seccion_ine_ciudadano_permisosDatos['recibe'] == "1"){
		$recibe = 1;
	}else{
		$recibe = 0;
	}

	if($switch_operacionesPermisos['evaluacion']){
		$evaluacion = 1;
	}else{
		$evaluacion = 0;
	}

	if($switch_operacionesPermisos['casilla'] && $seccion_ine_ciudadano_permisosDatos['casilla'] == "1"){
		$casilla = 1;
	}else{
		$casilla = 0;
	}
?>
<ul id="nav" class="nav navbar-nav">
	<li><a href='#' id="home">Inicio</a></li>
	<?php
		if($registro == 1 || $registro == 1 || $evaluacion == 1){
			echo "<li><a href='#' id='secciones_ine_ciudadanos'>Amigos</a></li>";
		}
	?>
	<?php
		if($entrega == 1 || $recibe == 1){
			echo "<li><a href='#' id='secciones_ine_ciudadanos_entrega'>Checks</a></li>";
		}
	?>
	<?php
		if($casilla == 1 || $casilla == 1){
			echo "<li><a href='#' id='casillas_votos_2024_a'>Municipal</a></li>";
			echo "<li><a href='#' id='casillas_votos_2024_dl'>Local</a></li>";
			echo "<li><a href='#' id='casillas_votos_2024_df'>Federal</a></li>";
		}
	?>
	<li><a href='#' id="configuracion_ciudadano">Settings</a></li>
</ul>