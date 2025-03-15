<?php
	include '../functions/usuario_permisos.php';
?>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<?php
			foreach ($eleccionesDatos as $key => $value) {
				?>
				<div class="sucFormTitulo">
					<label class="labelForm" id="labeltemaname">Modulo:<?= $value['modulo'] ?></label>
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">municipios<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="municipios_<?= $value['id'] ?>" autocomplete="off" id="municipios_<?= $value['id'] ?>" value="<?= $value['municipios'] ?>" onkeyup="clave(this.value)" /><br>
				</div>

				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">municipios_show<font color="#FF0004">*</font></label><br>
					<select id="municipios_show_<?= $value['id'] ?>" class="myselect" name="municipios_show_<?= $value['id'] ?>" >
						<?php
						echo statusGeneralForm($value['municipios_show']);
						?>
					</select>
				</div>
				<div class="sucForm" style="width:100%">
				</div>
				
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">distritos_locales<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="distritos_locales_<?= $value['id'] ?>" autocomplete="off" id="distritos_locales_<?= $value['id'] ?>" value="<?= $value['distritos_locales'] ?>" onkeyup="clave(this.value)" /><br>
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">distritos_locales_show<font color="#FF0004">*</font></label><br>
					<select id="distritos_locales_show_<?= $value['id'] ?>" class="myselect" name="distritos_locales_show_<?= $value['id'] ?>" >
						<?php
						echo statusGeneralForm($value['distritos_locales_show']);
						?>
					</select>
				</div>
				<div class="sucForm" style="width:100%">
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">distritos_federales<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="distritos_federales_<?= $value['id'] ?>" autocomplete="off" id="distritos_federales_<?= $value['id'] ?>" value="<?= $value['distritos_federales'] ?>" onkeyup="clave(this.value)" /><br>
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">distritos_federales_show<font color="#FF0004">*</font></label><br>
					<select id="distritos_federales_show_<?= $value['id'] ?>" class="myselect" name="distritos_federales_show_<?= $value['id'] ?>" >
						<?php
						echo statusGeneralForm($value['distritos_federales_show']);
						?>
					</select>
				</div> 
				<div class="sucForm" style="width:100%"></div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">senador<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="senador_<?= $value['id'] ?>" autocomplete="off" id="senador_<?= $value['id'] ?>" value="<?= $value['senador'] ?>" onkeyup="clave(this.value)" /><br>
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">senador_show<font color="#FF0004">*</font></label><br>
					<select id="senador_show_<?= $value['id'] ?>" class="myselect" name="senador_show_<?= $value['id'] ?>" >
						<?php
						echo statusGeneralForm($value['senador_show']);
						?>
					</select>
				</div>
				<div class="sucForm" style="width:100%"></div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">gobernador<font color="#FF0004">*</font></label><br>
					<input class="inputlogin" type="text" name="gobernador_<?= $value['id'] ?>" autocomplete="off" id="gobernador_<?= $value['id'] ?>" value="<?= $value['gobernador'] ?>" onkeyup="clave(this.value)" /><br>
				</div>
				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">gobernador_show<font color="#FF0004">*</font></label><br>
					<select id="gobernador_show_<?= $value['id'] ?>" class="myselect" name="gobernador_show_<?= $value['id'] ?>" >
						<?php
						echo statusGeneralForm($value['gobernador_show']);
						?>
					</select>
				</div>
				<div class="sucForm" style="width:100%"></div>
				<?php
			}
		?>

		<div class="sucForm" style="width: 100%">
			<br>
			<input type="button" id="sumbmit" onclick="guardar()" value="Guardar">
			<!--<input type="button" onclick="ResetInput()" value="Borrar">-->
			<input type="button" value="Cancelar" onclick="cerrar()">
		</div>
	</div> 
	<script type="text/javascript">
		$(".myselect").select2();
	</script>