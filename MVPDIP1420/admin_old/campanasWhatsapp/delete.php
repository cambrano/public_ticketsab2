<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/campanas_whatsapp.php";
	include __DIR__."/../functions/campanas_whatsapp_cuerpos.php";
	include __DIR__."/../functions/tablas_relacionadas.php";
	include '../functions/usuario_permisos.php';
	@session_start(); 
	$_SESSION['Paguinasub']="campanasWhatsapp/delete.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'campanas_whatsapp','campanasWhatsapp','index');
	if($redirectSecurity!=""){
		die;
	}

	$campana_whatsappDatos=campana_whatsappDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_whatsapp',$_COOKIE["id_usuario"]);

	$campana_whatsapp_cuerpoDatos = campana_whatsapp_cuerpoDatos('',$id);

	$tablasRelacionadas = tablasRelacionadas('campanas_whatsapp',$id);
	$registros_titutlo = 'Este registro esta ligado a : <br>';
	foreach ($tablasRelacionadas['tablas'] as $key => $value) {
		if($value['registros']>0 && $value['tabla']!='campanas_whatsapp_cuerpos' && $value['tabla']!='campanas_whatsapp_programadas' && $value['tabla']!='campanas_whatsapp_cartografias' && $value['tabla']!='campanas_whatsapp_tipos_ciudadanos' && $value['tabla']!='campanas_whatsapp_tipos_categorias_ciudadanos' && $value['tabla']!='campanas_whatsapp_encuestas' ){
			$registros_tablas .= ' - '.$value['comentario'].' con '.number_format($value['registros'],0,'',',').' registro(s) <br>';
		}
	}

	if($campana_whatsappDatos['tipo']==1){
		$tipo_campana ='Bienvenida';
	}elseif ($campana_whatsappDatos['tipo']==2) {
		$tipo_campana ='Programada';
	}else{
		$tipo_campana ='Encuesta';
	}
?>
	<title>Delete </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('campanasWhatsapp/index.php');
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
				url: "campanasWhatsapp/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('campanasWhatsapp/index.php');  

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
					<font style="font-size: 25px;">Eliminar Campaña Whatsapp</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Campaña Whatsapp</label><br>
			<label class="descripcionForm">
				<strong><?= $campana_whatsappDatos['nombre']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Tipo</label><br>
			<label class="descripcionForm">
				<strong><?= $tipo_campana ?></strong>
			</label><br>

			<label class="labelForm" id="labeltemaname">Cuerpo</label><br>
			<?=	$campana_whatsapp_cuerpoDatos['cuerpo'];	?>
			<br><br>
			<div style="width: 100%"></div>
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