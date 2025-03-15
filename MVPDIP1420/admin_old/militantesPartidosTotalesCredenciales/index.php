<?php
	@session_start();
	$_SESSION['Paguinasub']="militantesPartidosTotalesCredenciales/index.php";
	if($_GET['refresh']==1){
		?>
		<script type="text/javascript">
			location.reload();
		</script>
		<?php
		$id_militante_partido=$_GET['cot'];
		$_SESSION['id_militante_partido']=$id_militante_partido;
		die;
	}else{
		$id_militante_partido=$_SESSION['id_militante_partido']; 
	}

	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include __DIR__."/../functions/militantes_partidos.php";
	include __DIR__."/../functions/plataformas.php";

	validar_plataforma_vista($id_militante_partido,'militantes_partidos','militantesPartidosTotales','index',$codigo_plataforma);

	if($id_militante_partido!=""){
		$id_seccion_ine_ciudadano;
		$militante_partidoDatos = militante_partidoDatos($id_militante_partido);
		$id_seccion_ine_ciudadano = $militante_partidoDatos['id_seccion_ine_ciudadano'];
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadano);
	}else{
		echo $redirectSecurity=redirectSecurity($id_militante_partido,'militantes_partidos','militantesPartidosTotales','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	
	$permiso="insert";
	?>
	<title>Ciudadano Categoría</title>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager" style="display: table;"> 
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subPartidosLegados()">Partidos Legados</div> / 
		<div class="submenux" onclick="subMilitantePartidoTotales()">Militantes</div> /
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Ciudadano Credencialización</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br>
				</label><br>
				<h2><?= $nombre_completo ?> </h2>
				<font style="font-size: 15px;"><strong></strong></font>
			</div>
		</div> 
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>
