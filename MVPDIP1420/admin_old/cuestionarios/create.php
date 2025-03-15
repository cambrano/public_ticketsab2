<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	@session_start();
	$_SESSION['Paguinasub']="cuestionarios/create.php";
	unset($_SESSION['paguinaId']);
	$permiso="insert"; 

	$id_encuesta = $_SESSION['id_encuesta']; 
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

	unset($_SESSION['respuesta_num']);
	unset($_SESSION['respuesta']); 
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('cuestionarios/index.php');
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
				$("#mensaje").html("Clave clave");
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
				data: {pregunta: pregunta},
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
						$("#homebody").load('cuestionarios//index.php');
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