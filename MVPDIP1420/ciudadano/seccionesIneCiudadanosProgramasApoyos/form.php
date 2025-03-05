<?php
	 
	include '../functions/switch_operaciones.php';
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['evaluacion']==false){
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
	<script type="text/javascript">
		$( function() {
			$( "#fecha" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha").style.border= "";
				}
			});
			$('#hora').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora").style.border= "";
				}
			});
		});
		function buscar_clave_electoral(){
			var clave_elector = "<?= $seccion_ine_ciudadanoDatos['clave_elector'] ?>";
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus();
				return false;
			}

			var id_programa_apoyo = document.getElementById("id_programa_apoyo").value; 
			if(id_programa_apoyo == ""){
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var dataString = 'clave_elector='+clave_elector+'&id_programa_apoyo='+id_programa_apoyo;
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyos/search_clave_elector.php",
				data: dataString,
				dataType: 'json',
				success: function(data) {
					//console.log(data);
					/*
					$("#ine_nombre").html(data.nombre);
					$("#ine_apellido_paterno").html(data.apellido_paterno);
					$("#ine_apellido_materno").html(data.apellido_materno);
					$("#ine_fecha_nacimiento").html(data.fecha_nacimiento);
					$("#ine_sexo").html(data.sexo);
					$("#ine_seccion").html(data.seccion);
					$("#ine_manzana").html(data.manzana);
					$("#ine_calle").html(data.calle);
					$("#ine_no_exterior").html(data.no_exterior);
					$("#ine_no_interior").html(data.no_interior);
					$("#ine_colonia").html(data.colonia);
					$("#ine_codigo_postal").html(data.codigo_postal);
					$("#ine_ocr").html(data.ocr);
					*/
					if(data.contador==0){
						$("#mensaje_ine_disponible").html("&nbsp;");
						document.getElementById("mensaje_ine_disponible").classList.remove("mensajeError");
						$("#mensaje_ine_disponible").html("Este ciudadano no registrado."); 
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeSucces");
					}else{
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
						$("#mensaje_ine_disponible").html("Este ciudadano ya esta registrado.");
					}

				}
			});
		}
	</script>
	<style type="text/css">
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.data_interior{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
		}
		.data_interior_left{
			width: 50%;
			float: left;
			padding-left: 10px;
			padding-right: 10px;
			color: #191919;
			border-right: 1px solid #191919;
		}
		@media only screen and (max-width:1600px) {
			.data_interior{
				width: 100%;
			}
			.data_interior_left{
				border-right: none;
			}
		}
	</style>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div id="mensaje_ine_disponible"></div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Programa Apoyo</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $seccion_ine_ciudadano_programa_apoyoDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $seccion_ine_ciudadano_programa_apoyoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Programas Apoyos<font color="#FF0004">*</font></label><br>
			<select id="id_programa_apoyo" onchange="buscar_clave_electoral();">
				<?php echo programas_apoyos($seccion_ine_ciudadano_programa_apoyoDatos['id_programa_apoyo']); ?>
			</select>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_ciudadano_programa_apoyoDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_ciudadano_programa_apoyoDatos['hora'] ?>" placeholder="" /><br>
		</div>


		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_ciudadano_programa_apoyoDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucForm" style="width: 100%" >
			<br>
			<?php

			if($switch_operacionesPermisos){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>