<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/partidos_legados.php";
	include __DIR__."/../functions/claves.php";
	@session_start(); 
	$_SESSION['Paguinasub']="partidosLegados/update.php";  
	$id_hotel=$_SESSION['id_hotel']; 
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'partidos_legados','partidos2018','index');
	//var_dump($redirectSecurity);
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('partidos_legados');
	$partido_legadoDatos=partido_legadoDatos($id);
	if($partido_legadoDatos['clave']==""){
		$partido_legadoDatos['clave']=$claveF['clave'];
	}


	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('partidosLegados/index.php');
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

			var nombre_corto = document.getElementById("nombre_corto").value; 
			if(nombre_corto == ""){
				document.getElementById("nombre_corto").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre Corto requerido");
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
			var partido_legado = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'nombre_corto' : nombre_corto,
					'nombre' : nombre,
				}
			partido_legado.push(data);
			$.ajax({
				type: "POST",
				url: "partidosLegados/db_edit.php",
				data: {partido_legado: partido_legado},
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
						$("#homebody").load('partidosLegados/index.php');
					}else{
						if(data==""){
							$("#homebody").load('partidosLegados/index.php');
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
					<font style="font-size: 25px;">Modificar Partido</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a partido.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>