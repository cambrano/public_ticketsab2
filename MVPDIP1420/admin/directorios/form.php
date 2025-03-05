<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','directorios',$_COOKIE["id_usuario"]);
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
		function subdependencias(valor){
			id_dependencia = valor;
			if(id_dependencia == ""){
				document.getElementById("id_dependencia").style.border= "1px solid red";
				document.getElementById("id_dependencia").style.border= "";
				document.getElementById("id_dependencia").value="";
				var dataString = 'id_dependencia=x&tipo=list_select';
				$.ajax({
					type: "POST",
					url: "subDependencias/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_sub_dependencia").html(data);
					}
				});
			}else{
				document.getElementById("id_dependencia").style.border= "";
				var dataString = 'id_dependencia='+id_dependencia+'&tipo=list_select';
				$.ajax({
					type: "POST",
					url: "subDependencias/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_sub_dependencia").html(data);
					}
				});
			}
		}
		$( function() {
			$( "#fecha_nacimiento" ).datepicker({
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				yearRange: "1890:2010",
				defaultDate: "2006-01-01",
				onSelect: function (date) { 
					document.getElementById("fecha_nacimiento").style.border= "";
				}
			});
		});
		
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Directorio Institucional</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $directorioDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Ubicación<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_ubicacion">
				<?php
				echo ubicaciones($directorioDatos['id_ubicacion']);
				?>
			</select><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Dependencia</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Dependencia<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_dependencia" onchange="subdependencias(this.value)">
				<?php
				echo dependencias($directorioDatos['id_dependencia']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sub Dependencia<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_sub_dependencia">
				<?php
				echo sub_dependencias($directorioDatos['id_sub_dependencia'],$directorioDatos['id_dependencia'],'');
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Área<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="area" autocomplete="off"  id="area" value="<?= $directorioDatos['area'] ?>" placeholder="Área" /><br>
		</div>
		<div class="sucForm" style="width:100%" >
			<label class="labelForm" id="labeltemaname">Puesto<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="puesto" autocomplete="off"  id="puesto" value="<?= $directorioDatos['puesto'] ?>" placeholder="Puesto" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Generales</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Titulo Personal<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_titulo_personal">
				<?php
				echo titulos_personales($directorioDatos['id_titulo_personal']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width:100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $directorioDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>
		<div class="sucForm" style="width:100%">
			<label class="labelForm" id="labeltemaname">Apellido Paterno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="apellido_paterno" autocomplete="off"  id="apellido_paterno" value="<?= $directorioDatos['apellido_paterno'] ?>" placeholder="Apellido Paterno" /><br>
		</div>
		<div class="sucForm" style="width:100%">
			<label class="labelForm" id="labeltemaname">Apellido Materno<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="apellido_materno" autocomplete="off"  id="apellido_materno" value="<?= $directorioDatos['apellido_materno'] ?>" placeholder="Apellido Materno" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Sexo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_sexo">
				<?php
				echo sexos($directorioDatos['id_sexo']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Nacimiento<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_nacimiento" autocomplete="off"  id="fecha_nacimiento" value="<?= $directorioDatos['fecha_nacimiento'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Correo Eletrónico<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="correo_electronico" autocomplete="off"  id="correo_electronico" value="<?= $directorioDatos['correo_electronico'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Whatsapp<font color="#FF0004">*</font></label>(Solo Numero 10 Digitos)<br>
			<input class="inputlogin" type="text" pattern="[0-9]*" maxlength="10"  name="whatsapp" autocomplete="off"  id="whatsapp" value="<?= $directorioDatos['whatsapp'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Teléfono</label><br>
			<input class="inputlogin" type="text" name="telefono" autocomplete="off"  id="telefono" value="<?= $directorioDatos['telefono'] ?>" placeholder="9992154554" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Extencion</label><br>
			<input class="inputlogin" type="text" name="telefono_ext" autocomplete="off"  id="telefono_ext" value="<?= $directorioDatos['telefono_ext'] ?>" placeholder="10490" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Celular</label>(Solo Numero 10 Digitos, Si no tiene dejar en blanco)<br>
			<input class="inputlogin" type="text" pattern="[0-9]*" name="celular" autocomplete="off"  id="celular" value="<?= $directorioDatos['celular'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Observaciones</label><br>
				<textarea id="observaciones" style="width: 99%;height: 150px"><?= $directorioDatos['observaciones'] ?></textarea> <br>
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
	<script type="text/javascript">
		$(".myselect").select2();
	</script>