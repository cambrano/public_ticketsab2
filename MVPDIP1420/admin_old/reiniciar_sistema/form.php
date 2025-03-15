	<?php
		include __DIR__."/../functions/security.php";
		include '../functions/usuario_permisos.php';
		@session_start(); 
		if($_COOKIE["id_usuario"]!=1){
			echo "<script type='text/javascript'>$('#homebody').load('home.php');</script>";
			die;
		}
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>  
		<title>form</title>
		<link rel="icon" type="image/png" href="img/favicon.png"> 
	</head>
	<body> 
		<?php
		if(
			moduloAccion('configuracion','configuracion_inicial',$_COOKIE["id_usuario"],$permiso) ||
			moduloAccion('configuracion','configuracion_inicial',$_COOKIE["id_usuario"],'All') ){
			?>
			<input type="button" id="sumbmit" onclick="guardar()" value="Reset Sistema">
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
			<?php
		}
		?>
	</body>
	</html> 