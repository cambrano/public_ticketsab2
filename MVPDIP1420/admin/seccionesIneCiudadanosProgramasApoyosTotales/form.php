<?php
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_programas_apoyos',$_COOKIE["id_usuario"]);
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
			document.getElementById("mensaje_ine_disponible").classList.remove("mensajeSucces");
			document.getElementById("mensaje_ine_disponible").classList.remove("mensajeError");
			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus();
				return false;
			}
			var id_programa_apoyo = '<?= $id_programa_apoyo ?>';
			if(id_programa_apoyo == ""){
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var dataString = 'clave_elector='+clave_elector+'&id_programa_apoyo='+id_programa_apoyo;
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyosTotales/search_clave_elector.php",
				data: dataString,
				dataType: 'json',
				success: function(data) {
					console.log(data);
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
					$("#ine_curp").html(data.curp);

					if(data.contador==0){
						if(data.status_verificacion==false){
							$("#mensaje_ine_disponible").html("&nbsp;");
							document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
							$("#mensaje_ine_disponible").html("Este ciudadano no esta registrado en la plataforma."); 
							document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
						}else{
							$("#mensaje_ine_disponible").html("&nbsp;");
							document.getElementById("mensaje_ine_disponible").classList.add("mensajeSucces");
							$("#mensaje_ine_disponible").html("Este ciudadano no cuenta con este programa.");
							document.getElementById("mensaje_ine_disponible").classList.add("mensajeSucces");
						}
					}else{
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
						$("#mensaje_ine_disponible").html("Este ciudadano ya esta registrado en este programa.");
						document.getElementById("mensaje_ine_disponible").classList.add("mensajeError");
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
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Buscador Padron Ciudadano</label>
		</div>
		<div style="background-color: none;display: block;">
			<div style="padding: 10px 0px 0px 0px;background-color: none">
				<?php include "filtros_secciones_ine_ciudadanos.php"; ?></div>
			<div style="clear: both;"></div>
			<div id="dataTable">
				<?php //include "table_secciones_ine_ciudadanos.php"; ?>
			</div> 
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Beneficiario</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave Electoral<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text"  name="clave_elector" autocomplete="off"  id="clave_elector" value="<?= $seccion_ine_ciudadano_programa_apoyoDatos['clave_elector'] ?>" placeholder="" onblur="aMays(event, this)" size="40" maxlength="18"/><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<input type="button" onclick="buscar_clave_electoral()" value="Buscar Datos INE">
		</div>
		<div class="sucForm" style="width: 100%; background-color: #e3edfc;padding: 20px;">
			<h5>Datos INE</h5>
			<div id="busqueda_clave_electoral">
				<div id="mensaje_ine_disponible"></div>
				<div class="data_interior_left">
					C.U.R.P: <b id="ine_curp"><?= $row['curp'] =='' ? 'No Encontrado' : $row['curp'] ?></b><br>
					Nombre: <b id="ine_nombre"><?= $row['nombre'] =='' ? 'No Encontrado' : $row['nombre'] ?></b><br>
					Apellido Paterno: <b id="ine_apellido_paterno"><?= $row['apellido_paterno'] =='' ? 'No Encontrado' : $row['apellido_paterno'] ?></b><br>
					Apellido Materno: <b id="ine_apellido_materno"><?= $row['apellido_materno'] =='' ? 'No Encontrado' : $row['apellido_materno'] ?></b><br>
					Fecha Nacimiento: <b id="ine_fecha_nacimiento"><?= $row['fecha_nacimiento'] =='' ? 'No Encontrado' : $row['fecha_nacimiento'] ?></b><br>
					Sexo: <b id="ine_sexo"><?= $row['sexo'] =='' ? 'No Encontrado' : $row['sexo'] ?></b><br>
					Sección: <b id="ine_seccion"><?= $row['seccion'] =='' ? 'No Encontrado' : $row['seccion'] ?></b><br>
					Manzana: <b id="ine_manzana"><?= $row['manzana'] =='' ? 'No Encontrado' : $row['manzana'] ?></b><br>
				</div>
				<div class="data_interior">
					Calle: <b id="ine_calle"><?= $row['calle'] =='' ? 'No Encontrado' : $row['calle'] ?></b><br>
					No. Exterior: <b id="ine_no_exterior"><?= $row['no_exterior'] =='' ? 'No Encontrado' : $row['no_exterior'] ?></b><br>
					No. Interior: <b id="ine_no_interior"><?= $row['no_interior'] =='' ? 'No Encontrado' : $row['no_interior'] ?></b><br>
					Colonia: <b id="ine_colonia"><?= $row['colonia'] =='' ? 'No Encontrado' : $row['colonia'] ?></b><br>
					Código Postal: <b id="ine_codigo_postal"><?= $row['codigo_postal'] =='' ? 'No Encontrado' : $row['codigo_postal'] ?></b><br>
				</div>
			</div>
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

			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
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