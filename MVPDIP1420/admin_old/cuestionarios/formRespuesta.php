	<?php
		include __DIR__."/../functions/genid.php";
		$respuestaDatos['clave']="RESP-".$cod16M;
		$claveFRespuesta['input'] = 'disabled="disabled"';
		@session_start(); 
		if(!empty($_POST)){
			$num = $_POST['num'];
			//var_dump($_SESSION['image'][$num]);


			$base64 = base64_encode($_SESSION['image'][$num]['imagePrint']);
			$respuestaDatos['clave'] = $_SESSION['respuesta'][$num]['clave'];
			$respuestaDatos['orden'] = $_SESSION['respuesta'][$num]['orden'];
			$respuestaDatos['respuesta'] = $_SESSION['respuesta'][$num]['respuesta'];

			$texto_boton = "Editar Respuesta";
			$actionRespuesta = "editarRespuesta";

		}else{
			$texto_boton = "Crear Respuesta";
			$actionRespuesta = "guardarRespuesta";
			if($permiso=='update'){
				$respuestaDatos['orden'] = $key+2;
			}
		}
	?>

	<div class="sucForm" style="width: 100%">
		<div id="mensajeRespuesta" class="mensajeSolo" ><br></div>
	</div>
	<input type="hidden" id="respuesta_num" name="respuesta_num" value="<?= $num ?>">
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
		<input class="inputlogin" <?= $claveFRespuesta['input'] ?> type="text" style="width: 100%" name="respuesta_clave" autocomplete="off"  id="respuesta_clave" value="<?= $respuestaDatos['clave'] ?>" placeholder="Clave" onblur="aMays(event, this)" /><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Orden<font color="#FF0004">*</font></label><br>
		<input  class="inputlogin" type="text" name="respuesta_orden" autocomplete="off"  id="respuesta_orden" value="<?= $respuestaDatos['orden'] ?>" placeholder="" onkeypress="return CheckNumeric()" maxlength="4" /><br>
	</div>
	<div class="sucForm" style="width: 100%">
		<label class="labelForm" id="labeltemaname">Respuesta<font color="#FF0004">*</font></label><br>
		<input  class="inputlogin" type="text" name="respuesta_respuesta" autocomplete="off"  id="respuesta_respuesta" value="<?= $respuestaDatos['respuesta'] ?>" placeholder="Peor" maxlength="255" /><br>
	</div>
	<div class="sucForm">
		<input type="button" id="sumbmitRespuesta" style="float: left;" onclick="<?= $actionRespuesta ?>('mas')" value="<?= $texto_boton ?>">
	</div>