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
	include __DIR__."/../functions/secciones_ine_agendas_gobierno.php";
	include __DIR__."/../functions/secciones_ine_agendas_gobierno_locaciones.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/ejes_gobierno.php";
	include __DIR__."/../functions/tipos_giras.php";

	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_agendas_gobierno','seccionesIneAgendasGobierno','index');
	if($redirectSecurity!=""){
		die;
	}
	$claveF= clave2('secciones_ine_agendas_gobierno');
	$seccion_ine_agenda_gobiernoDatos=seccion_ine_agenda_gobiernoDatos($id);
	if($seccion_ine_agenda_gobiernoDatos['clave']==""){
		$seccion_ine_agenda_gobiernoDatos['clave']=$claveF['clave'];
	}

	$seccion_ine_agenda_gobiernoDatos['monto_total']=number_format($seccion_ine_agenda_gobiernoDatos['monto_total'],2,".",",");
	$seccion_ine_agenda_gobiernoDatos['beneficiarios']=number_format($seccion_ine_agenda_gobiernoDatos['beneficiarios'],0,"",",");
	$seccion_ine_agenda_gobiernoDatos['meta_cantidad']=number_format($seccion_ine_agenda_gobiernoDatos['meta_cantidad'],0,"",",");

	$secciones_ine_agendas_gobierno_locacionesDatos = secciones_ine_agendas_gobierno_locacionesDatos('',$id);
	$dependenciasDatos = dependenciasDatos();
	$permiso="update"; 

	$seccion_ine_agenda_gobiernoDatos['id_estado'] = $id_estado;
	$seccion_ine_agenda_gobiernoDatos['id_municipio'] = $id_municipio;
	$seccion_ine_agenda_gobiernoDatos['id_localidad'] = 290086;
	$seccion_ine_agenda_gobiernoDatos['fecha'] = date("Y-m-d");
	$seccion_ine_agenda_gobiernoDatos['hora'] = date("H:i:s");
	
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="seccionesIneAgendasGobierno/index.php";
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

			var id_tipo_gira = document.getElementById("id_tipo_gira").value; 
			if(id_tipo_gira == ""){
				document.getElementById("id_tipo_gira").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Agenda Gobierno requerido");
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

			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var organo_externo = document.getElementById("organo_externo").value; 
			if(organo_externo == ""){
				document.getElementById("organo_externo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Organismo Externo requerido");
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

			var observaciones = document.getElementById("observaciones").value;
			if(observaciones == ""){
				document.getElementById("observaciones").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Observaciones requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var seccion_ine_agenda_gobierno = []; 
			var data = {    
					'id' : id,
					'clave' : clave,
					'id_tipo_gira' : id_tipo_gira,
					'id_eje_gobierno' : id_eje_gobierno,
					'nombre' : nombre,
					'organo_externo' : organo_externo,
					'id_dependencia' : id_dependencia,
					'ids_dependencias' : dependencias_string,
					'num_asistentes' : num_asistentes,
					'num_beneficiarios' : num_beneficiarios,
					'observaciones' : observaciones,
				}
			seccion_ine_agenda_gobierno.push(data);

			const tableSubEventos = $('#sub_eventos-tabla').DataTable();
			// Obtener los datos de todas las filas como un array
			const datosSubEventos = tableSubEventos.rows().data().toArray();
			if (datosSubEventos.length === 0) {
				document.getElementById("mensaje").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Debe agregar por lo menos una Fecha y Dirección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			let sub_eventos_nuevos = {};
			var sub_eventos_registrados = [];
			datosSubEventos.forEach((fila) => {
				if(fila[2]==""){
					sub_eventos_nuevos[fila[1]] = {
						fecha : fila[3],
						hora : fila[4],
						id_pais : fila[5],
						id_estado : fila[6],
						id_municipio : fila[7],
						id_localidad : fila[8],
						id_seccion_ine : fila[10],
						colonia : fila[12],
						calle : fila[13],
						num_int : fila[14],
						num_ext : fila[15],
						codigo_postal : fila[16],
						latitud : fila[19],
						longitud : fila[20],
					};
				}else{
					sub_eventos_registrados[fila[1]] = {
						id : fila[2],
						fecha : fila[3],
						hora : fila[4],
						id_pais : fila[5],
						id_estado : fila[6],
						id_municipio : fila[7],
						id_localidad : fila[8],
						id_seccion_ine : fila[10],
						colonia : fila[12],
						calle : fila[13],
						num_int : fila[14],
						num_ext : fila[15],
						codigo_postal : fila[16],
						latitud : fila[19],
						longitud : fila[20],
					};
				}
				
			});

			/*
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
			puntos_nuevos:puntos_nuevos,puntos_registrados:puntos_registrados
			*/

			$.ajax({
				type: "POST",
				url: "seccionesIneAgendasGobierno/db_edit.php",
				data: {seccion_ine_agenda_gobierno: seccion_ine_agenda_gobierno,sub_eventos_registrados:sub_eventos_registrados,sub_eventos_nuevos:sub_eventos_nuevos},
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
						urlink="seccionesIneAgendasGobierno/index.php";
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
							urlink="seccionesIneAgendasGobierno/index.php";
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
			$("#mensaje_sub_evento").click(function(event) { 
				document.getElementById("mensaje_sub_evento").classList.remove("mensajeSucces");
				document.getElementById("mensaje_sub_evento").classList.remove("mensajeError");
				$("#mensaje_sub_evento").html("&nbsp");
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