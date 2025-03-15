<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/tipos_actividades.php"; 
	include '../functions/usuario_permisos.php';
	@session_start(); 
	$_SESSION['Paguinasub']="tiposActividades/delete.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'tipos_actividades','tiposActividades','index');
	if($redirectSecurity!=""){
		die;
	}
	$tipo_actividadDatos=tipo_actividadDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','tipos_actividades',$_COOKIE["id_usuario"]);
?>
	<title>Delete </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('tiposActividades/index.php');
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
			//var dataString = 'id=<?=$id;?>';
			var dataString = 'id=<?=$id;?>';
			$.ajax({
				type: "POST",
				url: "tiposActividades/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('tiposActividades/index.php');  

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
					<font style="font-size: 25px;">Eliminar Tipo Actividad</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Tipo Actividad</label><br>
			<label class="descripcionForm">
				<strong><?= $tipo_actividadDatos['nombre']?></strong>
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