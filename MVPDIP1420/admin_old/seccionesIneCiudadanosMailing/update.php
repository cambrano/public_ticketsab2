<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/campanas_mailing.php";
	include __DIR__."/../functions/campanas_mailing_cuerpos.php";
	include __DIR__."/../functions/tablas_relacionadas.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include '../functions/usuario_permisos.php';
	include '../functions/configuracion.php';
	include '../functions/usuarios.php';
	@session_start(); 
	$_SESSION['Paguinasub']='seccionesIneCiudadanosMailing/update.php';
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	 
	echo $redirectSecurity=redirectSecurity($id,'campanas_mailing','seccionesIneCiudadanosMailing','index');
	if($redirectSecurity!=""){
		die;
	}

	$id_seccion_ine_ciudadano=$_SESSION['id_seccion_ine_ciudadano'];
	if($id_seccion_ine_ciudadano==''){
		echo $redirectSecurity=redirectSecurity('','campanas_mailing','seccionesIneCiudadanosMailing','index');
		if($redirectSecurity!=""){
			die;
		}
	}



	$campana_mailingDatos=campana_mailingDatos($id);
	$seccion_ine_ciudadanoDatos=seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_mailing',$_COOKIE["id_usuario"]);

	$campana_mailing_cuerpoDatos = campana_mailing_cuerpoDatos('',$id);
	if($campana_mailingDatos['tipo']==1){
		$tipo_campana ='Bienvenida';
	}elseif ($campana_mailingDatos['tipo']==2) {
		$tipo_campana ='Programada';
	}else{
		$tipo_campana ='Encuesta';
	}

	include 'preview.php';
?>
	<title>Reenviar </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneCiudadanosMailing/index.php');
		}
		function guardar() {
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
			var mensajeDelete = '<?= $registros_tablas ?>';
			if(mensajeDelete != ''){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html('Tiene registros relacionados no se puede borrar el registro.');
				document.getElementById("mensaje").classList.add("mensajeError"); 
				return false;
			}
			//var dataString = 'id=<?=$id;?>';
			var dataString = 'id=<?=$id;?>';
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosMailing/db_reenviar_masivos_configurada.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('seccionesIneCiudadanosMailing/index.php');  

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
					<font style="font-size: 25px;">Reenviar Correo <br></font>
					<h3> <?= $seccion_ine_ciudadanoDatos['nombre_completo'] ?> </h3>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Camapaña Mail</label><br>
			<label class="descripcionForm">
				<strong><?= $campana_mailingDatos['nombre']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Tipo</label><br>
			<label class="descripcionForm">
				<strong><?= $tipo_campana ?></strong>
			</label><br>

			<label class="labelForm" id="labeltemaname">Asunto</label><br>
			<label class="descripcionForm">
				<strong><?= $asuntoHTML?></strong>
			</label><br>
			<?=	$bodyHTML;	?>

			<?php
			if($registros_tablas!=''){
				echo '<div class="mensajeWarning"><font style="font-size:10px">*Usted debe borrar antes los registros relacionados.</font><br>'.$registros_titutlo.'';
				echo '<b>'.$registros_tablas.'</b>';
				echo '</div><br>';
			}
			if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Enviar">
				<?php
			}
			?>
			<input type="button" onclick="cerrar()" value="Salir">
		</div>
	</div>