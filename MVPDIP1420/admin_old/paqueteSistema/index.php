<?php

		@session_start(); 
		include "../functions/error.php"; 
		
		include "../functions/security.php"; 
		include 'functions/security.php';

		include "../functions/timemex.php"; 
		include 'functions/timemex.php';

		include "../functions/paquetes_sistema.php";  
		include 'functions/paquetes_sistema.php';
		include "../functions/pagos_sistema.php";  
		include 'functions/pagos_sistema.php';

		include "../functions/usuarios.php";  

		$_SESSION['Paguinasub']="paqueteSistema/index.php";
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
	<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>  
		<title>Paquete Sistema</title>
	</head>
	<body>
		<div id="bodymanager" class="bodymanager" style="display: table; ">
			<label class="tituloForm">
				<font style="font-size: 20px;">Paquete Sistema</font>
			</label><br>
			<?php
				include "data.php";
			?>



			
			
			<div id="dataTable" hidden="hidden">
				<div><?php include "filtros.php"; ?></div>
				<?php include "table.php"; ?>
			</div>
		</div>
	</body>
	</html> 