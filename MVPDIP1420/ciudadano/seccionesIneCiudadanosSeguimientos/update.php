<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_seguimientos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id),time()+(60*60*24*650),"/",false);
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	$redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_seguimientos','seccionesIneCiudadanosSeguimientos','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_seccion_ine_ciudadanox!=""){
		$id_seccion_ine_ciudadanox;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadanox);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadanox,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$id_seccion_ine_ciudadano;

	$seccion_ine_ciudadano_seguimientoDatos=seccion_ine_ciudadano_seguimientoDatos($id,$id_seccion_ine_ciudadanox);

	if($seccion_ine_ciudadano_seguimientoDatos['medio_registro'] =="2" ){
		$mensaje_medio = '<div class="mensajeDark" >El seguimiento fue creado por el sistema, a '.number_format($seccion_ine_ciudadano_seguimientoDatos['distancia_m'],2,'.',',').' m de su casa tiene una discrepancia de 100m. El punto verde representa donde se registro el seguimiento y el rojo donde se dijo que se registro el seguimiento.</div>';
	}

	if($seccion_ine_ciudadano_seguimientoDatos['distancia_alert'] =="1" ){
		$mensaje_medio = '<div class="mensajeError" >El ciudadano fue creado a '.number_format($seccion_ine_ciudadano_seguimientoDatos['distancia_m'],2,'.',',').' m de su casa tiene una discrepancia de 100m. El punto verde representa donde se registro el seguimiento y el rojo donde se dijo que se registro el seguimiento.</div>';
	}

	if($seccion_ine_ciudadano_seguimientoDatos['longitud']!=''){
		$longitud=$seccion_ine_ciudadano_seguimientoDatos['longitud'];
		$latitud=$seccion_ine_ciudadano_seguimientoDatos['latitud'];
	}
	$zoom="18";

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			link="seccionesIneCiudadanosSeguimientos/index.php?cot=<?= $id_seccion_ine_ciudadanox ?>"; 
			var link2="seccionesIneCiudadanosSeguimientos/index.php";
			dataString = 'urlink='+link2;  
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
			$("#homebody").load(link);
		}

		function guardar() {
			var coma= /,/g;
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadanox ?>'; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine = '<?= $id_seccion_ine ?>'; 
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar la sección en el sistema requerido");
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

			var asunto = document.getElementById("asunto").value;
			if(asunto == ""){
				document.getElementById("asunto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Asunto requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			

			var observaciones = document.getElementById("observaciones").value;
			if(observaciones == ""){
				document.getElementById("observaciones").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Observaciones requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var longitud = document.getElementById("longitud").value;
			var latitud = document.getElementById("latitud").value;

			var longitud_r = "<?= $seccion_ine_ciudadano_seguimientoDatos['longitud_r'] ?>";
			var latitud_r = "<?= $seccion_ine_ciudadano_seguimientoDatos['latitud_r'] ?>";


			var seccion_ine_ciudadano_seguimiento = []; 
			var data = {    
					'id' : id,
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
					'id_seccion_ine' : id_seccion_ine,
					'fecha' : fecha,
					'hora' : hora,
					'observaciones' : observaciones,
					'latitud' : latitud,
					'longitud' : longitud,
					'latitud_r' : latitud_r,
					'longitud_r' : longitud_r,
					'asunto' : asunto,
				}
			seccion_ine_ciudadano_seguimiento.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosSeguimientos/db_edit.php",
				data: {seccion_ine_ciudadano_seguimiento: seccion_ine_ciudadano_seguimiento},
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
						link="seccionesIneCiudadanosSeguimientos/index.php?cot=<?= $id_seccion_ine_ciudadanox ?>"; 
						var link2="seccionesIneCiudadanosSeguimientos/index.php";
						dataString = 'urlink='+link2;
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) {}
						});
						//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
						$("#homebody").load(link);
					}else{
						if(data==""){
							link="seccionesIneCiudadanosSeguimientos/index.php?cot=<?= $id_seccion_ine_ciudadanox ?>"; 
							var link2="seccionesIneCiudadanosSeguimientos/index.php";
							dataString = 'urlink='+link2;
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) {}
							});
							//window.open(link,'AsignarEmpleado','width=480, height=350'); return false;
							$("#homebody").load(link);
						}else{
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							
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
					<font style="font-size: 25px;">Modificar Seguimiento</font>
				</label><br>
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar seguimiento.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>