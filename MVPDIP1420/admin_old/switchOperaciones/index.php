<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/switch_operaciones.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="switchOperaciones/index.php";
	unset($_SESSION['paguinaId']);

	$switch_operacionesDatos=switch_operacionesDatos();
	if($switchOperacionesDatos['id']!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','switch_operaciones',$_COOKIE["id_usuario"]);
	//var_dump($switch_operacionesDatos);
	?>
	<title>Switch Operaciones</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			<?php
				if($_SESSION['dia_d']==1){
					echo '$("#homebody").load("setupDiaD/index.php");';
				}else{
					echo '$("#homebody").load("setupLogistica/index.php");';
				}
			?>
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var registro = document.getElementById("registro").value; 
			if(registro == ""){
				document.getElementById("registro").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Registro requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var evaluacion = document.getElementById("evaluacion").value; 
			if(evaluacion == ""){
				document.getElementById("evaluacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Evaluacion requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var mailing = document.getElementById("mailing").value; 
			if(mailing == ""){
				document.getElementById("mailing").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas Mailing requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var sms = document.getElementById("sms").value; 
			if(sms == ""){
				document.getElementById("sms").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas SMS requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var whatsapp = document.getElementById("whatsapp").value; 
			if(whatsapp == ""){
				document.getElementById("whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Campañas Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var entrega = document.getElementById("entrega").value; 
			if(entrega == ""){
				document.getElementById("entrega").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Entrega requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var recibe = document.getElementById("recibe").value; 
			if(recibe == ""){
				document.getElementById("recibe").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Recibe requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var casilla = document.getElementById("casilla").value; 
			if(casilla == ""){
				document.getElementById("casilla").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var usuarios = document.getElementById("usuarios").value; 
			if(usuarios == ""){
				document.getElementById("usuarios").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Usuarios requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			
			var switch_operaciones = []; 
			var data = {
					'registro' : registro,
					'evaluacion' : evaluacion,
					'entrega' : entrega,
					'recibe' : recibe,
					'casilla' : casilla,
					'usuarios' : usuarios,
					'mailing' : mailing,
					'sms' : sms,
					'whatsapp' : whatsapp,
				}
			switch_operaciones.push(data);
			$.ajax({
				type: "POST",
				url: "switchOperaciones/db_add_update.php",
				data: {switch_operaciones: switch_operaciones},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('switchOperaciones/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('switchOperaciones/index.php');
						}else{
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
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
		<?php
			if($_SESSION['dia_d']==1){
				echo '<div class="submenux" onclick="subConfiguracionDiaD()">Día D</div> / ';
			}else{
				echo '<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / ';
			}
		?>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
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
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Switch de Operaciones</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para switch operaciones.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
