    <script>
		function searchTableFiltro(){
			var secciones_ine_giras_input = document.getElementById("secciones_ine_giras");
			var secciones_ine_giras_array = [];
			var secciones_ine_giras_array_table = [];
			for (var i = 0; i < secciones_ine_giras_input.length; i++) {
				if (secciones_ine_giras_input.options[i].selected){
					secciones_ine_giras_array.push(secciones_ine_giras_input.options[i].value);
				}
			}
			secciones_ine_giras = secciones_ine_giras_array.join(",");

			var secciones_ine_actividades_input = document.getElementById("secciones_ine_actividades");
			var secciones_ine_actividades_array = [];
			var secciones_ine_actividades_array_table = [];
			for (var i = 0; i < secciones_ine_actividades_input.length; i++) {
				if (secciones_ine_actividades_input.options[i].selected){
					secciones_ine_actividades_array.push(secciones_ine_actividades_input.options[i].value);
				}
			}
			secciones_ine_actividades = secciones_ine_actividades_array.join(",");


		}
	</script>
	<div style="background-color:rgba(197,197,197,0.3); width:100%;padding:5px;display:table">
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Giras<br></label><br>
			<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Mostrar" id="secciones_ine_giras" onchange="searchTable();">
				<option	option value="junta">Juntas</option>
				<option value="visita">Visitas</option>
				<option value="caminata">Caminatas</option>
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