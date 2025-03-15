<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/plataformas.php";


	@session_start();
	$_SESSION['Paguinasub']="documentosOficialesCiudadanos/create.php";  
	$permiso="insert"; 
	$id_seccion_ine_ciudadano = $_SESSION['id_seccion_ine_ciudadano'];
	validar_plataforma_vista($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index',$codigo_plataforma);
	if($id_seccion_ine_ciudadano!=""){
		$id_seccion_ine_ciudadano;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		$id_seccion_ine = $seccion_ine_ciudadanoDatos['id_seccion_ine'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadano,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	

	unset($_SESSION['image_num']);
	unset($_SESSION['image']); 
?>
	<title>Create</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('documentosOficialesCiudadanos/index.php');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano ?>'; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
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

			var fecha_emision = document.getElementById("fecha_emision").value; 
			if(fecha_emision == ""){
				document.getElementById("fecha_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Emisión requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_vigencia = document.getElementById("fecha_vigencia").value;
			if(fecha_vigencia == ""){
				document.getElementById("fecha_vigencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Vigencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var documento_oficial = []; 
			var data = {    
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
					'tipo' : tipo,
					'fecha_emision' : fecha_emision,
					'fecha_vigencia' : fecha_vigencia,
				}
			documento_oficial.push(data);


			$.ajax({
				type: "POST",
				url: "documentosOficialesCiudadanos/db_add.php",
				data: {documento_oficial: documento_oficial},
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
						$("#homebody").load('documentosOficialesCiudadanos//index.php');
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
					<font style="font-size: 25px;">Crear Documento Oficial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para registrar y dar de alta a documento oficial.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>