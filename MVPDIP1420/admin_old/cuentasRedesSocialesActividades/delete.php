<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/cuentas_redes_sociales_actividades.php";
	include __DIR__."/../functions/cuentas_redes_sociales.php";
	include __DIR__."/../functions/redes_sociales.php";
	@session_start();
	include '../functions/usuario_permisos.php';  
	if(!empty($_GET)){
		$_SESSION['Paguinasub']="cuentasRedesSocialesActividades/delete.php";
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$id;
	echo $redirectSecurity=redirectSecurity($id,'cuentas_redes_sociales_actividades','cuentasRedesSocialesActividades','index');
	if($redirectSecurity!=""){
		die;
	}
	if($_SESSION['reset']!="x"){
		$disbale_id_pricipal='disabled="disabled"';
		$id_cuenta_red_social = $_SESSION['id_cuenta_red_social']; 
		if($id_cuenta_red_social!=""){
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}else{
			echo $redirectSecurity=redirectSecurity($id_cuenta_red_social,'cuentas_redes_sociales','cuentasRedesSociales','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}
	$cuenta_red_social_actividadDatos=cuenta_red_social_actividadDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_actividades',$_COOKIE["id_usuario"]);
	//var_dump($cuenta_red_social_actividadDatos);
?>
	<title>Delete</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('cuentasRedesSocialesActividades/index.php');
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
			var dataString = 'id=<?=$id;?>';  
			$.ajax({
				type: "POST",
				url: "cuentasRedesSocialesActividades/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('cuentasRedesSocialesActividades/index.php');  

					}else{
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
						document.getElementById("mensaje").classList.add("mensajeError"); 
					}
					//$("#homebody").load('temaslist.php'); 
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
					<font style="font-size: 25px;">Eliminar Actividad Red Social </font>
				</label><br>
				
			</div>
		</div>
		<div class="bodyinput">
			<br>
			<label class="labelForm" id="labeltemaname">Red Social</label><br>
			<label class="descripcionForm">
				<strong><?= red_socialNombre($cuenta_red_socialDatos['id_red_social']); ?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Fecha</label><br>
			<label class="descripcionForm">
				<strong><?=$cuenta_red_social_actividadDatos['fecha_emision']; ?></strong>
			</label><br>
			<br>
			<label class="labelForm" id="labeltemaname">Detalle</label><br>
			<label class="descripcionForm">
				<strong><?=$cuenta_red_social_actividadDatos['detalle']; ?></strong>
			</label><br>
			<font style="font-size: 15px;"><strong></strong></font>
			<?php
			if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="SI">
				<?php
			}
			?>
			<input type="button" onclick="cerrar()" value="NO">
		</div>
	</div>