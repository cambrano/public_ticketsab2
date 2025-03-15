<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/partidos_legados.php"; 
	include '../functions/usuario_permisos.php';
	include __DIR__."/../functions/tablas_relacionadas.php";
	@session_start(); 
	$_SESSION['Paguinasub']="partidosLegados/delete.php";
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	echo $redirectSecurity=redirectSecurity($id,'partidos_legados','partidos2018','index');
	if($redirectSecurity!=""){
		die;
	}
	$partido_legadoDatos=partido_legadoDatos($id);
	$id_imagen=$partido_legadoDatos['id_imagen'];
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','partidos_legados',$_COOKIE["id_usuario"]);
	$tablasRelacionadas = tablasRelacionadas('partidos_legados',$id);
	$registros_titutlo = 'Este registro esta ligado a : <br>';
	foreach ($tablasRelacionadas['tablas'] as $key => $value) {
		if($value['registros']>0){
			$registros_tablas .= ' - '.$value['comentario'].' con '.number_format($value['registros'],0,'',',').' registro(s) <br>';
		}
	}

?>
	<title>Delete </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('partidosLegados/index.php');
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
				url: "partidosLegados/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Eliminado con exito."); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('partidosLegados/index.php');  

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
					<font style="font-size: 25px;">Eliminar Partido</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<label class="labelForm" id="labeltemaname">Partido</label><br>
			<label class="descripcionForm">
				<strong><?= $partido_legadoDatos['nombre']?></strong>
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