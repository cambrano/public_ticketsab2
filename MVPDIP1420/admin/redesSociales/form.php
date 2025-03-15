<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','redes_sociales',$_COOKIE["id_usuario"]);
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
	<link rel="stylesheet" href="css/pro.min.css">
	<script type="text/javascript">
		function mostrar_icono() {
			var icono = document.getElementById("icono").value;
			if(icono == ""){
				document.getElementById("icono").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Icono requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var font_awesome_icono = [];
			var data = {
					'icono' : icono, 
				}
			font_awesome_icono.push(data);
			$.ajax({
				type: "POST",
				url: "fontAwesomeIconos/ajax.php",
				data: {font_awesome_icono: font_awesome_icono},
				success: function(data) {
					$("#mostrar_icono").html(data);
				}
			});
		}
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Redes Sociales</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $red_socialDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="nombre" autocomplete="off"  id="nombre" value="<?= $red_socialDatos['nombre'] ?>" placeholder="" maxlength="120"/><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Icono</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Icono<font color="#FF0004">*</font></label>
			<input class="inputlogin" type="text" name="icono" autocomplete="off"  id="icono" value="<?= $red_socialDatos['icono']  ?>" maxlength="d" />
			<input type="button" name="Mostrar Icono" value="Mostrar Icono" onclick="mostrar_icono()">
			<br><br>
		</div>
		<div class="sucForm" style="width: 45%">
			<div id="mostrar_icono">
				<li class="<?= $red_socialDatos['icono'] ?> fa-2x"></li>
			</div>
		</div>
		
		<div class="sucForm" style="width: 100%">
			<ol>
				<li>Busque su icono aquí <a href="https://fontawesome.com/icons" target="_blank"> Galería de Iconos</a>.</li>
				<li>Seleccione su icono.</li>
				<li>Copie y pegue un código ejemplo asi &lt;i class="<b>fab fa-500px</b>">  &lt;/i></li>
				<li>Solo copie un código así fab fa-500px y péguelo en el campo icono. </li>
				<li>Presione Mostrar Icono para saber si se encuentra correcto el icono.</li>
				<li>Algunos Iconos PRO son de compra.</li>
			</ol>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Tipo</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">tipo<font color="#FF0004">*</font></label><br>
			<select   name="tipo" id="tipo" class='myselect'>
				<option <?= ('url' == $red_socialDatos['tipo'] ) ? 'selected="selected"' : ''; ?> value="url">Url</option>
				<option <?= ('@' == $red_socialDatos['tipo'] ) ? 'selected="selected"' : ''; ?> value="@">@</option>
				<option <?= ('id' == $red_socialDatos['tipo'] ) ? 'selected="selected"' : ''; ?> value="id">Id</option>
			</select>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">URL<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url" autocomplete="off"  id="url" value="<?= $red_socialDatos['url'] ?>" placeholder="" maxlength="120"/><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<?php
				if(!empty($id)){

					echo '<label class="labelForm" id="labeltemaname">Estatus<font color="#FF0004">*</font></label><br>';
					echo '<select id="status" class="myselect" name="status" >';
					echo statusGeneralForm($red_socialDatos['status']);
					echo '</select><br><br>';
				} 
			?> 
		</div>


		<div class="sucForm" style="width: 100%">
			<br>
			<?php
			if($moduloAccionPermisos[$permiso] || $moduloAccionPermisos['all']){
				?>
				<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
				<?php
			}
			?>
				<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>