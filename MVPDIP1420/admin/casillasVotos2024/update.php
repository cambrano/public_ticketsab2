<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/casillas_votos_partidos_2024.php";
	include __DIR__."/../functions/claves_2.php";
	include __DIR__."/../functions/partidos_2024.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_parametros.php";
	include __DIR__."/../functions/tipos_casillas.php";

	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'casillas_votos_2024','casillasVotos2024','index');
	//var_dump($redirectSecurity);
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave2('casillas_votos_2024');
	$casilla_voto_2024Datos=casilla_voto_2024Datos($id);
	if($casilla_voto_2024Datos['clave']==""){
		$casilla_voto_2024Datos['clave']=$claveF['clave'];
	}

	$tipo = $casilla_voto_2024Datos['tipo'];

	$partidos_2024Datos = partidos_2024Datos('','',$tipo);

	$casillas_votos_partidos_2024Datos = casillas_votos_partidos_2024Datos('',$id,'');

	$votos_validos = 0;
	foreach ($partidos_2024Datos as $key => $value) {
		foreach ($casillas_votos_partidos_2024Datos as $keyT => $valueT) {
			if($value['id']==$valueT['id_partido_2024']){
				$partidos_2024Datos[$key]['votos'] = $valueT['votos'];
				$votos_validos = $valueT['votos'] + $votos_validos;
			}
		}
	}


	$votos_totales = $votos_validos + $casilla_voto_2024Datos['votos_can_nreg'] + $casilla_voto_2024Datos['votos_nulos'];

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="casillasVotos2024/index.php";
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

			var id_seccion_ine = document.getElementById("id_seccion_ine").value; 
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var tipo_seccion = document.getElementById("tipo_seccion").value; 
			if(tipo_seccion == ""){
				document.getElementById("tipo_seccion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}



			var id_tipo_casilla = document.getElementById("id_tipo_casilla").value; 
			if(id_tipo_casilla == ""){
				document.getElementById("id_tipo_casilla").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var codigo = document.getElementById("codigo").value; 
			if(codigo == ""){
				document.getElementById("codigo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var lista_nominal = document.getElementById("lista_nominal").value; 
			/*
			if(lista_nominal==0){
				document.getElementById("lista_nominal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Monto no puede ser 0 Lista Nominal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			*/
			var lista_nominal = document.getElementById("lista_nominal").value; 
			var lista_nominal=  lista_nominal.replace(coma,'');
			if(lista_nominal == ""){
				document.getElementById("lista_nominal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Lista Nominal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var votos_nulos = document.getElementById("votos_nulos").value; 
			var votos_nulos=  votos_nulos.replace(coma,'');
			if(votos_nulos == ""){
				document.getElementById("votos_nulos").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Votos Nulos requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var votos_can_nreg = document.getElementById("votos_can_nreg").value; 
			var votos_can_nreg=  votos_can_nreg.replace(coma,'');
			if(votos_can_nreg == ""){
				document.getElementById("votos_can_nreg").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Votos CAN NREG requerido");
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
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var longitud = document.getElementById("longitud_m").value;
			var latitud = document.getElementById("latitud_m").value;

			var num_ext = document.getElementById("num_ext").value;
			var num_int = document.getElementById("num_int").value;
			var referencia = document.getElementById("referencia").value;

			var casilla_voto_2024 = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'id_tipo_casilla' : id_tipo_casilla,
					'codigo' : codigo,
					'lista_nominal' : lista_nominal,
					'status' : status,
					'votos_nulos' : votos_nulos,
					'votos_can_nreg' : votos_can_nreg,

					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'colonia' : colonia, 
					'codigo_postal' : codigo_postal,
					'latitud' : latitud,
					'longitud' : longitud,
					'tipo' : '<?= $tipo ?>',

					'num_int' : num_int,
					'num_ext' : num_ext,
					'referencia' : referencia,
				}
			casilla_voto_2024.push(data);

			///partidos
			var votos_partidos_2024 = [];
			<?php
			foreach ($partidos_2024Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				var votos_partido_<?= $value['id'] ?>=  votos_partido_<?= $value['id'] ?>.replace(coma,'');
				if(votos_partido_<?= $value['id'] ?> == ""){
					document.getElementById("votos_partido_<?= $value['id'] ?>").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Votos <?= $value['nombre_corto'] ?> requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var id_partido_2024 = '<?= $value['id'] ?>';
				var data = {
						'id_partido_2024' : id_partido_2024,
						'votos' : votos_partido_<?= $value['id'] ?>,
						'tipo' : '<?= $tipo ?>',
					}
				votos_partidos_2024.push(data);
				<?php
			}
			?>

			$.ajax({
				type: "POST",
				url: "casillasVotos2024/db_edit.php",
				data: {casilla_voto_2024: casilla_voto_2024,votos_partidos_2024:votos_partidos_2024},
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
						urlink="casillasVotos2024/index.php";
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
							$("#homebody").load('casillasVotos2024/index.php');
							urlink="casillasVotos2024/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
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
					<font style="font-size: 25px;">Modificar Casilla Voto 2024<br></font>
					<?php

					if($tipo==0){
						?>
						<font style="font-size: 25px;">Municipio</font>
						<?php
					}elseif ($tipo==1) {
						?>
						<font style="font-size: 25px;">Distrito Local</font>
						<?php
					}else{
						?>
						<font style="font-size: 25px;">Distrito Federal</font>
						<?php
					}

					?>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a casilla voto 2024.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>