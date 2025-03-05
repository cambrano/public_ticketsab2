<?php
	include __DIR__."/../functions/security.php";
	include '../functions/switch_operaciones.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET['cot'])){
		$id_seccion_ine_ciudadanox=$_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_seccion_ine_ciudadanox),time()+(60*60*24*650),"/",false);
	}else{
		$id_seccion_ine_ciudadanox = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	if($id_seccion_ine_ciudadanox!=""){
		$id_seccion_ine_ciudadanox;
		$seccion_ine_ciudadanoDatos = seccion_ine_ciudadanoDatos($id_seccion_ine_ciudadanox);
		$nombre_completo = $seccion_ine_ciudadanoDatos['nombre_completo'];
		if($nombre_completo==""){
			echo $redirectSecurity=redirectSecurity('','secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_seccion_ine_ciudadanox,'secciones_ine_ciudadanos','seccionesIneCiudadanos','index');
		if($redirectSecurity!=""){
			die;
		}
	}


	$switch_operacionesPermisos = switch_operacionesPermisos();
?>
	<title>Programas Apoyos</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subSeccionesIneCiudadanos()">Ciudadanos</div> <br>
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if($switch_operacionesPermisos['evaluacion']==false){
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
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Programa Apoyos
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $switch_operacionesPermisos){
					?>
					<input type="button" value="Nuevo Programa Apoyo" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div> <?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>