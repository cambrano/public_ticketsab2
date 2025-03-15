<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','casillas_votos_2016',$_COOKIE["id_usuario"]);
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
	<script type="text/javascript">
		function votos_validos(){
			var votos = 0;
			<?php
			foreach ($partidos_2016Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_validos = document.getElementById("votos_validos").value=votos.toLocaleString("ja-JP"); 
			votos_totales();
		}

		function votos_totales(){
			var votos = 0;
			var votos_nulos = document.getElementById("votos_nulos").value;
			if(votos_nulos==""){
				votos_nulos = 0;
				document.getElementById("votos_nulos").value =0;
			}
			votos = parseInt(votos_nulos) + votos;
			var votos_can_nreg = document.getElementById("votos_can_nreg").value;
			if(votos_can_nreg==""){
				votos_can_nreg = 0;
				document.getElementById("votos_can_nreg").value =0;
			}
			votos = parseInt(votos_can_nreg) + votos;
			<?php
			foreach ($partidos_2016Datos as $key => $value) {
				?>
				var votos_partido_<?= $value['id'] ?> = document.getElementById("votos_partido_<?= $value['id'] ?>").value; 
				if(votos_partido_<?= $value['id'] ?>!=""){
					votos = parseInt(votos_partido_<?= $value['id'] ?>) + votos;
				}else{
					document.getElementById("votos_partido_<?= $value['id'] ?>").value = 0;
				}
				<?php
			}
			?>
			var votos_totales = document.getElementById("votos_totales").value=votos.toLocaleString("ja-JP"); 
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Casilla Voto 2016</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $casilla_voto_2016Datos['clave'] ?>" placeholder="" maxlength="120" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Secciones<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_seccion_ine" >
				<?php
				echo secciones_ine($casilla_voto_2016Datos['id_seccion_ine'],$id_municipio,$id_distrito_local,$id_distrito_federal,'');
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo Casilla<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_casilla">
				<?php
				echo tipos_casillas($casilla_voto_2016Datos['id_tipo_casilla']);
				?>
			</select><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Código<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="codigo" autocomplete="off"  id="codigo" value="<?= $casilla_voto_2016Datos['codigo'] ?>" placeholder="Código" onblur="aMays(event, this)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Lista Nominal<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="lista_nominal" autocomplete="off"  id="lista_nominal" value="<?= $casilla_voto_2016Datos['lista_nominal'] ?>" placeholder="Lista Nominal" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="status" autocomplete="off"  id="status" value="<?= $casilla_voto_2016Datos['status'] ?>" placeholder="estatus" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Votos</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Totales<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_totales" autocomplete="off"  id="votos_totales" value="<?= $votos_totales ?>" placeholder="Votos Totales" onkeypress="return CheckNumeric()" /><br>
		</div>
		<div class="sucForm" style="width: 100%"></div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Válidos<font color="#FF0004">*</font></label><br>
			<input  class="inputlogin" disabled="disabled" type="text" style="width: 100%" name="votos_validos" autocomplete="off"  id="votos_validos" value="<?= $votos_validos ?>" placeholder="Votos Válidos" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Nulos<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_nulos" autocomplete="off"  id="votos_nulos" value="<?= $casilla_voto_2016Datos['votos_nulos'] ?>" placeholder="Votos Nulos" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos CAN NREG<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="votos_can_nreg" autocomplete="off"  id="votos_can_nreg" value="<?= $casilla_voto_2016Datos['votos_can_nreg'] ?>" placeholder="Votos CAN NREG" onkeypress="return CheckNumeric()" onchange="votos_totales()"/><br>
		</div>

		<style type="text/css">
			.mobile_mode{
				width: 30%;
				background-color: #f4f4f2
			}
			@media screen and (max-width: 930px) {
				.mobile_mode{
					width: 49%;
					background-color: #f4f4f2
				}
			}
			@media screen and (max-width: 820px) {
				.mobile_mode{
					width: 100%;
					background-color: #f4f4f2
				}
			}
		</style>

		<div class="sucForm" style="width: 100%;">
			<?php
			foreach ($partidos_2016Datos as $key => $value) {
				$nombre_corto = strtr($value['nombre_corto'], "_", " ");

				?>
				<div class="sucForm mobile_mode">
					<div class="sucForm" style="width:30%">
						<img style="width:80% " src="images/logos_partidos/<?= $value['logo'] ?>">
					</div>
					<div class="sucForm" style="width: 60%">
						<label class="labelForm" id="labeltemaname" style="font-size: 9px"><?= $nombre_corto ?><font color="#FF0004">*</font></label><br>
						<input class="inputlogin" type="text" style="width: 100%" name="votos_partido_<?= $value['id'] ?>" autocomplete="off"  id="votos_partido_<?= $value['id'] ?>" value="<?= $value['votos'] ?>" placeholder="Número Votos" onkeypress="return CheckNumeric()" onchange="votos_validos()"/><br>
					</div>
				</div>
				<?php
			}
			?>
		</div>

		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
				<?php
			}
			?>
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div>
	<script type="text/javascript">
		$(".myselect").select2();
	</script>