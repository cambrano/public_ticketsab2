<?php
	@session_start(); 
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_estructuras',$_COOKIE["id_usuario"]);
	?>
	<title>Secciones Ine Ciudadanos Estructuras</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
				?>
				<script type="text/javascript">
					document.getElementById("mensaje").classList.add("mensajeError");
					$("#mensaje").html("No tiene permiso");
					urlink="home.php";
					dataString = 'urlink='+urlink; 
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					$("#homebody").load(urlink);
				</script>
				<?php
				die;
			}
		?>
		<label class="tituloForm">
			Estructuras
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<input id="btn_descargarExcel" type="button" value="Excel Estructura" onClick="downloadExcel();"> 
			<input id="btn_descargarExcel1" type="button" value="Excel Estructura Separada" onClick="downloadExcel1();"> 
			<input id="btn_descargarExcel2" type="button" value="Excel Estructura Lineal" onClick="downloadExcel2();"> 
		</div> 
		<br><br>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>