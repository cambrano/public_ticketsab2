<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/notificaciones_sistema.php";
	@session_start();
	$_SESSION['Paguinasub']="notificacionesSistema/index.php";

	$notificaciones_sistema=notificaciones_sistema();
	$fecha_nacimiento_dias=$notificaciones_sistema["fecha_nacimiento_dias"];
	$fecha_nacimiento_intervalo=$notificaciones_sistema["fecha_nacimiento_intervalo"];
	$fecha_nacimiento_status=$notificaciones_sistema["fecha_nacimiento_status"];
	if($fecha_nacimiento_status==1){
		$fecha_nacimiento_statusCK='checked="checked"';
	}
	$sf_fecha_nacimiento_intervalo[$fecha_nacimiento_intervalo]='selected="selected"' ;


	$cliente_retorno_dias=$notificaciones_sistema["cliente_retorno_dias"];
	$cliente_retorno_intervalo=$notificaciones_sistema["cliente_retorno_intervalo"];
	$cliente_retorno_status=$notificaciones_sistema["cliente_retorno_status"];
	if($cliente_retorno_status==1){
		$cliente_retorno_statusCK='checked="checked"';
	}
	$sf_cliente_retorno_intervalo[$cliente_retorno_intervalo]='selected="selected"' ;


	$pago_reserva_dias=$notificaciones_sistema["pago_reserva_dias"];
	$pago_reserva_intervalo=$notificaciones_sistema["pago_reserva_intervalo"];
	$pago_reserva_status=$notificaciones_sistema["pago_reserva_status"];
	if($pago_reserva_status==1){
		$pago_reserva_statusCK='checked="checked"';
	}
	$sf_pago_reserva_intervalo[$pago_reserva_intervalo]='selected="selected"' ;

	$cliente_llegada_dias=$notificaciones_sistema["cliente_llegada_dias"];
	$cliente_llegada_intervalo=$notificaciones_sistema["cliente_llegada_intervalo"];
	$cliente_llegada_status=$notificaciones_sistema["cliente_llegada_status"];
	if($cliente_llegada_status==1){
		$cliente_llegada_statusCK='checked="checked"';
	}
	$sf_cliente_llegada_intervalo[$cliente_llegada_intervalo]='selected="selected"' ;


	$id=$notificaciones_sistema['id']; 
	if($id!=""){
		$permiso="update";
	}else{
		$permiso="insert";
	}
	?>
	<title>notificaciones_sistema</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('setupmanagerpanel/index.php');
		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var fecha_nacimiento_status = document.getElementById("fecha_nacimiento_status").checked;
			if(fecha_nacimiento_status){
				var fecha_nacimiento_status=1;
				var fecha_nacimiento_intervalo = document.getElementById("fecha_nacimiento_intervalo").value; 
				if(fecha_nacimiento_intervalo == ""){
					document.getElementById("fecha_nacimiento_intervalo").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Cumpleaños Intervalo requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var fecha_nacimiento_dias = document.getElementById("fecha_nacimiento_dias").value; 
				if(fecha_nacimiento_dias == ""){
					document.getElementById("fecha_nacimiento_dias").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Cumpleaños Días requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var fecha_nacimiento_status=0;
				var fecha_nacimiento_intervalo = document.getElementById("fecha_nacimiento_intervalo").value; 
				var fecha_nacimiento_dias = document.getElementById("fecha_nacimiento_dias").value; 
			}

			var pago_reserva_status = document.getElementById("pago_reserva_status").checked;
			if(pago_reserva_status){
				var pago_reserva_status=1;
				var pago_reserva_intervalo = document.getElementById("pago_reserva_intervalo").value; 
				if(pago_reserva_intervalo == ""){
					document.getElementById("pago_reserva_intervalo").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Pago Cliente Antes de Reserva Intervalo requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var pago_reserva_dias = document.getElementById("pago_reserva_dias").value; 
				if(pago_reserva_dias == ""){
					document.getElementById("pago_reserva_dias").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Pago Cliente Antes de Reserva Días requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var pago_reserva_status=0;
				var pago_reserva_intervalo = document.getElementById("pago_reserva_intervalo").value; 
				var pago_reserva_dias = document.getElementById("pago_reserva_dias").value; 
			}


			var cliente_retorno_status = document.getElementById("cliente_retorno_status").checked;
			if(cliente_retorno_status){
				var cliente_retorno_status=1;
				var cliente_retorno_intervalo = document.getElementById("cliente_retorno_intervalo").value; 
				if(cliente_retorno_intervalo == ""){
					document.getElementById("cliente_retorno_intervalo").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cliente Retorno Reserva requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var cliente_retorno_dias = document.getElementById("cliente_retorno_dias").value; 
				if(cliente_retorno_dias == ""){
					document.getElementById("cliente_retorno_dias").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cliente Retorno Días requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var cliente_retorno_status=0;
				var cliente_retorno_intervalo = document.getElementById("cliente_retorno_intervalo").value; 
				var cliente_retorno_dias = document.getElementById("cliente_retorno_dias").value; 
			}


			var cliente_llegada_status = document.getElementById("cliente_llegada_status").checked;
			if(cliente_llegada_status){
				var cliente_llegada_status=1;
				var cliente_llegada_intervalo = document.getElementById("cliente_llegada_intervalo").value; 
				if(cliente_llegada_intervalo == ""){
					document.getElementById("cliente_llegada_intervalo").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cliente Fecha Check IN Reserva requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var cliente_llegada_dias = document.getElementById("cliente_llegada_dias").value; 
				if(cliente_llegada_dias == ""){
					document.getElementById("cliente_llegada_dias").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Cliente Fecha Check IN Días requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}else{
				var cliente_llegada_status=0;
				var cliente_llegada_intervalo = document.getElementById("cliente_llegada_intervalo").value; 
				var cliente_llegada_dias = document.getElementById("cliente_llegada_dias").value; 
			}

			if(fecha_nacimiento_status == false && pago_reserva_status == false && cliente_retorno_status == false && cliente_llegada_status == false){
				document.getElementById("sumbmit").disabled = false;
				//return false;
			}

			var notificacion_sistema = []; 
			var data = {
					'fecha_nacimiento_dias' : fecha_nacimiento_dias,
					'fecha_nacimiento_intervalo' : fecha_nacimiento_intervalo,
					'fecha_nacimiento_status' : fecha_nacimiento_status,

					'cliente_retorno_dias' : cliente_retorno_dias,
					'cliente_retorno_intervalo' : cliente_retorno_intervalo,
					'cliente_retorno_status' : cliente_retorno_status,

					'pago_reserva_dias' : pago_reserva_dias,
					'pago_reserva_intervalo' : pago_reserva_intervalo,
					'pago_reserva_status' : pago_reserva_status,

					'cliente_llegada_dias' : cliente_llegada_dias,
					'cliente_llegada_intervalo' : cliente_llegada_intervalo,
					'cliente_llegada_status' : cliente_llegada_status,
				}
			notificacion_sistema.push(data); 
			$.ajax({
				type: "POST",
				url: "notificacionesSistema/db_add_update.php",
				data: {notificacion_sistema: notificacion_sistema},
				success: function(data) {
					if(data=="SI"){ 
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('setupmanagerpanel/index.php');
					}else{
						if(data=="SINCAMBIOS"){
							$("#homebody").load('setupmanagerpanel/index.php');
						}else{
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
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
		<div class="submenux" onclick="subConfiguracion()">Configuración</div> /
		<div id="mensaje"><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Configurar de las notificaciones del sistema</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Formulario para configurar notificaciones del sistema de citas, sucursales, tatuador y cliente.</font><br>
				</label>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>