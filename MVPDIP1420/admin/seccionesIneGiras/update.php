<?php
	include '../functions/tool_xhpzab.php';
	@session_start();
	if($_GET['refresh']==1){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		die;
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/secciones_ine_giras.php";
	include __DIR__."/../functions/secciones_ine_giras_puntos.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/ejes_gobierno.php";
	include __DIR__."/../functions/tipos_giras.php";

	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_giras','seccionesIneGiras','index');
	if($redirectSecurity!=""){
		die;
	}
	$claveF= clave2('secciones_ine_giras');
	$seccion_ine_giraDatos=seccion_ine_giraDatos($id);
	if($seccion_ine_giraDatos['clave']==""){
		$seccion_ine_giraDatos['clave']=$claveF['clave'];
	}

	$seccion_ine_giraDatos['monto_total']=number_format($seccion_ine_giraDatos['monto_total'],2,".",",");
	$seccion_ine_giraDatos['beneficiarios']=number_format($seccion_ine_giraDatos['beneficiarios'],0,"",",");
	$seccion_ine_giraDatos['meta_cantidad']=number_format($seccion_ine_giraDatos['meta_cantidad'],0,"",",");

	$secciones_ine_giras_puntosDatos = secciones_ine_giras_puntosDatos('',$id);
	$xlatitud = null;
	unset($value);
	foreach ($secciones_ine_giras_puntosDatos as $key => $value) {
	}



	if($value['longitud']!=''){
		$seccion_ine_giraDatos['longitud'] = $value['longitud'];
		$seccion_ine_giraDatos['latitud'] = $value['latitud'];
	}
	$dependenciasDatos = dependenciasDatos();
	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneGiras/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink+'?refresh=1');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var coma= /,/g;
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

			var id_eje_gobierno = document.getElementById("id_eje_gobierno").value; 
			if(id_eje_gobierno == ""){
				document.getElementById("id_eje_gobierno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Eje de Gobierno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_dependencia = document.getElementById("id_dependencia").value; 
			if(id_dependencia == ""){
				document.getElementById("id_dependencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Dependencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var dependencias_colaborativas = [];
			var num = 0;
			<?php
				foreach ($dependenciasDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_dp<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
						num = num + 1;
						var data = '<?= $value['id'] ?>';
						dependencias_colaborativas.push(data);
					}
					<?php
				}
			?>
			dependencias_string = dependencias_colaborativas.join(',');

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var num_asistentes = document.getElementById("num_asistentes").value; 
			if(num_asistentes == ""){
				document.getElementById("num_asistentes").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Num Asistentes requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var num_beneficiarios = document.getElementById("num_beneficiarios").value; 
			if(num_beneficiarios == ""){
				document.getElementById("num_beneficiarios").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Num Beneficiarios requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha = document.getElementById("fecha").value; 
			if(fecha == ""){
				document.getElementById("fecha").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!fechaValida(fecha)){ 
					$("#mensaje").html("Fecha Inicio Válida requerido");
					document.getElementById("sumbmit").disabled = false;
					document.getElementById("mensaje").classList.add("mensajeError");
					document.getElementById("fecha").focus(); 
					return false;
				}
			}

			var hora = document.getElementById("hora").value; 
			if(hora == ""){
				document.getElementById("hora").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}



			/*
			var tipo = document.getElementById("tipo").value; 
			if(tipo == ""){
				document.getElementById("tipo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/

			var id_tipo_gira = document.getElementById("id_tipo_gira").value; 
			if(id_tipo_gira == ""){
				document.getElementById("id_tipo_gira").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Agenda Gobierno requerido");
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

			var seccion_ine_gira = []; 
			var data = {    
					'id' : id,
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'id_seccion_ine' : id_seccion_ine,
					'id_tipo_gira' : id_tipo_gira,
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
					'fecha' : fecha,
					'hora' : hora,
					'id_dependencia' : id_dependencia,
					'ids_dependencias' : dependencias_string,
					'num_asistentes' : num_asistentes,
					'num_beneficiarios' : num_beneficiarios,
					'id_eje_gobierno' : id_eje_gobierno,
				}
			seccion_ine_gira.push(data);


			var dataTable = $('#respuestan-tabla').DataTable();
			// Obtener todos los datos de la tabla
			var data_table = dataTable.rows().data().toArray();
			var puntos_nuevos = [];
			var puntos_registrados = [];

			data_table.forEach(element => {
				if(element[2]==""){
					var data = {    
						'orden' : element[0],
						'latitud' : element[3],
						'longitud' : element[4],
					}
					puntos_nuevos.push(data);
				}else{
					var data = {    
						'id' : element[2],
						'orden' : element[0],
						'latitud' : element[3],
						'longitud' : element[4],
					}
					puntos_registrados[element[2]] = data ;
				}
			});

			$.ajax({
				type: "POST",
				url: "seccionesIneGiras/db_edit.php",
				data: {seccion_ine_gira: seccion_ine_gira,puntos_nuevos:puntos_nuevos,puntos_registrados:puntos_registrados},
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
						urlink="seccionesIneGiras/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink+'?refresh=1');
					}else{
						if(data==""){
							urlink="seccionesIneGiras/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink+'?refresh=1');
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
					<font style="font-size: 25px;">Modificar Agenda de Gobierno</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a agenda de gobierno.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>