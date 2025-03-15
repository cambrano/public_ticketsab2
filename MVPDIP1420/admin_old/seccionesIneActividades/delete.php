<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/secciones_ine_actividades.php";
	@session_start();
	include '../functions/usuario_permisos.php';  
	if(!empty($_GET)){
		$_SESSION['Paguinasub']="seccionesIneActividades/delete.php";
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$id;
	echo $redirectSecurity=redirectSecurity($id,'secciones_ine_actividades','seccionesIneActividades','index');
	if($redirectSecurity!=""){
		die;
	}
	$seccion_ine_actividadDatos=seccion_ine_actividadDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','secciones_ine_actividades',$_COOKIE["id_usuario"]);
?>
	<title>Delete</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('seccionesIneActividades/index.php');
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
				url: "seccionesIneActividades/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('seccionesIneActividades/index.php');  

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
					<font style="font-size: 25px;">Eliminar Acción u Obras </font>
				</label><br>
				
			</div>
		</div>
		<div class="bodyinput">
			<br>
			<label class="labelForm" id="labeltemaname">Clave</label><br>
			<label class="descripcionForm">
				<strong><?=$seccion_ine_actividadDatos['clave']; ?></strong>
			</label><br><br>
			<label class="labelForm" id="labeltemaname">Nombre</label><br>
			<label class="descripcionForm">
				<strong><?=$seccion_ine_actividadDatos['nombre']; ?></strong>
			</label><br><br>
			<font style="font-size: 15px;"><strong></strong></font>
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