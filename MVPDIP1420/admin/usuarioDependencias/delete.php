<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/empleados_dependencias.php";
	include __DIR__."/../functions/usuarios_permisos.php";
	include __DIR__."/../functions/dependencias.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start(); 
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId_1",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	$id;
	echo $redirectSecurity=redirectSecurity($id,'usuarios_modulos','usuarioDependencias','index');
	if($redirectSecurity!=""){
		die;
	}
	$empleado_dependenciaDatos=empleado_dependenciaDatos($id);
	$id=$empleado_dependenciaDatos['id']; 
	$id_dependencia=$empleado_dependenciaDatos['id_dependencia']; 
	$dependenciaDatos=dependenciaDatos($id_dependencia);
	$id_empleado = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	$empleadoDatos=empleadoDatos($id_empleado);


	validar_plataforma_vista($id_empleado,'empleados','adminGenerales','index',$codigo_plataforma);
	$moduloAccionPermisos = moduloAccionPermisos('configuracion','empleados_permisos',$_COOKIE["id_usuario"]);
	?>
	<title>Delete</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="usuarioDependencias/index.php";
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
				url: "usuarioDependencias/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="usuarioDependencias/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
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
					<font style="font-size: 25px;">Eliminar Dependencia</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;"></font><br><br>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Nombre</label><br>
			<label class="descripcionForm">
				<strong><?= $empleadoDatos['nombre_empleado'] ?></strong>
			</label><br><br>

			
			<label class="labelForm">
				<font color="grey">Dependencia:</font> <strong><?= $dependenciaDatos["nombre"]?></strong><br><br>
			</label><br>
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