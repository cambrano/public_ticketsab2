<?php
	@session_start();
	$_SESSION['Paguinasub']="seccionesIneCiudadanos/update.php";
	$_SESSION['paguinaId']=$_GET['id'];
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	if($_GET['refresh']==2){
		?>
		<script type="text/javascript">
			window.location.href = '../';
		</script>
		<?php
		die;
	}