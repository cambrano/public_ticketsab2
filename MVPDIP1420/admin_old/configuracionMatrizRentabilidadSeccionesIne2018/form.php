	<?php
	if($tipo_uso_plataforma=='municipio'){
		$display_distrito_local = 'style="display: none"';
		$display_distrito_federal = 'style="display: none"';
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$display_municipio = 'style="display: none"';
		$display_distrito_federal = 'style="display: none"';
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$display_municipio = 'style="display: none"';
		$display_distrito_local = 'style="display: none"';
	}
	?>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Configuración Semáforo Amarillo</label>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Votos Diferencia<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="votos_semaforo_amarillo" autocomplete="off"  id="votos_semaforo_amarillo" value="<?= $configuracion_matriz_rentabilidad_secciones_ine_2018Datos['votos_semaforo_amarillo'] ?>" placeholder="" onkeypress="return CheckNumeric()" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Configuración Partidos</label>
		</div>

		<div class="sucForm" <?= $display_municipio ?>>
			<label class="labelForm" id="labeltemaname">Partido Ayuntamiento<font color="#FF0004">*</font></label><br>
			<select   name="id_partido_2018_ayuntamiento" id="id_partido_2018_ayuntamiento" class='myselect'>
				<?php echo partidos_2018($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id_partido_2018_ayuntamiento'],'0'); ?>
			</select>
			<br>
		</div>

		<div class="sucForm" <?= $display_distrito_local ?>>
			<label class="labelForm" id="labeltemaname">Partido Distrito Local<font color="#FF0004">*</font></label><br>
			<select   name="id_partido_2018_distrito_local" id="id_partido_2018_distrito_local" class='myselect'>
				<?php echo partidos_2018($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id_partido_2018_distrito_local'],'1'); ?>
			</select>
			<br>
		</div>
		<div class="sucForm" <?= $display_distrito_federal ?>>
			<label class="labelForm" id="labeltemaname">Partido Distrito Federal<font color="#FF0004">*</font></label><br>
			<select   name="id_partido_2018_distrito_federal" id="id_partido_2018_distrito_federal" class='myselect'>
				<?php echo partidos_2018($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id_partido_2018_distrito_federal'],'2'); ?>
			</select>
			<br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Configuración Ciudadanos</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Partido Militantes<font color="#FF0004">*</font></label><br>
			<select   name="id_partido_legado" id="id_partido_legado" class='myselect'>
				<?php echo partidos_legados($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id_partido_legado']); ?>
			</select>
			<br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Categoría Funcionario<font color="#FF0004">*</font></label><br>
			<select   name="id_tipo_categoria_ciudadano" id="id_tipo_categoria_ciudadano" class='myselect'>
				<?php echo tipos_categorias_ciudadanos($configuracion_matriz_rentabilidad_secciones_ine_2018Datos['id_tipo_categoria_ciudadano']); ?>
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