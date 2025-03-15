<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/casillas_votos_2018.php";
	include __DIR__."/../functions/casillas_votos_partidos_2018.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/partidos_2018.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/tipos_casillas.php";
	@session_start(); 
	$_SESSION['Paguinasub']="casillasVotos2018/update.php";  
	if(!empty($_GET)){
		$id=$_SESSION['paguinaId']=$_GET['id'];
	}else{
		$id=$_SESSION['paguinaId'];
	}
	$redirectSecurity=redirectSecurity($id,'casillas_votos_2018','casillasVotos2018','index');
	//var_dump($redirectSecurity);
	if($redirectSecurity!=""){
		die;
	}

	$claveF= clave('casillas_votos_2018');
	$casilla_voto_2018Datos=casilla_voto_2018Datos($id);
	if($casilla_voto_2018Datos['clave']==""){
		$casilla_voto_2018Datos['clave']=$claveF['clave'];
	}

	$tipo = $casilla_voto_2018Datos['tipo'];

	$partidos_2018Datos = partidos_2018Datos('','',$tipo);

	$casillas_votos_partidos_2018Datos = casillas_votos_partidos_2018Datos('',$id,'');

	$votos_validos = 0;
	foreach ($partidos_2018Datos as $key => $value) {
		foreach ($casillas_votos_partidos_2018Datos as $keyT => $valueT) {
			if($value['id']==$valueT['id_partido_2018']){
				$partidos_2018Datos[$key]['votos'] = $valueT['votos'];
				$votos_validos = $valueT['votos'] + $votos_validos;
			}
		}
	}


	$votos_totales = $votos_validos + $casilla_voto_2018Datos['votos_can_nreg'] + $casilla_voto_2018Datos['votos_nulos'];

	$permiso="update";
	?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			$("#homebody").load('casillasVotos2018/index.php');
		}
		 

		function guardar() {
			var coma= /,/g;
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_seccion_ine = document.getElementById("id_seccion_ine").value; 
			if(id_seccion_ine == ""){
				document.getElementById("id_seccion_ine").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sección requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var id_tipo_casilla = document.getElementById("id_tipo_casilla").value; 
			if(id_tipo_casilla == ""){
				document.getElementById("id_tipo_casilla").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo Casilla requerido");
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

			var status = document.getElementById("status").value; 
			if(status == ""){
				document.getElementById("status").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

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

			var casilla_voto_2018 = [];
			var data = {
					'id' : id,
					'clave' : clave,
					'id_seccion_ine' : id_seccion_ine,
					'id_tipo_casilla' : id_tipo_casilla,
					'codigo' : codigo,
					'lista_nominal' : lista_nominal,
					'status' : status,
					'votos_nulos' : votos_nulos,
					'votos_can_nreg' : votos_can_nreg,
					'tipo' : '<?= $tipo ?>',
				}
			casilla_voto_2018.push(data);

			///partidos
			var votos_partidos_2018 = [];
			<?php
			foreach ($partidos_2018Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				var votos_partido_<?= $value['id'] ?>=  votos_partido_<?= $value['id'] ?>.replace(coma,'');
				if(votos_partido_<?= $value['id'] ?> == ""){
					document.getElementById("votos_partido_<?= $value['id'] ?>").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Votos <?= $value['nombre_corto'] ?> requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
				var id_partido_2018 = '<?= $value['id'] ?>';
				var data = {
						'id_partido_2018' : id_partido_2018,
						'votos' : votos_partido_<?= $value['id'] ?>,
						'tipo' : '<?= $tipo ?>',
					}
				votos_partidos_2018.push(data);
				<?php
			}
			?>

			$.ajax({
				type: "POST",
				url: "casillasVotos2018/db_edit.php",
				data: {casilla_voto_2018: casilla_voto_2018,votos_partidos_2018:votos_partidos_2018},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						$("#homebody").load('casillasVotos2018/index.php');
					}else{
						if(data==""){
							$("#homebody").load('casillasVotos2018/index.php');
						}else{
							document.getElementById("sumbmit").disabled = false;
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							
						}
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
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Casilla Voto Pasada<br></font>
					<?php

					if($tipo==0){
						?>
						<font style="font-size: 25px;">Ayuntamiento</font>
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

					?>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a casilla voto 2018.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>