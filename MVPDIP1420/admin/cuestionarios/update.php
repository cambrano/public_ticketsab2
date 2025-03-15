<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php"; 
	include __DIR__."/../functions/cuestionarios.php";
	include __DIR__."/../functions/cuestionarios_respuestas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}

	$redirectSecurity=redirectSecurity($id,'cuestionarios','cuestionarios','index');
	if($redirectSecurity!=""){
		die;
	}

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
	$cuestionarioDatos=cuestionarioDatos($id);
	$cuestionario_respuestasDatos=cuestionario_respuestasDatos('',$id,$id_encuesta);
	$permiso="update"; 
?>
	<title>Update</title>
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
			
			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

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
				var data_table = dataTable.rows().data().toArray();
				var respuestas_nuevas = [];
				var respuestas_registradas = [];
				data_table.forEach(element => {
					if(element[1]==""){
						var data = {    
							'id_respuesta' : element[1],
							'clave' : element[2],
							'orden' : element[0],
							'respuesta' : element[3],
						}
						respuestas_nuevas.push(data);
					}else{
						var data = {    
							'id_respuesta' : element[1],
							'clave' : element[2],
							'orden' : element[0],
							'respuesta' : element[3],
						}
						respuestas_registradas[element[1]] = data ;
					}
				});

				if (respuestas_nuevas.length == 0 && respuestas_registradas.length == 0  ) {
					document.getElementById("sumbmit").disabled = false;
					$("#mensajeRespuesta").html("Respuetas requeridas");
					document.getElementById("mensajeRespuesta").classList.add("mensajeError");
					return false;
				}
			}

			var pregunta = []; 
			var data_pregunta = { 
					'id' : id,   
					'id_encuesta' : id_encuesta,
					'clave' : clave,
					'orden' : orden,
					'cantidad' : cantidad,
					'tipo' : tipo,
					'campo' : campo,
					'requerido' : requerido,
					'pregunta' : pregunta_input,
				}
			pregunta.push(data_pregunta);

			$.ajax({
				type: "POST",
				url: "cuestionarios/db_edit.php",
				data: {pregunta: pregunta,respuestas_nuevas:respuestas_nuevas,respuestas_registradas:respuestas_registradas},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
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
						if(data==""){
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
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
						}
						
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
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Pregunta</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a pregunta.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>