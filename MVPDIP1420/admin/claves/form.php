	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Claves</label>
		</div>
		<?php
		foreach ($inputs as $key => $value) {
			if(substr($value, 0, 6)!="forma_"){
				echo "<div class='sucForm' ";
				echo '<label class="labelForm" id="labeltemaname">'.ucwords(strtolower(str_replace("_", " ", $value))).'<font color="#FF0004">*</font></label><br>';
				echo "<input class='inputlogin'  type='text' name='{$key}' autocomplete='off' id='{$key}' value='".$claveDatos[$key]."' placeholder=''  onblur='aMays(event, this)' maxlength='20' /><br>";
				echo "</div>";
			}else{
				echo "<div class='sucForm'>";
				unset($sf_select);
				echo "";
				echo '<label class="labelForm" id="labeltemaname"></label><br>';
				echo "<select id='{$key}' class='myselect' >";
				$sf_select[$claveDatos[$value]]='selected="selected"';
				echo "<option ".$sf_select["automatico"]." value='automatico' >Automático</option>";
				echo "<option ".$sf_select["manual"]." value='manual' >Manual</option>";
				echo "</select><br>";
				echo "</div>";
				echo '<div class="sucForm" style="width: 100%"></div>';
			}
			
		}

		?>

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