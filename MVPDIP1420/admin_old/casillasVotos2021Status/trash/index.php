<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/casillas_votos_2021.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include '../functions/usuario_permisos.php';


	@session_start();
	$_SESSION['Paguinasub']="casillasVotos2021Status/index.php";
	if(!empty($_GET['cot'])){
		$id_casilla_voto_2021=$_GET['cot'];
		$_SESSION['id_casilla_voto_2021']=$id_casilla_voto_2021;
	}else{
		$id_casilla_voto_2021=$_SESSION['id_casilla_voto_2021']; 
	}

	$_SESSION['Paguinasub']="casillasVotos2021Status/index.php";
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
	//var_dump($apiWhatsappDatos);
	?>
	<title>Casilla Voto Status</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load("setupLogistica/index.php");
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var id_casilla_voto_2021 = '<?= $id_casilla_voto_2021 ?>'; 
			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Status requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var fecha = document.getElementById("fecha").value; 
			if(fecha == ""){
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			if(!fechaValida(fecha)){ 
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var hora = document.getElementById("hora").value; 
			if(hora == ""){
				document.getElementById("hora").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var casillas_votos_2021_status = []; 
			var data = {
					'id_casilla_voto_2021' : id_casilla_voto_2021,
					'status' : status,
					'hora' : hora,
					'fecha' : fecha,
				}
			casillas_votos_2021_status.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2021Status/db_add_update.php",
				data: {casillas_votos_2021_status : casillas_votos_2021_status},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('casillasVotos2021Status/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('casillasVotos2021Status/index.php');
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> <br>
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
					<font style="font-size: 25px;">Status Casilla 2021</font>
				</label><br>
				<label class="tiempo_espera_segundosForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para status de la casilla 2021.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
