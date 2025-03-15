<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/casillas_votos_2022_revocacion_mandato.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2022_revocacion_mandato',$_COOKIE["id_usuario"]);
	?>
	<title>Casillas 2022 Renovación Mandato</title>
	<div id="bodymanager" class="bodymanager">
		<?php
			if($_COOKIE['subPage']==1){
				echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
			}else{
				echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
			}
		?>
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
			Casillas 2022 Revocación de mandato
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					if($tipo_uso_plataforma=='municipio'){
						echo '<input type="button" value="Nueva Casilla" onClick="addAyuntamiento();">';
					}elseif($tipo_uso_plataforma=='distrito_local'){
						echo '<input type="button" value="Nueva Casilla" onClick="addDistritoLocal();">';	
					}elseif($tipo_uso_plataforma=='distrito_federal'){
						echo '<input type="button" value="Nueva Casilla" onClick="addDistritoFederal();">';	
					}
				}
			?>
		</div>
		<br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>