<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/equipos_directorios.php"; 
	include __DIR__."/../functions/equipos.php"; 
	include __DIR__."/../functions/directorios.php"; 
	include __DIR__."/../functions/tablas_relacionadas.php";
	include __DIR__."/../functions/tipos_equipos.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start(); 
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'equipos_directorios','equipos','index');
	if($redirectSecurity!=""){
		die;
	}
	
	$moduloAccionPermisos = moduloAccionPermisos('operatividad','equipos',$_COOKIE["id_usuario"]);

	

	$equipo_directorioDatos=equipo_directorioDatos($id);
	$equipoDatos = equipoDatos($equipo_directorioDatos['id_equipo']);
	$directorioDatos = directorioDatos($equipo_directorioDatos['id_directorio']);
	$tipo_equipoDatos=tipo_equipoDatos($equipoDatos['id_tipo_equipo']);
?>
	<title>Delete </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="equipos/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
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
				url: "equipos/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="equipos/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
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
					<font style="font-size: 25px;">Liberar Equipo</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Asignado</label><br>
			<label class="descripcionForm">
				<strong><?= $directorioDatos['nombre']?> <?= $directorioDatos['apellido_paterno']?> <?= $directorioDatos['apellido_materno']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Tipo Equipo</label><br>
			<label class="descripcionForm">
				<strong><?= $tipo_equipoDatos['nombre']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Marca</label><br>
			<label class="descripcionForm">
				<strong><?= $equipoDatos['marca']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Modelo</label><br>
			<label class="descripcionForm">
				<strong><?= $equipoDatos['modelo']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Procesador</label><br>
			<label class="descripcionForm">
				<strong><?= $equipoDatos['procesador']?></strong>
			</label><br>
			<label class="labelForm" id="labeltemaname">Memoria RAM(GB)</label><br>
			<label class="descripcionForm">
				<strong><?= $equipoDatos['ram']?></strong>
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