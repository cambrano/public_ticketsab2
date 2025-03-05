<?php
	include __DIR__."/../functions/security.php";
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/genid.php";
	include __DIR__."/../functions/dependencias.php";
	include '../functions/tool_xhpzab.php';
	@session_start();
	$moduloAccionPermisos = moduloAccionPermisos('dependencias','dependencias',$_COOKIE["id_usuario"]);
	if(!empty($_GET['cot'])){
		$id_dependencia=$_GET['cot'];
		setcookie("paguinaId_2",encrypt_ab_check($id_dependencia), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id_dependencia = decrypt_ab_checkFinal($_COOKIE['paguinaId_2']);
	}

	if($id_dependencia!=""){
		$id_dependencia;
		$dependenciaDatos = dependenciaDatos($id_dependencia);
		$nombre = $dependenciaDatos['nombre'];
		if($nombre==""){
			echo $redirectSecurity=redirectSecurity('','dependencias','dependencias','index');
			if($redirectSecurity!=""){
				die;
			}
		}
	}else{
		echo $redirectSecurity=redirectSecurity($id_dependencia,'dependencias','dependencias','index');
		if($redirectSecurity!=""){
			die;
		}
	}
	?>
	<title>Sub Dependencias</title>
	<div id="bodymanager" class="bodymanager">
		<div class="submenux" onclick="subConfiguracionPadrones()">Sistema Único De Beneficiarios</div> / 
		<div class="submenux" onclick="subDependencias()">Dependencias</div>
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
		<h2><?= $nombre ?> </h2>
		<label class="tituloForm">
			Sub Dependencias
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
			<?php
				if( $moduloAccionPermisos['insert'] || $moduloAccionPermisos['all']){
					?>
					<input type="button" value="Nueva Sub Dependencia" onClick="add();"> 
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