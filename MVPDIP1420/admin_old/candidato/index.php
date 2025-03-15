<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/candidato.php";
	include '../functions/usuario_permisos.php';
	@session_start();
	$_SESSION['Paguinasub']="candidato/index.php";

	$candidatoDatos=candidatoDatos();
	if($candidatoDatos['id']!=""){
		$permiso="update";

		$disable_id_tipo_cartografia ='';
		if($candidatoDatos['tipo_cartografia']=='municipios'){
			include __DIR__."/../functions/municipios.php";
			$selectCartografia = municipios($candidatoDatos['id_tipo_cartografia']);
			$cartografia_texto = 'Municipio';
		}elseif($candidatoDatos['tipo_cartografia']=='secciones_ine'){
			include __DIR__."/../functions/secciones_ine.php";
			$selectCartografia = secciones_ine($candidatoDatos['id_tipo_cartografia']);
			$cartografia_texto = 'Sección INE';
		}elseif($candidatoDatos['tipo_cartografia']=='distritos_locales'){
			include __DIR__."/../functions/distritos_locales.php";
			$selectCartografia = distritos_locales($candidatoDatos['id_tipo_cartografia']);
			$cartografia_texto = 'Distrito Local';
		}
		elseif($candidatoDatos['tipo_cartografia']=='distritos_federales'){
			include __DIR__."/../functions/distritos_federales.php";
			$selectCartografia = distritos_federales($candidatoDatos['id_tipo_cartografia']);
			$cartografia_texto = 'Distrito Federal';
		}else{
			$selectCartografia='<option value="">Seleccione</option>';
			$disable_id_tipo_cartografia ='disabled="disabled"';
			$cartografia_texto = 'Cartografía';
		}

	}else{
		$permiso="insert";
		$selectCartografia='<option value="">Seleccione</option>';
		$disable_id_tipo_cartografia ='disabled="disabled"';
		$cartografia_texto = 'Cartografía';
	}
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','candidato',$_COOKIE["id_usuario"]);
	//var_dump($candidatoDatos);
	?>
	<title>Candidato</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load("setupLogistica/index.php");
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var nombre_completo = document.getElementById("nombre_completo").value; 
			if(nombre_completo == ""){
				document.getElementById("nombre_completo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre Completo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			 
			var descripcion_corta = document.getElementById("descripcion_corta").value; 
			if(descripcion_corta == ""){
				document.getElementById("descripcion_corta").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Descripción Corta requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var descripcion = document.getElementById("descripcion").value; 
			if(descripcion == ""){
				document.getElementById("descripcion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Descripción requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var latitud = document.getElementById("latitud").value; 
			if(latitud == ""){
				document.getElementById("latitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Latitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var longitud = document.getElementById("longitud").value; 
			if(longitud == ""){
				document.getElementById("longitud").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Longitud requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var direccion_completa = document.getElementById("direccion_completa").value; 
			if(direccion_completa == ""){
				document.getElementById("direccion_completa").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Dirección Completa requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var telefono = document.getElementById("telefono").value; 
			if(telefono == ""){
				document.getElementById("telefono").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Teléfono requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var whatsapp = document.getElementById("whatsapp").value; 
			if(whatsapp == ""){
				document.getElementById("whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var correo_electronico = document.getElementById("correo_electronico").value; 
			if(correo_electronico == ""){
				document.getElementById("correo_electronico").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Electronico Válido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_electronico)){
					document.getElementById("correo_electronico").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electronico Válido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			var whatsapp = document.getElementById("whatsapp").value; 
			if(whatsapp == ""){
				document.getElementById("whatsapp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Whatsapp requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var twitter = document.getElementById("twitter").value; 
			if(twitter == ""){
				document.getElementById("twitter").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Twitter requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var facebook = document.getElementById("facebook").value; 
			if(facebook == ""){
				document.getElementById("facebook").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Facebook requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var instagram = document.getElementById("instagram").value; 
			if(instagram == ""){
				document.getElementById("instagram").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Instagram requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var tipo_cartografia = document.getElementById("tipo_cartografia").value; 
			if(tipo_cartografia!=''){
				var id_tipo_cartografia = document.getElementById("id_tipo_cartografia").value; 
				if(id_tipo_cartografia == ""){
					document.getElementById("id_tipo_cartografia").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cartografia requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var tipo_cartografia = null;
				var id_tipo_cartografia = null;
			}
			var link_video = document.getElementById("link_video").value; 
			if(link_video == ""){
				document.getElementById("link_video").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Link Video requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var color_principal = document.getElementById("color_principal").value; 
			if(color_principal == ""){
				document.getElementById("color_principal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Color Principal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var color_secundario = document.getElementById("color_secundario").value; 
			if(color_secundario == ""){
				document.getElementById("color_secundario").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Color Secundario requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var candidato = []; 
			var data = {
					'nombre_completo' : nombre_completo,
					'descripcion_corta' : descripcion_corta,
					'latitud' : latitud,
					'longitud' : longitud,
					'direccion_completa' : direccion_completa,
					'telefono' : telefono,
					'descripcion' : descripcion,
					'whatsapp' : whatsapp,
					'correo_electronico' : correo_electronico,
					'twitter' : twitter,
					'facebook' : facebook,
					'instagram' : instagram,
					'tipo_cartografia' : tipo_cartografia,
					'id_tipo_cartografia' : id_tipo_cartografia,
					'link_video' : link_video,
					'color_principal' : color_principal,
					'color_secundario' : color_secundario,
				}
			candidato.push(data);
			$.ajax({
				type: "POST",
				url: "candidato/db_add_update.php",
				data: {candidato: candidato},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('candidato/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('candidato/index.php');
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
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
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
					<font style="font-size: 25px;">Candidato</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para candidato.</font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
