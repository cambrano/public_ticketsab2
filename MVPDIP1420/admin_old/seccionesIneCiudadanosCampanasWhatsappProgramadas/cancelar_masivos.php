<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos_campanas_whatsapp_programadas.php"; 
	include __DIR__."/../functions/tablas_relacionadas.php";
	include '../functions/usuario_permisos.php';
	@session_start(); 
	$_SESSION['Paguinasub']="seccionesIneCiudadanosCampanasWhatsappProgramadas/cancelar_masivos.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_ciudadanos_campanas_whatsapp_programadas','seccionesIneCiudadanosCampanasWhatsappProgramadas','index');
	if($redirectSecurity!=""){
		die;
	}
	$seccion_ine_ciudadano_campana_whatsapp_programadaDatos=seccion_ine_ciudadano_campana_whatsapp_programadaDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_ciudadanos_campanas_whatsapp_programadas',$_COOKIE["id_usuario"]);

	$tablasRelacionadas = tablasRelacionadas('secciones_ine_ciudadanos_campanas_whatsapp_programadas',$id);
	$registros_titutlo = 'Este registro esta ligado a : <br>';
	foreach ($tablasRelacionadas['tablas'] as $key => $value) {
		if($value['registros']>0){
			$registros_tablas .= ' - '.$value['comentario'].' con '.number_format($value['registros'],0,'',',').' registro(s) <br>';
		}
	}
?>
	<title>Cancelar Masivos </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php');
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
			var mensajeCancelar Masivos = '<?= $registros_tablas ?>';
			if(mensajeCancelar Masivos != ''){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html('Tiene registros relacionados no se puede borrar el registro.');
				document.getElementById("mensaje").classList.add("mensajeError"); 
				return false;
			}
			//var dataString = 'id=<?=$id;?>';
			var dataString = 'id=<?=$id;?>';
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosCampanasWhatsappProgramadas/db_cancelar_masivos.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('seccionesIneCiudadanosCampanasWhatsappProgramadas/index.php');  

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
					<font style="font-size: 25px;">Eliminar Tipo Casilla</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Tipo Casilla</label><br>
			<label class="descripcionForm">
				<strong><?= $seccion_ine_ciudadano_campana_whatsapp_programadaDatos['nombre']?></strong>
			</label><br>
			<?php
			if($registros_tablas!=''){
				echo '<div class="mensajeWarning"><font style="font-size:10px">*Usted debe borrar antes los registros relacionados.</font><br>'.$registros_titutlo.'';
				echo '<b>'.$registros_tablas.'</b>';
				echo '</div><br>';
			}
			if( $moduloAccionPermisos['delete'] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="SI">
				<?php
			}
			?>
			<input type="button" onclick="cerrar()" value="NO">
		</div>
	</div>