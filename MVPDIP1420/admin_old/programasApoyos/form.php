<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','programas_apoyos',$_COOKIE["id_usuario"]);
	if(empty($moduloAccionPermisos)){
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
	<script type="text/javascript">
		$( function() {
			$( "#fecha_inicio" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_inicio").style.border= "";
				}
			}); 
			$( "#fecha_final" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_final").style.border= "";
				}
			}); 
		});
	</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos  Programas Apoyos</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" style="width: 100%" name="clave" autocomplete="off"  id="clave" value="<?= $programa_apoyoDatos['clave'] ?>" placeholder="Clave" onkeyup="clave(this.value)" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Folio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="folio" autocomplete="off"  id="folio" value="<?= $programa_apoyoDatos['folio'] ?>" onkeyup="aMays(event, this)" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Nombre<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" style="width: 100%" name="nombre" autocomplete="off"  id="nombre" value="<?= $programa_apoyoDatos['nombre'] ?>" placeholder="Nombre" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<?php
				$select[$programa_apoyoDatos['tipo']] = 'selected="selected"';
			?>
			<select name="tipo" id="tipo" class='myselect'>  
				<option value="">Seleccione</option>
				<option <?= $select['monetario'] ?> value="monetario">Monetario</option>
				<option <?= $select['especie'] ?> value="especie">Especie</option>
			</select>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Inicio<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_inicio" autocomplete="off"  id="fecha_inicio" value="<?= $programa_apoyoDatos['fecha_inicio'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Final<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_final" autocomplete="off"  id="fecha_final" value="<?= $programa_apoyoDatos['fecha_final'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Descripción<font color="#FF0004">*</font></label><br>
			<textarea id="descripcion" style="width: 99%;height: 150px"><?= $programa_apoyoDatos['descripcion'] ?></textarea> <br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Territorios</label>
		</div>
		<?php

		foreach ($tipos_territoriosDatos as $key => $value) {
			if($programas_apoyos_territoriosIdDatos[$value['id']]['id']!=''){
				$checked = 'checked="checked"';
			}else{
				$checked = '';
			}
			?>
			<div class="sucForm" style="padding:5px 20px 5px 20px ">
				<input <?= $checked ?> class="inputlogin" type="checkbox" name="chk_tt<?= $value['id'] ?>" autocomplete="off"  id="chk_tt<?= $value['id'] ?>" value=""/>
				<label class="labelForm" for="chk_tt<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
			</div>
			<?php
		}
		?>
		<div class="sucFormTitulo" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Categorías</label>
		</div>
		<?php
		foreach ($categorias_programas_apoyosDatos as $key => $value) {
			if($programas_apoyos_categoriasIdDatos[$value['id']]['id']!=''){
				$checked = 'checked="checked"';
			}else{
				$checked = '';
			}
			?>
			<div class="sucForm" style="padding:5px 20px 5px 20px ">
				<input <?= $checked ?> class="inputlogin" type="checkbox" name="chk_cpa<?= $value['id'] ?>" autocomplete="off"  id="chk_cpa<?= $value['id'] ?>" value=""/>
				<label class="labelForm" for="chk_cpa<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
			</div>
			<?php
		}
		?>
		<div class="sucFormTitulo" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Dependencia<font color="#FF0004">*</font></label><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<select id="id_dependencia" class='myselect'>
				<?php echo dependencias($programa_apoyoDatos['id_dependencia']); ?>
			</select>
			<br>
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