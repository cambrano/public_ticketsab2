<?php
	include __DIR__."/../functions/security.php";
	@session_start(); 
	if($_COOKIE["id_usuario"]!=1){
		?>
		<script type="text/javascript">
			document.getElementById("mensaje").classList.add("mensajeError");
			$("#mensaje").html("No tiene permiso");
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		</script>
		<?php
		die;
	}
?>
	<title>reiniciar_sistema Inicial</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="setupmanagerpanel/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function ResetInput(){
			document.getElementById("form").reset();
			document.getElementById("form").style.border="";
		}

		function guardar(){
			document.getElementById("sumbmit").disabled = true;
			var datas = [];
			var data = {   
					'reset' : 1,
				}
			datas.push(data);
			 
			$.ajax({
				type: "POST",
				url: "reiniciar_sistema/db_reset.php",
				data: {datas: datas},
				async: false,
				success: function(data) {
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito");  
						document.getElementById("mensaje").classList.add("mensajeSucces");
						location.reload();
						
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
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div id="mensaje"><br></div>
		<form name="form" id="form"> 
			<div class="bodyform">
				<div class= "bodyheader">
					<br><br>
					<label class="tituloForm">
						<font style="font-size: 25px;">Reniciar Sistema</font>
					</label><br>
					<label class="descripcionForm">
						<font style="font-size: 13px;"></font><br><br>
					</label><br>
					<font style="font-size: 15px;"><strong></strong></font>
				</div>
			</div> 
			<div class="bodyinput">
				<?php include "form.php";?>
			</div>
		</form>
	</div>