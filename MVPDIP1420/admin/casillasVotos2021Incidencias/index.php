<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/casillas_votos_2021.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id_casilla_voto_2021 = $_GET['cot'];
		setcookie("paguinaId_1",encrypt_ab_check($id_casilla_voto_2021), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_casilla_voto_2021 = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}
	if($id_casilla_voto_2021!=""){
		$id_casilla_voto_2021;
		$casillas_votos_2021Datos = casillas_votos_2021Datos($id_casilla_voto_2021);
		$codigo = $casillas_votos_2021Datos[0]['codigo'];
		if($codigo==""){ 
			echo $redirectSecurity=redirectSecurity('','casillas_votos_2021','casillasVotos2021','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{ 
		echo $redirectSecurity=redirectSecurity($id_casilla_voto_2021,'casillas_votos_2021','casillasVotos2021','index');
		if($redirectSecurity!=""){
			die;
		}
	}

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);
?>
	<title>Casillas Votos 2021</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subCasillasVotos2021()">Casillas Votos 2021</div> 
		<br>
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<?php
			if(empty($moduloAccionPermisos)){
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
			Casilla <?= $codigo ?> Incidencias
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Incidencia" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>