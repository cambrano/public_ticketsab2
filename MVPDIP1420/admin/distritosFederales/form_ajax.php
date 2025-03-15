<?php
	if(!empty($_POST)){
		$num = $_POST['num'];
		@session_start();
		$_SESSION['limites'][$num];
		?>
		<div id="mensaje_limit"></div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
				<input type="hidden" name="numero" id="numero_limite" value="<?= $_SESSION['limites'][$num]['numero'] ?>" placeholder="numero" autocomplete="off">
				<input type="hidden" name="id" id="id_limite" value="<?= $_SESSION['limites'][$num]['id'] ?>" placeholder="id" autocomplete="off">
				<input type="text" name="latitud" id="orden_limite" value="<?= $_SESSION['limites'][$num]['orden'] ?>" placeholder="orden" autocomplete="off"><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
				<input type="text" name="latitud" id="latitud_limite" value="<?= $_SESSION['limites'][$num]['latitud'] ?>" placeholder="latitud" autocomplete="off" onkeypress="return CheckNumeric()" ><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
				<input type="text" name="longitud" id="longitud_limite" value="<?= $_SESSION['limites'][$num]['longitud'] ?>" placeholder="longitud" autocomplete="off" onkeypress="return CheckNumeric()" >
			</div>
			<div class="sucForm" style="width:100%">
				<input type="button" value="Generar Limite" onclick="generar_limite()">
			</div>
		<?php
	}