<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/secciones_ine.php"; 
	include __DIR__."/../functions/secciones_ine_parametros.php"; 
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/distritos_locales.php";
	
	@session_start();
	$_SESSION['Paguinasub']="seccionesIne/update.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine','seccionesIne','index');
	if($redirectSecurity!=""){
		die;
	}
	
	$claveF= clave('secciones_ine');
	$seccion_ineDatos=seccion_ineDatos($id);
	if($seccion_ineDatos['clave']==""){
		$seccion_ineDatos['clave']=$claveF['clave'];
	}
	unset($_SESSION['limites']);
	unset($_SESSION['limites_num']);
	//var_dump($usuario_seccion_ineDatos);

	$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('',$id,'orden DESC');

	$num = 0;
	foreach ($secciones_ine_parametrosDatos as $key => $value) {
		$_SESSION['limites_num'] = $num;
		$_SESSION['limites'][$key]= array(
			'numero' => $key,
			'orden'=>$value['orden'],
			'longitud'=>$value['longitud'],
			'latitud'=>$value['latitud'],
			'status'=>'1',
			'id'=>$value['id'],
		);
		$num = $num + 1;
	}

	$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');
	foreach ($secciones_ine_parametrosDatos as $key => $value) {
		$secciones_area[$value['id_seccion_ine']][] = $value ;
	}

	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIne/index.php');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			
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

			var numero = document.getElementById("numero").value; 
			if(numero == ""){
				document.getElementById("numero").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_municipio = document.getElementById("id_municipio").value; 
			if(id_municipio == ""){
				document.getElementById("id_municipio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_distrito_federal = document.getElementById("id_distrito_federal").value; 
			if(id_distrito_federal == ""){
				document.getElementById("id_distrito_federal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Distrito Federal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_distrito_local = document.getElementById("id_distrito_local").value; 
			if(id_distrito_local == ""){
				document.getElementById("id_distrito_local").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Distrito Local requerido");
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


 
			 
			var seccion_ine = []; 
			var data = { 
					'id' : id,
					'clave' : clave,
					'numero' : numero,
					'id_municipio' : id_municipio,
					'id_distrito_local' : id_distrito_local,
					'id_distrito_federal' : id_distrito_federal,
					'latitud' : latitud,
					'longitud' : longitud,
					
				}
			seccion_ine.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIne/db_edit.php",
				data: {seccion_ine: seccion_ine},
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
						$("#homebody").load('seccionesIne/index.php');
					}else{
						if(data==""){
							$("#homebody").load('seccionesIne/index.php');
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
					<font style="font-size: 25px;">Modificar Sección</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a sección.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>