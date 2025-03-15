<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/programas_apoyos.php";
	include __DIR__."/../functions/programas_apoyos_territorios.php";
	include __DIR__."/../functions/programas_apoyos_categorias.php";
	include __DIR__."/../functions/programas_apoyos_dependencias.php";
	include __DIR__."/../functions/tipos_territorios.php";
	include __DIR__."/../functions/categorias_programas_apoyos.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/claves.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'programas_apoyos','programasApoyos','index');
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('programas_apoyos');
	$programa_apoyoDatos=programa_apoyoDatos($id);
	if($programa_apoyoDatos['clave']==""){
		$programa_apoyoDatos['clave']=$claveF['clave'];
	}

	$programas_apoyos_territoriosIdDatos=programas_apoyos_territoriosIdDatos('',$id);
	$programas_apoyos_categoriasIdDatos=programas_apoyos_categoriasIdDatos('',$id);
	$programas_apoyos_dependenciasIdDatos=programas_apoyos_dependenciasIdDatos('',$id);


	$tipos_territoriosDatos = tipos_territoriosDatos();
	$categorias_programas_apoyosDatos = categorias_programas_apoyosDatos();
	//$dependenciasDatos = dependenciasDatos();

	$dependencia = array_keys($programas_apoyos_dependenciasIdDatos);
	$programa_apoyoDatos['id_dependencia'] = $dependencia[0];
	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="programasApoyos/index.php";
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
			var espacios_invalidos= /\s+/g;
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


			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
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

			var fecha_inicio = document.getElementById("fecha_inicio").value;
			fecha_inicio = fecha_inicio.replace(espacios_invalidos, '');
			if(fecha_inicio == ""){
				document.getElementById("fecha_inicio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(fecha_inicio)){ 
					document.getElementById("fecha_inicio").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Inicio Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			var fecha_final = document.getElementById("fecha_final").value;
			fecha_final = fecha_final.replace(espacios_invalidos, '');
			if(fecha_final == ""){
				document.getElementById("fecha_final").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(fecha_final)){ 
					document.getElementById("fecha_final").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Inicio Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			if(fecha_inicio > fecha_final){
				document.getElementById("fecha_inicio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Inicio, no puede ser mayor a Fecha Final");
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

			var programas_apoyos_territorios = [];
			var num = 0;
			<?php
				foreach ($tipos_territoriosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_tt<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
						num = num + 1;
					}else{
						check = 0;
					}
					var data = {
							'id' : '<?= $programas_apoyos_territoriosIdDatos[$value['id']] ['id'] ?>',
							'id_tipo_territorio' : '<?= $value['id'] ?>',
							'check' : check,
						}
					programas_apoyos_territorios.push(data);
					<?php
				}
			?>

			if(num==0){
				document.getElementById("chk_tt<?=$value['id'] ?>").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Territorio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var programas_apoyos_categorias = [];
			var num = 0;
			<?php
				foreach ($categorias_programas_apoyosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_cpa<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
						num = num + 1;
					}else{
						check = 0;
					}
					var data = {
							'id' : '<?= $programas_apoyos_categoriasIdDatos[$value['id']] ['id'] ?>',
							'id_categoria_programa_apoyo' : '<?= $value['id'] ?>',
							'check' : check,
						}
					programas_apoyos_categorias.push(data);
					<?php
				}
			?>

			if(num==0){
				document.getElementById("chk_cpa<?=$value['id'] ?>").focus();
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Categoría requerido");
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

			var programas_apoyos_dependencias = [];
			var data = {
					'id' : '<?= $programas_apoyos_dependenciasIdDatos[$programa_apoyoDatos['id_dependencia']] ['id'] ?>',
					'id_dependencia' : id_dependencia,
				}
			programas_apoyos_dependencias.push(data);

			var programa_apoyo = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'folio' : folio,
					'nombre' : nombre,
					'tipo' : tipo,
					'fecha_inicio' : fecha_inicio,
					'fecha_final' : fecha_final,
					'descripcion' : descripcion,
				}
			programa_apoyo.push(data);
			$.ajax({
				type: "POST",
				url: "programasApoyos/db_edit.php",
				data: {programa_apoyo: programa_apoyo,programas_apoyos_territorios:programas_apoyos_territorios,programas_apoyos_categorias:programas_apoyos_categorias,programas_apoyos_dependencias:programas_apoyos_dependencias},
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
						urlink="programasApoyos/index.php";
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
							urlink="programasApoyos/index.php";
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
					<font style="font-size: 25px;">Modificar Programa Apoyo</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a programa apoyo.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>