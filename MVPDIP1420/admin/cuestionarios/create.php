<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$permiso="insert"; 
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_encuesta!=""){
		echo $redirectSecurity=redirectSecurity($id_encuesta,'encuestas','encuestas','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_encuesta,'encuestas','encuestas','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$cuestionarioDatos['clave']="PRE-".$cod16M;
	$claveF['input'] = 'disabled="disabled"';
	$cuestionarioDatos['cantidad'] = 1;
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="cuestionarios/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_encuesta = '<?= $id_encuesta ?>'; 
			if(id_encuesta == ""){
				document.getElementById("id_encuesta").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var orden = document.getElementById("orden").value; 
			if(orden == ""){
				document.getElementById("orden").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Orden requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var cantidad = document.getElementById("cantidad").value; 
			if(cantidad == ""){
				document.getElementById("cantidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("No. Válidos requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var tipo = document.getElementById("tipo").value; 
			if(tipo == ""){
				document.getElementById("tipo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var campo = document.getElementById("campo").value; 
			if(campo == ""){
				document.getElementById("campo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			if (tipo == 'abierto' && campo != 'text') {
				document.getElementById("campo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campo debe ser texto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if (tipo != 'abierto' && campo == 'text') {
				document.getElementById("campo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campo debe ser texto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var requerido = document.getElementById("requerido").value; 
			if(requerido == ""){
				document.getElementById("requerido").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Requerido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var pregunta_input = document.getElementById("pregunta").value; 
			if(pregunta_input == ""){
				document.getElementById("pregunta").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Pregunta pregunta");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			if (tipo == 'abierto' && campo == 'text') {
				var respuestas = [];
			}else{
				var dataTable = $('#respuestan-tabla').DataTable();
				// Obtener todos los datos de la tabla
				var rows = dataTable.rows().data().toArray();
				var respuestas = [];
				rows.forEach(element => {
					var data = {    
						'id_respuesta' : element[1],
						'clave' : element[2],
						'orden' : element[0],
						'respuesta' : element[3],
					}
					respuestas.push(data);
				});

				if (respuestas.length == 0) {
					document.getElementById("sumbmit").disabled = false;
					$("#mensajeRespuesta").html("Respuetas requeridas");
					document.getElementById("mensajeRespuesta").classList.add("mensajeError");
					return false;
				}
			}

			var pregunta = []; 
			var data = {    
					'id_encuesta' : id_encuesta,
					'clave' : clave,
					'orden' : orden,
					'cantidad' : cantidad,
					'tipo' : tipo,
					'campo' : campo,
					'requerido' : requerido,
					'pregunta' : pregunta_input,
				}
			pregunta.push(data);


			$.ajax({
				type: "POST",
				url: "cuestionarios/db_add.php",
				data: {pregunta: pregunta,respuestas:respuestas},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="cuestionarios/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
					}
				}
			});
		} 
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
			$("#mensajeRespuesta").click(function(event) { 
				document.getElementById("mensajeRespuesta").classList.remove("mensajeSucces");
				document.getElementById("mensajeRespuesta").classList.remove("mensajeError");
				$("#mensajeRespuesta").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Crear Pregunta</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta la pregunta.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>