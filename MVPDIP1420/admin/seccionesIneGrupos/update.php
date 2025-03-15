<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/secciones_ine_grupos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/tipos_secciones_ine_grupos.php";
	include __DIR__."/../functions/tipos_intereses.php";
	include __DIR__."/../functions/tipos_relaciones.php";
	include __DIR__."/../functions/partidos_legados.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_grupos','seccionesIneGrupos','index');
	if($redirectSecurity!=""){
		die;
	}
	$claveF= clave('secciones_ine_grupos');
	$seccion_ine_grupoDatos=seccion_ine_grupoDatos($id);
	if($seccion_ine_grupoDatos['clave']==""){
		$seccion_ine_grupoDatos['clave']=$claveF['clave'];
	}
	//var_dump($seccion_ine_grupoDatos);
	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneGrupos/index.php";
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
			var espacios_invalidos= /\s+/g;

			var id = '<?= $id ?>'; 
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

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
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

			var id_tipo_seccion_ine_grupo = document.getElementById("id_tipo_seccion_ine_grupo").value; 
			if(id_tipo_seccion_ine_grupo == ""){
				document.getElementById("id_tipo_seccion_ine_grupo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Infraestructura requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_interes = document.getElementById("id_tipo_interes").value; 
			if(id_tipo_interes == ""){
				document.getElementById("id_tipo_interes").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Interes requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_tipo_relacion = document.getElementById("id_tipo_relacion").value; 
			if(id_tipo_relacion == ""){
				document.getElementById("id_tipo_relacion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Relación requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_partido_legado = document.getElementById("id_partido_legado").value; 
			if(id_partido_legado == ""){
				document.getElementById("id_partido_legado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Partido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var observaciones = document.getElementById("observaciones").value;

			var id_seccion_ine = document.getElementById("id_seccion_ine").value; 
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_pais = document.getElementById("id_pais").value; 
			if(id_pais == ""){
				document.getElementById("id_pais").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Pais requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			//alert(codigo_postal);
			var id_estado = document.getElementById("id_estado").value; 
			if(id_estado == ""){
				document.getElementById("id_estado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			//alert(id_estado);
			var id_municipio = document.getElementById("id_municipio").value; 
			if(id_municipio == ""){
				document.getElementById("id_municipio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(id_municipio);

			//alert(id_municipio);
			var id_localidad = document.getElementById("id_localidad").value; 
			if(id_localidad == ""){
				document.getElementById("id_localidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var calle = document.getElementById("calle").value; 
			if(calle == ""){
				document.getElementById("calle").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var num_ext = document.getElementById("num_ext").value;
			var num_int = document.getElementById("num_int").value;
			//alert(calle);
			var colonia = document.getElementById("colonia").value; 
			if(colonia == ""){
				document.getElementById("colonia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Colonia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var codigo_postal = document.getElementById("codigo_postal").value;
			codigo_postal = codigo_postal.replace(espacios_invalidos, '');
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var longitud = document.getElementById("longitud").value;
			var latitud = document.getElementById("latitud").value;

			var longitud_r = document.getElementById("longitud_r").value;
			var latitud_r = document.getElementById("latitud_r").value;

			var seccion_ine_grupo = []; 
			var data = {    
					'id' : id,
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'id_seccion_ine' : id_seccion_ine,
					'fecha' : fecha,
					'hora' : hora,
					'observaciones' : observaciones,
					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'num_int' : num_int,
					'num_ext' : num_ext,
					'colonia' : colonia, 
					'codigo_postal' : codigo_postal,
					'latitud' : latitud,
					'longitud' : longitud,
					'latitud_r' : latitud_r,
					'longitud_r' : longitud_r,
					'id_tipo_seccion_ine_grupo' : id_tipo_seccion_ine_grupo,
					'id_tipo_interes' : id_tipo_interes,
					'id_tipo_relacion' : id_tipo_relacion,
					'id_partido_legado' : id_partido_legado,
				}
			seccion_ine_grupo.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneGrupos/db_edit.php",
				data: {seccion_ine_grupo: seccion_ine_grupo},
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
						urlink="seccionesIneGrupos/index.php";
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
							urlink="seccionesIneGrupos/index.php";
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
					<font style="font-size: 25px;">Modificar Grupo de Interes</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a grupo de interes.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>