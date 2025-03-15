<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','encuestas',$_COOKIE["id_usuario"]);
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
	<script language="javascript" type="text/javascript">
		function guardarRespuesta(metodo) {
			document.getElementById("sumbmitRespuesta").disabled = true;
			document.getElementById("mensajeRespuesta").classList.remove("mensajeSucces");
			document.getElementById("mensajeRespuesta").classList.remove("mensajeError");

			var respuesta_clave = document.getElementById("respuesta_clave").value; 
			if(respuesta_clave == ""){
				document.getElementById("respuesta_clave").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Clave requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}

			var respuesta_orden = document.getElementById("respuesta_orden").value; 
			if(respuesta_orden == ""){
				document.getElementById("respuesta_orden").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Orden requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}

			var respuesta_respuesta = document.getElementById("respuesta_respuesta").value; 
			if(respuesta_respuesta == ""){
				document.getElementById("respuesta_respuesta").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Respuesta requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}
			var new_orden = parseInt(respuesta_orden) + 1;

			var respuesta = []; 
			var data = {    
					'num' : '',
					'clave' : respuesta_clave,
					'orden' : respuesta_orden,
					'respuesta' : respuesta_respuesta,
				}
			respuesta.push(data);

			$.ajax({
				type: "POST",
				url: "cuestionarios/respuestaSession.php",
				data: {respuesta: respuesta},
				success: function(data) {
					var caracteres = "ABCDEFGHJKMNPQRTUVWXYZ2346789";
					var clave = "";
					for (i=0; i<6; i++) clave +=caracteres.charAt(Math.floor(Math.random()*caracteres.length)); 
					document.getElementById("mensajeRespuesta").classList.add("mensajeSolo");
					$("#mensajeRespuesta").html("");
					document.getElementById("sumbmitRespuesta").disabled = false;
					$("#imageList").html(data);
					document.getElementById("respuesta_clave").value =' RESP-'+clave+(Math.trunc(Date.now()/ 1000));
					document.getElementById("respuesta_orden").value = new_orden;
					document.getElementById("respuesta_respuesta").value = "";
					document.getElementById("respuesta_num").value = "";
				}
			});
		}
		function editarRespuesta(metodo) {
			document.getElementById("sumbmitRespuesta").disabled = true;
			document.getElementById("mensajeRespuesta").classList.remove("mensajeSucces");
			document.getElementById("mensajeRespuesta").classList.remove("mensajeError");

			var respuesta_num = document.getElementById("respuesta_num").value; 
			if(respuesta_num == ""){
				document.getElementById("respuesta_num").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Numero Respuesta requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}

			var respuesta_clave = document.getElementById("respuesta_clave").value; 
			if(respuesta_clave == ""){
				document.getElementById("respuesta_clave").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Clave requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}

			var respuesta_orden = document.getElementById("respuesta_orden").value; 
			if(respuesta_orden == ""){
				document.getElementById("respuesta_orden").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Orden requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}

			var respuesta_respuesta = document.getElementById("respuesta_respuesta").value; 
			if(respuesta_respuesta == ""){
				document.getElementById("respuesta_respuesta").focus(); 
				document.getElementById("sumbmitRespuesta").disabled = false;
				$("#mensajeRespuesta").html("Respuesta requerido");
				document.getElementById("mensajeRespuesta").classList.add("mensajeError");
				return false;
			}
			var new_orden = parseInt(respuesta_orden) + 1;

			var respuesta = []; 
			var data = {    
					'num' : respuesta_num,
					'clave' : respuesta_clave,
					'orden' : respuesta_orden,
					'respuesta' : respuesta_respuesta,
					'update' : 'update',
				}
			respuesta.push(data);

			$.ajax({
				type: "POST",
				url: "cuestionarios/respuestaSession.php",
				data: {respuesta: respuesta},
				success: function(data) {
					var caracteres = "ABCDEFGHJKMNPQRTUVWXYZ2346789";
					var clave = "";
					for (i=0; i<6; i++) clave +=caracteres.charAt(Math.floor(Math.random()*caracteres.length)); 
					document.getElementById("mensajeRespuesta").classList.add("mensajeSolo");
					$("#mensajeRespuesta").html("");
					document.getElementById("sumbmitRespuesta").disabled = false;
					$("#imageList").html(data);
					document.getElementById("respuesta_clave").value ='RESP-'+clave+(Math.trunc(Date.now()/ 1000));
					document.getElementById("respuesta_orden").value = new_orden;
					document.getElementById("respuesta_respuesta").value = "";
					document.getElementById("respuesta_num").value = "";
					alert(1);

					document.getElementById('sumbmitRespuesta').setAttribute('onclick','guardarRespuesta("mas")');
					document.getElementById("sumbmitRespuesta").value = "Crear Respuesta";
				}
			});
		}

		function eliminarRespuesta(value){
			var respuesta = []; 
			var data = {    
					'num' : value,
					'update' : '',
				}
			respuesta.push(data);
			$.ajax({
				type: "POST",
				url: "cuestionarios/respuestaSession.php",
				data: {respuesta: respuesta},
				success: function(data) {
					document.getElementById("mensajeRespuesta").classList.add("mensajeSolo");
					document.getElementById("sumbmitRespuesta").disabled = false;
					$("#imageList").html(data);
				}
			});
		}
		function editarRespuestaNumero(value){
			var formData = new FormData($("#form")[0]);
			formData.append('num', value);
			var ruta = "cuestionarios/formRespuesta.php";
			$.ajax({
				url: ruta,
				type: "POST",
				data: formData, 
				contentType: false,
				processData: false,
				success: function(data){ 
					document.getElementById("mensajeRespuesta").classList.add("mensajeSolo");
					document.getElementById("sumbmitRespuesta").disabled = false;
					$("#form_imagen").html(data);
					//$("#logo").html("");
				}
			});
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Pregunta</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $cuestionarioDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="orden" autocomplete="off"  id="orden" value="<?= $cuestionarioDatos['orden'] ?>" placeholder="" onkeypress="return CheckNumeric()" maxlength="4" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">No.Válidos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="cantidad" autocomplete="off"  id="cantidad" value="<?= $cuestionarioDatos['cantidad'] ?>" placeholder="" onkeypress="return CheckNumeric()" maxlength="4"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option <?= $select['multiple'] ?> value="multiple">Opcion Multiple</option>
				<option <?= $select['abierto'] ?> value="abierto">Abierto</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Campo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['campo']] = 'selected="selected"';
			?>
			<select name="campo" id="campo" class='myselect'>  
				<option <?= $select[0] ?> value="">Seleccione</option>
				<option <?= $select['text'] ?> value="text">Texto</option>
				<option <?= $select['checkbox'] ?> value="checkbox">Checkbox &#x2611;</option>
				<option <?= $select['radio'] ?> value="radio">Radio  &#x26ac;</option>
			</select>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Requerido<font color="#FF0004">*</font></label><br>
			<?php
				$select[$cuestionarioDatos['requerido']] = 'selected="selected"';
			?>
			<select name="requerido" id="requerido" class='myselect'>  
				<option <?= $select[1] ?> value="1">SI</option>
				<option <?= $select[0] ?> value="0">No</option>
			</select>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Pregunta<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" type="text" name="pregunta" autocomplete="off"  id="pregunta" value="<?= $cuestionarioDatos['pregunta'] ?>" placeholder="En comparación con el año anterior,¿La situación de su familia está mejor, peor o igual?" maxlength="255" /><br><br>
		</div>
		<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Respuesta(s)</label>
		</div>

		<div id="form_imagen">
			<?php
			include "formRespuesta.php";
			?>
		</div>
		<br>
		<div id="imageList" class="mensajeSolo" >
			<?php
				include "respuestaSession.php";
			?>
		</div>
		<br>


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