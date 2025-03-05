<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);
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
	<script>
		function buscadorArea(){
			buscardorEquipo = document.getElementById("buscardorEquipo").style.display;
			if(buscardorEquipo=='block'){
				document.getElementById("buscardorEquipo").style.display='none';
			}else{
				document.getElementById("buscardorEquipo").style.display='block';
			}
		}
	</script>
	<style>
		.sucFormTituloBoton{
			cursor: pointer;
			-webkit-user-select: none; /* Safari */
  			-ms-user-select: none; /* IE 10 and IE 11 */
  			user-select: none; /* Standard syntax */
		}
		.sucFormTituloBoton:hover{
			background-color:#b2e2f2;
		}
		.sucFormTituloBoton:active{
			background-color:#e3edfc;
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">

		<div class="sucForm" style="width: 100%"></div>
		<div class="sucFormTitulo sucFormTituloBoton" onclick="buscadorArea()"  >
			<label class="sucFormTituloBoton labelForm " id="labeltemaname">Buscador Equipos Disponible</label><br>
		</div>
		<div id="buscardorEquipo" style="background-color: none;display: block;">
			<div style="padding: 10px 0px 0px 0px;background-color: none">
				<?php include "filtros_equipos.php"; ?></div>
			<div style="clear: both;"></div>
			<div id="dataTable">
				<?php include "table_equipos.php"; ?>
			</div> 
		</div>


		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Equipo</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Id Equipo<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="id_equipo" autocomplete="off"  id="id_equipo" value="<?= $equipoDatos['id_equipo'] ?>" placeholder="Id Equipo" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $equipoDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="folio" autocomplete="off"  id="folio" value="<?= $equipoDatos['folio'] ?>" placeholder="Folio" /><br>
		</div>

		<div class="sucForm" style="width: 100%; background-color: #e3edfc;padding: 20px;">
			<h5>Datos Equipo</h5>
			<div id="busqueda_equipo">
				<div class="data_interior_left">
					Clave: <b id="busqueda_clave"></b><br>
					Folio: <b id="busqueda_folio"></b><br>
					Ubicación: <b id="busqueda_ubicacion"></b><br>
					Tipo Equipo: <b id="busqueda_tipo_equipo"></b><br>
					Marca: <b id="busqueda_marca"></b><br>
					Modelo: <b id="busqueda_modelo"></b><br>
					Memoria RAM(GB): <b id="busqueda_ram"></b><br>
					Procesador: <b id="busqueda_procesador"></b><br>
				</div>
			</div>
		</div>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<?php
			}
			?>
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div>
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>
	<script type="text/javascript">
		$(".myselect").select2();
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>