<?php
	 
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_grupos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			$("#homebody").load('home.php');
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
			$( "#fecha_inicio" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_inicio").style.border= "";
				}
			});
			$( "#fecha_final" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_final").style.border= "";
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
			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus();
				return false;
			}
			var dataString = 'search_clave_elector='+clave_elector;
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosGrupos/search_clave_elector.php",
				data: dataString,
				dataType: 'json',
				success: function(data) {
					//console.log(data);
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
					document.getElementById("correo_electronico").value = data.correo_electronico;
					document.getElementById("whatsapp").value = data.whatsapp;
					document.getElementById("telefono").value = data.telefono;
					document.getElementById("celular").value = data.celular;

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
			<label class="labelForm" id="labeltemaname">Datos Miembro</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="clave" autocomplete="off" <?= $claveF['input'] ?> id="clave" value="<?= $seccion_ine_ciudadano_grupoDatos['clave'] ?>" onkeyup="clave(this.value)" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $seccion_ine_ciudadano_grupoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>


		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha" autocomplete="off"  id="fecha" value="<?= $seccion_ine_ciudadano_grupoDatos['fecha'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora" autocomplete="off"  id="hora" value="<?= $seccion_ine_ciudadano_grupoDatos['hora'] ?>" placeholder="" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Tipo de Miembro</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Grupo<font color="#FF0004">*</font></label><br>
			<select id="id_seccion_ine_grupo" class="myselect" name="id_seccion_ine_grupo" >
				<?php
				echo secciones_ine_grupos($seccion_ine_ciudadano_grupoDatos['id_seccion_ine_grupo']);
				?>
			</select><br><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Nombramiento<font color="#FF0004">*</font></label><br>
			<select id="id_tipo_nombramiento" class="myselect" name="id_tipo_nombramiento" >
				<?php
				echo tipos_nombramientos($seccion_ine_ciudadano_grupoDatos['id_tipo_nombramiento']);
				?>
			</select><br><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Inicio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_inicio" autocomplete="off"  id="fecha_inicio" value="<?= $seccion_ine_ciudadano_grupoDatos['fecha_inicio'] ?>" placeholder="" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Final<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_final" autocomplete="off"  id="fecha_final" value="<?= $seccion_ine_ciudadano_grupoDatos['fecha_final'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Ciudadano</label><br>
		</div>

		<div style="background-color: none;display: inline-block;">
			<div style="padding: 10px 0px 0px 0px;background-color: none">
				<?php include "filtros_secciones_ine_ciudadanos.php"; ?></div>
			<div style="clear: both;"></div>
			<div id="dataTable">
				<?php include "table_secciones_ine_ciudadanos.php"; ?>
			</div> 
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo Eletrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $seccion_ine_ciudadano_grupoDatos['correo_electronico'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label>(Solo Numero 10 Digitos)<br>
			<input class="inputlogin" type="text" name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $seccion_ine_ciudadano_grupoDatos['whatsapp'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $seccion_ine_ciudadano_grupoDatos['telefono'] ?>" placeholder="9992154554 ext 10" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label>(Solo Numero 10 Digitos, Si no tiene dejar en blanco)<br>
			<input class="inputlogin" type="text" name="celular" autocomplete="off"  id="celular" value="<?= $seccion_ine_ciudadano_grupoDatos['celular'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Observaciones</label><br>
			<textarea id="observaciones" style="width: 99%;height: 150px"><?= $seccion_ine_ciudadano_grupoDatos['observaciones'] ?></textarea> <br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<select id="status" class="myselect" name="status" >
				<?php
				echo statusGeneralForm($seccion_ine_ciudadano_grupoDatos['status']);
				?>
			</select><br><br>
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