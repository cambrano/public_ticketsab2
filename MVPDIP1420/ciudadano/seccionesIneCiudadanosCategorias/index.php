<?php
	include __DIR__."/../functions/security.php";
	include '../functions/switch_operaciones.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_categorias.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_seccion_ine_ciudadanox=$_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_seccion_ine_ciudadanox),time()+(60*60*24*650),"/",false);
	}else{
		$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}


	if($id_seccion_ine_ciudadanox!=""){
		$id_seccion_ine_ciudadanox;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadanox);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadanox,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$tipos_categorias_ciudadanosDatos = tipos_categorias_ciudadanosDatos();

	$secciones_ine_ciudadanos_categoriasDatos = secciones_ine_ciudadanos_categoriasDatos('',$id_seccion_ine_ciudadanox);

	foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
		foreach ($secciones_ine_ciudadanos_categoriasDatos as $keyT => $valueT) {
			if($tipos_categorias_ciudadanosDatos[$key]['id'] == $valueT['id_tipo_categoria_ciudadano']){
				$tipos_categorias_ciudadanosDatos[$key]['checked']="checked";
			}
		}
	}

	if($secciones_ine_ciudadanos_categoriasDatos[0]['clave']==""){
		$secciones_ine_ciudadanos_categoriasDatos[0]['clave'] = $tran_cod;
	}

	$switch_operacionesPermisos = switch_operacionesPermisos();
	?>
	<title>Ciudadano Categoría</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			link="seccionesIneCiudadanos/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load('seccionesIneCiudadanos/index.php');
		}
		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");


			var id_seccion_ine_ciudadano = "<?= $id_seccion_ine_ciudadanox ?>"; 
			if(id_seccion_ine_ciudadano == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id Ciudadano Requerido requerido");
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


			var tipo_categoria_ciudadano = [];

			<?php
			foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
				?>
				var id_tipo_categoria_ciudadano_<?= $value['id'] ?> = document.getElementById("id_tipo_categoria_ciudadano_<?= $value['id'] ?>").checked;
				if(id_tipo_categoria_ciudadano_<?= $value['id'] ?>){
					id_tipo_categoria_ciudadano_<?= $value['id'] ?> = 1;
				}else{
					id_tipo_categoria_ciudadano_<?= $value['id'] ?> = 0;
				}

				var id_tipo_categoria_ciudadano = "<?= $value['id'] ?>";

				var data = { 
					'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
					'valor' : id_tipo_categoria_ciudadano_<?= $value['id'] ?>,
					'id_seccion_ine_ciudadano' : id_seccion_ine_ciudadano,
					'clave' : clave,
				}
				tipo_categoria_ciudadano.push(data);
				<?php
			}
			?>

			$.ajax({
					type: "POST",
					url: "seccionesIneCiudadanosCategorias/db_add_update.php",
					data: {tipo_categoria_ciudadano: tipo_categoria_ciudadano},
					success: function(data) {
						if(data=="SI"){ 
							document.getElementById("sumbmit").disabled = true;
							$("#mensaje").html("Guardado con éxito"); 
							document.getElementById("mensaje").classList.add("mensajeSucces");
							link="seccionesIneCiudadanos/index.php";
							dataString = 'urlink='+link; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load('seccionesIneCiudadanos/index.php');
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
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> <br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Ciudadano Categoría</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Por favor, complete el siguiente formulario para las categorías .</font><br>
				</label><br>
				<h2><?= $nombre_completo ?> </h2>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
