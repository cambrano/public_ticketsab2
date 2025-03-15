<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/casillas_votos_2021.php";

	@session_start();
	$_SESSION['Paguinasub']="casillasVotos2021Status/index.php";
	if(!empty($_GET['cot'])){
		$id_casilla_voto_2021=$_GET['cot'];
		$_SESSION['id_casilla_voto_2021']=$id_casilla_voto_2021;
	}else{
		$id_casilla_voto_2021=$_SESSION['id_casilla_voto_2021']; 
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
					$("#homebody").load('home.php');
				</script>
				<?php
				die;
			}
		?>
		<h2><?= $nombre_completo ?> </h2>
		<label class="tituloForm">
			Casilla <?= $codigo ?>
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nuevo Etatus" onClick="add();"> 
					<?php
				}
			?>
		</div>
		<br><br>
		<div> <?php include "filtros1.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>