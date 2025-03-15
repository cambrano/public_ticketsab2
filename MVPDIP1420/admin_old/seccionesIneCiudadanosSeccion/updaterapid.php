<?php
	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanosSeccion/update.php";
	$_SESSION['paguinaId']=$_GET['id'];
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}