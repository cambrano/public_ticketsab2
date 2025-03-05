<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/partidos_2024.php";
	include __DIR__."/../functions/casillas_votos_2024.php";
	include __DIR__."/../functions/casillas_votos_2024_status.php";
	include __DIR__."/../functions/casillas_votos_2024_incidencias.php";
	include __DIR__."/../functions/casillas_votos_partidos_2024.php";
	include __DIR__."/../functions/elecciones.php";
	include '../functions/switch_operaciones.php';
	include '../functions/secciones_ine_ciudadanos_permisos.php';
	@session_start();
	$elecciones = eleccionesModulo('2024');
	$ano = $elecciones['distritos_federales'];

	$entra=true;
	$tipo = '2';

	$seccion_ine_ciudadano_permisosDatos = seccion_ine_ciudadano_permisosDatos('','',$_COOKIE["id_usuario"]);
	$switch_operacionesPermisos = switch_operacionesPermisos();
	if($switch_operacionesPermisos['casilla'] && $seccion_ine_ciudadano_permisosDatos['casilla'] == "1"){
		$casilla = true;
	}else{
		$casilla = false;
	}
	include '../functions/usuarios.php';
	$usuarioDatos = usuarioDatos($_COOKIE["id_usuario"]);
	$id_seccion_ine = $usuarioDatos['id_seccion_ine'];

	$seccion_ineDatos = seccion_ineDatos($usuarioDatos['id_seccion_ine']);
	$id_casilla_voto_2024 = $_COOKIE["id_casilla_voto_partidos"];



	if($id_casilla_voto_2024!="" && $entra){
		$input_dis ='disabled="disabled"';
		$boton_dis = 'style="background-color: black; color: yellow; cursor: not-allowed;"';

		$casilla_voto_2024Datos=casilla_voto_2024Datos($id_casilla_voto_2024,'',$tipo);
		
		$partidos_2024Datos = partidos_2024Datos('','',$tipo);
		if($tipo=='x'){
			$tipo = '0';
		}

 
		$id_casilla_voto_2024;
		$casillas_votos_partidos_2024Datos = casillas_votos_partidos_2024Datos('',$id_casilla_voto_2024,'');
		$votos_validos = 0;
		foreach ($partidos_2024Datos as $key => $value) {
			foreach ($casillas_votos_partidos_2024Datos as $keyT => $valueT) {
				if($value['id']==$valueT['id_partido_2024']){
					$partidos_2024Datos[$key]['votos'] = $valueT['votos'];
					$votos_validos = $valueT['votos'] + $votos_validos;
				}
			}
		}
		$votos_totales = $votos_validos + $casilla_voto_2024Datos['votos_can_nreg'] + $casilla_voto_2024Datos['votos_nulos'];
	}

	$casilla_voto_2024_statusDatosAll = casilla_voto_2024_statusDatosAll('',$_COOKIE["id_casilla_voto_partidos"],'ORDER BY fecha_hora DESC LIMIT 1');
	$casilla_voto_2024_incidenciasDatos = casilla_voto_2024_incidenciasDatos('',$_COOKIE["id_casilla_voto_partidos"],'ORDER BY fecha_hora DESC LIMIT 1');

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
					'tipo' : '<?= $tipo ?>',
				}
			casilla_voto.push(data);
			$.ajax({
				type: "POST",
				url: "casillasVotos2024/asignar_casilla.php",
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
					location.reload();
				}
			});
		}
		function desbloquear_casilla(){
			document.getElementById("sumbmit").disabled = false;
			document.getElementById("sumbmit").style.cursor = "pointer";
			document.getElementById("sumbmit").style.color = "black";
			document.getElementById("sumbmit").style.backgroundColor = "#dbdcff";
			document.getElementById("id_casilla_voto_2024").disabled = false;
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
			if($casilla==0){
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
			<?php

			if($tipo==0){
				$tipo='x';
				?>
				<font style="font-size: 25px;">Municipal</font>
				<?php
			}elseif ($tipo==1) {
				?>
				<font style="font-size: 25px;">Distrito Local</font>
				<?php
			}else{
				?>
				<font style="font-size: 25px;">Distrito Federal</font>
				<?php
			}

			?><br>
			Información Casilla <?= $ano ?>
		</label><br>
		<div style="float: right; width: 100%; text-align: left;"> 
		</div>
		<br>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Casilla</label><br>
			<select id="id_casilla_voto_2024" class='myselect' <?= $input_dis ?> >
				<?= casillas_votos_2024($id_casilla_voto_2024,$id_seccion_ine,$tipo) ?>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">&nbsp;</label><br>
			<input type="button" id="sumbmit" onclick="asignar_casilla()" value="Activar Casilla" <?= $boton_dis ?> <?= $input_dis ?>>
			<input type="button" id="desbloquear" onclick="desbloquear_casilla()" value="Desactivar Casilla">
		</div>
		<div style=" width: 100%;display: block;float: left;">
			<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
		</div>

		<script language="javascript" type="text/javascript">
			function guardar() {
				var coma= /,/g;
				document.getElementById("sumbmit").disabled = true;
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
				var id = '<?= $id_casilla_voto_2024?>';
				if(id == ""){
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Casilla requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}


				var codigo = document.getElementById("codigo").value; 
				if(codigo == ""){
					document.getElementById("codigo").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Nombre requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}

				var lista_nominal = document.getElementById("lista_nominal").value; 
				if(lista_nominal==0){
					document.getElementById("lista_nominal").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Monto no puede ser 0 Lista Nominal requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var lista_nominal = document.getElementById("lista_nominal").value; 
				var lista_nominal=  lista_nominal.replace(coma,'');
				if(lista_nominal == ""){
					document.getElementById("lista_nominal").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Lista Nominal requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}

				/*
				var status = document.getElementById("status").value; 
				if(status == ""){
					document.getElementById("status").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Estatus requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				*/

				var votos_nulos = document.getElementById("votos_nulos").value; 
				var votos_nulos=  votos_nulos.replace(coma,'');
				if(votos_nulos == ""){
					document.getElementById("votos_nulos").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Votos Nulos requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}

				var votos_can_nreg = document.getElementById("votos_can_nreg").value; 
				var votos_can_nreg=  votos_can_nreg.replace(coma,'');
				if(votos_can_nreg == ""){
					document.getElementById("votos_can_nreg").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Votos CAN NREG requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}


				var casilla_voto_2024 = [];
				var data = {
						'id' : id,
						/*'codigo' : codigo,*/
						/*'lista_nominal' : lista_nominal,*/
						/*'status' : status,*/
						'votos_nulos' : votos_nulos,
						'votos_can_nreg' : votos_can_nreg,
						'tipo' : '<?= $tipo ?>',
					}
				casilla_voto_2024.push(data);


				///partidos
				///partidos
				var votos_partidos_2024 = [];

				<?php
				foreach ($partidos_2024Datos as $key => $value) {
					?>var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				var votos_partido_<?= $value['id'] ?>=  votos_partido_<?= $value['id'] ?>.replace(coma,'');
				if(votos_partido_<?= $value['id'] ?> == ""){
					document.getElementById("votos_partido_<?= $value['id'] ?>").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Votos <?= $value['nombre_corto'] ?> requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var id_partido_2024 = '<?= $value['id'] ?>';
				var data = {
						'id_partido_2024' : id_partido_2024,
						'votos' : votos_partido_<?= $value['id'] ?>,
						'tipo' : '<?= $tipo ?>',
						}
				votos_partidos_2024.push(data);


				<?php
				}
				?>

				$.ajax({
					type: "POST",
					url: "casillasVotos2024/db_edit.php",
					data: {casilla_voto_2024: casilla_voto_2024,votos_partidos_2024:votos_partidos_2024},
					success: function(data) {
						if(data=="SI"){
							document.getElementById("sumbmit").disabled = true;
							document.getElementById("mensaje").classList.remove("mensajeError");
							$("#mensaje").html("&nbsp;");
							$("#mensaje").html("Guardado con éxito"); 
							document.getElementById("mensaje").classList.add("mensajeSucces");
							//$("#homebody").load('casillasVotos2024/index.php');
						}else{
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
						}
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

		<div class="bodyinput">
			<?php 
				include "form.php";
			?>
		</div>
	</div>