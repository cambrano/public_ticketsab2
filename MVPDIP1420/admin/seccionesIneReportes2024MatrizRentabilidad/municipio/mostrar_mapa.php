	<div style="background-color:rgba(197,197,197,0.3); width:100%;padding:5px;display:table">
	<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo de Semáforo<br></label><br>
			<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Mostrar" id="tipo_semaforo" onchange="searchTable();">
				<option selected value="1" >Individual</option>
				<option value="2" >Coalición</option>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Agendas Gobierno<br></label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Mostrar" id="secciones_ine_agendas_gobierno" onchange="searchTable();">
				<?php
				echo tipos_giras('','SIN');
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">P. Inversion<br></label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Mostrar" id="secciones_ine_actividades" onchange="searchTable();">
				<option value="apoyo" >Apoyos</option>
				<option value="obra" >Obras</option>
				<option value="accion" >Acciones</option>
			</select><br>
		</div>
	</div>