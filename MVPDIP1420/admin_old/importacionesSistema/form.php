	<div style=" width: 100%; display:inline-block; text-align: left;">
		<form id="form">
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<label class="labelForm" id="labeltemaname">Tipo Vista<font color="#FF0004">*</font></label><br>
				<select id="tipo_vista" class="myselect" onchange="resetMensajes()">
					<option value="0" selected="selected" >Compacta</option>
					<option value="1" >Normal</option> 
				</select><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<label class="labelForm" id="labeltemaname">Tipo De Operación<font color="#FF0004">*</font></label><br>
				<select id="tipo_operacion" class="myselect" onchange="resetMensajes()">
					<option value="">Seleccione</option>
					<option value="1" selected="selected" >Insertar Información</option>
					<option value="2" >Editar Información</option>
				</select><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<label class="labelForm" id="labeltemaname">Tipo de Información<font color="#FF0004">*</font></label><br>
				<select id="tabla_operacion" class="myselect" onchange="resetMensajes()">
					<?php
					echo tipoTablas();
					?>
				</select><br>
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<!--<label class="labelForm" id="labeltemaname">Seleccione Archivo<font color="#FF0004">*</font></label><br>
				<input type="file" name="file" id="file" ><br>--->
				<div class="fileupload" style="text-align: center;" >
					Seleccionar Archivo
					<input type="file" id="file" name="file" />
				</div> 
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<input type="button" id="sumbmit" onclick="guardar()" value="Validar"> 
				<input type="button" value="Cancelar" onclick="cerrar()">
			</div>
			<div class="sucForm" style="float: left;position: relative;margin-right: 5px;margin-bottom: 5px;width: 100%">
				<div id="loadSistema" style="padding: 4px;width: 100%; text-align: center; display:none;">
					<img src="img/load.gif" height="180">
				</div>
				<div id="importacionArea">&nbsp;</div>
				<div id="mensaje">&nbsp;</div> 
			</div>
		</form>
	</div>

	<script type="text/javascript">
		$(".myselect").select2();
	</script>