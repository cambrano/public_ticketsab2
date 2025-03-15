<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/distritos_locales.php"; 
	include __DIR__."/../functions/distritos_locales_parametros.php"; 
	include __DIR__."/../functions/claves.php"; 
	
	@session_start();
	$_SESSION['Paguinasub']="distritosLocales/update.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'distritos_locales','distritosLocales','index');
	if($redirectSecurity!=""){
		die;
	}
	
	$claveF= clave('distritos_locales');
	$distrito_localDatos=distrito_localDatos($id);
	if($distrito_localDatos['clave']==""){
		$distrito_localDatos['clave']=$claveF['clave'];
	}
	unset($_SESSION['limites']);
	unset($_SESSION['limites_num']);
	//var_dump($usuario_distrito_localDatos);
	/*
	$distritos_locales_parametrosDatos = distritos_locales_parametrosDatos('',$id,'orden DESC');
	$num = 0;
	foreach ($distritos_locales_parametrosDatos as $key => $value) {
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
	*/

	$distritos_locales_parametrosDatosMapa = distritos_locales_parametrosDatosMapa();
	$num = 0;
	foreach ($distritos_locales_parametrosDatosMapa as $key => $value) {
		if($key == $id){
			foreach ($value as $keyT => $valueT) {
				$_SESSION['limites_num'] = $num;
				$_SESSION['limites'][$keyT]= array(
					'numero' => $keyT,
					'orden'=>$valueT['orden'],
					'longitud'=>$valueT['longitud'],
					'latitud'=>$valueT['latitud'],
					'status'=>'1',
					'id'=>$valueT['id'],
				);
				$num = $num + 1;
			}
		}
	}
	

	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('distritosLocales/index.php');
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
 
			 
			var distrito_local = []; 
			var data = { 
					'id' : id,
					'clave' : clave,
					'numero' : numero,
					'latitud' : latitud,
					'longitud' : longitud,
					
				}
			distrito_local.push(data);
			$.ajax({
				type: "POST",
				url: "distritosLocales/db_edit.php",
				data: {distrito_local: distrito_local},
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
						$("#homebody").load('distritosLocales/index.php');
					}else{
						if(data==""){
							$("#homebody").load('distritosLocales/index.php');
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
					<font style="font-size: 25px;">Modificar Distrito Local</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a distrito local.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>