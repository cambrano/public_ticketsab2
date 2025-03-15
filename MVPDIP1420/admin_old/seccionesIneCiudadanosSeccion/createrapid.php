<?php
	@session_start();
	unset($_SESSION['Paguinasub']);
	$_SESSION['Paguinasub']="seccionesIneCiudadanosSeccion/create.php";
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}