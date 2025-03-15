<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2021_incidencias.php";
	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/tablas_relacionadas.php";
	@session_start();
	include '../functions/usuario_permisos.php';  
	if(!empty($_GET)){
		$_SESSION['Paguinasub']="casillasVotos2021IncidenciasReportes/delete.php";
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$id_casilla_voto_2021=$_SESSION['id_casilla_voto_2021']; 
	echo $redirectSecurity=redirectSecurity($id,'casillas_votos_2021_incidencias','casillasVotos2021IncidenciasReportes','index');
	if($redirectSecurity!=""){
		die;
	}
	
	$casilla_voto_2021_incidenciaDatos=casilla_voto_2021_incidenciaDatos($id);
	$id = $casilla_voto_2021_incidenciaDatos['id'];

	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2021',$_COOKIE["id_usuario"]);

	$tablasRelacionadas = tablasRelacionadas('casillas_votos_2021_incidencias',$id);
	$registros_titutlo = 'Este registro esta ligado a : <br>';
	foreach ($tablasRelacionadas['tablas'] as $key => $value) {
		if($value['registros']>0){
			$registros_tablas .= ' - '.$value['comentario'].' con '.number_format($value['registros'],0,'',',').' registro(s) <br>';
		}
	}
?>

	<title>Delete</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('casillasVotos2021IncidenciasReportes/index.php');
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
				url: "casillasVotos2021IncidenciasReportes/db_delete.php",
				data: dataString,
				success: function(data) { 
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError"); 
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('casillasVotos2021IncidenciasReportes/index.php');  

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
		<?php
		if( $moduloAccionPermisos['delete'] == false && $moduloAccionPermisos['all'] == false ){
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
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Eliminar Inicidencia Casilla </font>
				</label><br>
				
			</div>
		</div>
		<div class="bodyinput">
			<br>
			<label class="labelForm" id="labeltemaname">Fecha Registro</label><br>
			<label class="descripcionForm">
				<strong><?=$casilla_voto_2021_incidenciaDatos['fechaR']; ?></strong>
			</label><br><br>
			<label class="labelForm" id="labeltemaname">Semáforo</label><br>
			<label class="descripcionForm">
				<?php
				$casilla_voto_2021_incidenciaDatos['semaforo'];
				if($casilla_voto_2021_incidenciaDatos['semaforo'] == 1){
					$casilla_voto_2021_incidenciaDatos['semaforo'] = 'Verde';
				}elseif ($casilla_voto_2021_incidenciaDatos['semaforo'] == 2) {
					$casilla_voto_2021_incidenciaDatos['semaforo'] = 'Amarillo';
				}elseif ($casilla_voto_2021_incidenciaDatos['semaforo'] == 3) {
					$casilla_voto_2021_incidenciaDatos['semaforo'] = 'Rojo';
				}else{
					$casilla_voto_2021_incidenciaDatos['semaforo'] = 'No tiene';
				}

				?>
				<strong><?=$casilla_voto_2021_incidenciaDatos['semaforo']; ?></strong>
			</label><br><br>
			<font style="font-size: 15px;"><strong></strong></font>
			<?php
			if($registros_tablas!=''){
				echo '<div class="mensajeWarning"><font style="font-size:10px">*Sí usted decide borrar se borran los registros relacionados.</font><br>'.$registros_titutlo.'';
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