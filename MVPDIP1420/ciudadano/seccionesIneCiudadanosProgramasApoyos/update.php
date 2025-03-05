<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_programas_apoyos.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id),time()+(60*60*24*650),"/",false);
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	$redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_programas_apoyos','seccionesIneCiudadanosProgramasApoyos','index');
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

	$seccion_ine_ciudadano_programa_apoyoDatos=seccion_ine_ciudadano_programa_apoyoDatos($id,$id_seccion_ine_ciudadano);


	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			link="seccionesIneCiudadanosProgramasApoyos/index.php?cot=<?= $id_seccion_ine_ciudadano ?>"; 
			var link2="seccionesIneCiudadanosProgramasApoyos/index.php";
			dataString = 'urlink='+link2+'&id=<?= $id_seccion_ine_ciudadano ?>';  
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var folio = document.getElementById("folio").value; 
			if(folio == ""){
				document.getElementById("folio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Folio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine_ciudadano = '<?= $id_seccion_ine_ciudadano ?>'; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("id_seccion_ine_ciudadano").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe Seleccionar un ciudadano en el sistema requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_programa_apoyo = document.getElementById("id_programa_apoyo").value;
			if(id_programa_apoyo == ""){
				document.getElementById("id_programa_apoyo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Programa Apoyo requerido");
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

			var observaciones = document.getElementById("observaciones").value;
			if(observaciones == ""){
				document.getElementById("observaciones").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Observaciones requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var seccion_ine_ciudadano_programa_apoyo = []; 
			var data = {    
					'id' : id,
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
					'id_programa_apoyo' : id_programa_apoyo,
					'clave' : clave,
					'folio' : folio,
					'fecha' : fecha,
					'hora' : hora,
					'observaciones' : observaciones,
				}
			seccion_ine_ciudadano_programa_apoyo.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosProgramasApoyos/db_edit.php",
				data: {seccion_ine_ciudadano_programa_apoyo: seccion_ine_ciudadano_programa_apoyo},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						link="seccionesIneCiudadanosProgramasApoyos/index.php?cot=<?= $id_seccion_ine_ciudadano ?>"; 
							var link2="seccionesIneCiudadanosProgramasApoyos/index.php";
							dataString = 'urlink='+link2+'&id=<?= $id_seccion_ine_ciudadano ?>';  
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
							$("#homebody").load('seccionesIneCiudadanosProgramasApoyos/index.php');
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
					<font style="font-size: 25px;">Modificar Programa Apoyo</font>
				</label><br>
				<h2><?= $seccion_ine_ciudadanoDatos['nombre_completo']; ?></h2>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar programa apoyo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>