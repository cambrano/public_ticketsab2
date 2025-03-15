<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php"; 
	include __DIR__."/../functions/documentos_oficiales.php";
	include __DIR__."/../functions/documentos_oficiales_images.php";

	@session_start();
	$_SESSION['Paguinasub']="documentosOficiales/update.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'documentos_oficiales','documentosOficiales','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_identidad = $_SESSION['id_identidad']; 
	if($id_identidad!=""){
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_identidad,'identidades','identidades','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	$documento_oficialDatos=documento_oficialDatos($id);

	unset($_SESSION['image_num']);
	unset($_SESSION['image']);
	$documento_oficial_imagesDatos=documento_oficial_imagesDatos('',$id);
	foreach ($documento_oficial_imagesDatos as $key => $value) {
		$image = file_get_contents(__DIR__.'/'.$value['file']);
		$_SESSION['image'][$key]= array(
			'name' => $value['name'],
			'file'=>$value['file'],
			'imagePrint'=>$image , 
			'id_identidad'=>$value['id_identidad'],
			'tipo_imagen'=>$value['tipo_imagen'],
			'status'=>'1',
			'file_name'=>$value['name'],
			'type'=>$value['type'],
			'file_size'=>$value['file_size'],
			'id'=>$value['id'], 
		);
	}

	
	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('documentosOficiales/index.php');
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

			var id_identidad = '<?= $id_identidad ?>'; 
			if(id_identidad == ""){
				document.getElementById("id_identidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Identidad requerido");
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

			var fecha_emision = document.getElementById("fecha_emision").value; 
			if(fecha_emision == ""){
				document.getElementById("fecha_emision").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Emisión requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_vigencia = document.getElementById("fecha_vigencia").value;
			if(fecha_vigencia == ""){
				document.getElementById("fecha_vigencia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Vigencia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var documento_oficial = []; 
			var data = {    
					'id' : id,
					'id_identidad' : id_identidad,
					'tipo' : tipo,
					'fecha_emision' : fecha_emision,
					'fecha_vigencia' : fecha_vigencia,
				}
			documento_oficial.push(data);

			$.ajax({
				type: "POST",
				url: "documentosOficiales/db_edit.php",
				data: {documento_oficial: documento_oficial},
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
						$("#homebody").load('documentosOficiales/index.php');
					}else{
						if(data==""){
							$("#homebody").load('documentosOficiales/index.php');
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
					<font style="font-size: 25px;">Modificar Documento Oficial</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a documento oficial.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>