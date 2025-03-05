<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include '../functions/switch_operaciones.php';
	include '../functions/secciones_ine_ciudadanos_permisos.php';
	include '../functions/usuarios.php';
	$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
	@session_start();
	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine = $usuarioDatos['id_seccion_ine'];
	$seccion_ineDatos = seccion_ineDatos($usuarioDatos['id_seccion_ine']);
	$id_casilla_voto_2024 = $_COOKIE["id_casilla_voto"];
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['entrega'] && $seccion_ine_ciudadano_permisosDatos['entrega'] == "1"){
		$entrega = true;
	}else{
		$entrega = false;
	}

	if($switch_operacionesPermisos['recibe'] && $seccion_ine_ciudadano_permisosDatos['recibe'] == "1"){
		$recibe = true;
	}else{
		$recibe = false;
	}

	if($id_casilla_voto_2024!=""){
		$input_dis ='disabled="disabled"';
		$boton_dis = 'style="background-color: black; color: yellow; cursor: not-allowed;"';
	}

?>
	<script type="text/javascript">
		function asignar_casilla(){
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");

			var id_casilla_voto_2024 = document.getElementById("id_casilla_voto_2024").value;
			if(id_casilla_voto_2024==""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Casilla requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var casilla_voto = [];
			var data = {
					'id_casilla_voto_2024' : id_casilla_voto_2024, 
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanosEntrega/asignar_casilla.php",
				data: {casilla_voto: casilla_voto},
				async: true,
				success: function(data) {
					document.getElementById("mensaje").classList.add("mensajeSucces");
					$("#mensaje").html(data);
					document.getElementById("sumbmit").disabled = true;
					document.getElementById("sumbmit").style.cursor = "not-allowed";
					document.getElementById("sumbmit").style.backgroundColor = "black";
					document.getElementById("sumbmit").style.color = "yellow";
					document.getElementById("id_casilla_voto_2024").disabled = true;

				}
			});
		}
		function desbloquear_casilla(){
			document.getElementById("sumbmit").disabled = false;
			document.getElementById("sumbmit").style.cursor = "pointer";
			document.getElementById("sumbmit").style.color = "black";
			document.getElementById("sumbmit").style.backgroundColor = "#dbdcff";
			$('#id_casilla_voto_2024').prop('disabled', false);
			$('#id_casilla_voto_2024').selectpicker('refresh');
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
	<title>Ciudadanos</title>
	<div id="bodymanager" class="bodymanager">
		<div id="mensaje" class="mensajeSolo" ></div>
		<?php
			if($entrega == false && $recibe == false){
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
		<label class="tituloForm">
			Ciudadanos Checks
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
		</div>
		<br><br>
		<div id="mapaLoad">
			<?php /*include "mapa.php"; */?>
		</div> 
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">Casillas</label><br>
			<select id="id_casilla_voto_2024" class='myselect' <?= $input_dis ?> >
				<?= casillas_votos_2024($id_casilla_voto_2024,$id_seccion_ine) ?>
			</select>
		</div>
		<div class="sucForm" style="display:none">
			<label class="labelForm" id="labeltemaname">&nbsp;</label><br>
			<input type="button" id="sumbmit" onclick="asignar_casilla()" value="Bloquear Casilla" <?= $boton_dis ?> <?= $input_dis ?>>
			<input type="button" id="desbloquear" onclick="desbloquear_casilla()" value="Desbloquear Casilla">
		</div>
		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div><?php include "filtros.php"; ?></div>
		<div style="clear: both;"></div>
		<div id="dataTable">
			<?php include "table.php"; ?>
		</div> 
	</div>