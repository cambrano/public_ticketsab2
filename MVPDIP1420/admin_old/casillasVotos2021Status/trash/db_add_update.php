<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/timemex.php";
	include __DIR__."/../functions/log_usuarios.php";
	include __DIR__."/../functions/camparaRegistros.php";
	include __DIR__."/../functions/configuracion.php";
	include __DIR__."/../functions/usuario_permisos.php";

	echo "<pre>";
	var_dump($_POST);
	echo "</pre>";

	$id_casilla_voto_2021 = $_POST['casillas_votos_2021_status'][0]['id_casilla_voto_2021'];
	$status = $_POST['casillas_votos_2021_status'][0]['status'];
	$fecha = $_POST['casillas_votos_2021_status'][0]['fecha'];
	$hora = $_POST['casillas_votos_2021_status'][0]['hora'];

	