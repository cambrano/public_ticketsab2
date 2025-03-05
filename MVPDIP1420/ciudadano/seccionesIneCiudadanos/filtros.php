<?php
	include __DIR__."/../functions/security.php";
	@session_start();
?>
	<script type="text/javascript">
		$( function() {
			$( "#fecha_nacimiento_1" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			});
			$( "#fecha_nacimiento_2" ).datepicker({ 
				changeMonth: true,
				changeYear: true, 
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd',
				onSelect: function (date) {
					searchTable();
				}
			}); 
		});
		function locationMunicipio() {
			var id_estado = '<?= $id_estado ?>';
			var id_municipio = document.getElementById("id_municipio").value;
			var id_municipio = id_municipio.replace(/^\s+|\s+$/g, ""); 
			document.getElementById("id_municipio").value=id_municipio;
			if(id_municipio == ""){
				document.getElementById("id_municipio").style.border= "1px solid red";
				document.getElementById("id_localidad").style.border= "";
				document.getElementById("id_localidad").value="";
				var dataString = 'id_estado=x';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}else{
				document.getElementById("id_municipio").style.border= "";
				document.getElementById("id_localidad").style.border= "";
				var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio+'&tipo=municipio_array';
				$.ajax({
					type: "POST",
					url: "localidades/ajax.php",
					data: dataString,
					success: function(data) {
						$("#id_localidad").html(data);
					}
				});
			}
			//searchTable();
		}
		function searchTable(value){
			var clave = document.getElementById("clave").value;
			var folio = document.getElementById("folio").value;
			var clave_elector = document.getElementById("clave_elector").value;
			var curp = document.getElementById("curp").value;
			var info_vigente = document.getElementById("info_vigente").value;
			var id_tipo_ciudadano_input = document.getElementById("id_tipo_ciudadano");
			var id_tipo_ciudadano_array = [];
			for (var i = 0; i < id_tipo_ciudadano_input.length; i++) {
				if (id_tipo_ciudadano_input.options[i].selected){
					id_tipo_ciudadano_array.push(id_tipo_ciudadano_input.options[i].value);
				}
			}
			id_tipo_ciudadano = id_tipo_ciudadano_array.join(",");
			var id_tipo_categoria_ciudadano_input = document.getElementById("id_tipo_categoria_ciudadano");
			var id_tipo_categoria_ciudadano_array = [];
			for (var i = 0; i < id_tipo_categoria_ciudadano_input.length; i++) {
				if (id_tipo_categoria_ciudadano_input.options[i].selected){
					id_tipo_categoria_ciudadano_array.push(id_tipo_categoria_ciudadano_input.options[i].value);
				}
			}
			id_tipo_categoria_ciudadano = id_tipo_categoria_ciudadano_array.join(",");
			var nombre = document.getElementById("nombre").value; 
			nombre = nombre.trim();
			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			apellido_paterno = apellido_paterno.trim();
			var apellido_materno = document.getElementById("apellido_materno").value;
			apellido_materno = apellido_materno.trim();
			var sexo = document.getElementById("sexo").value;
			var fecha_nacimiento_dia = document.getElementById("fecha_nacimiento_dia").value;
			var fecha_nacimiento_mes = document.getElementById("fecha_nacimiento_mes").value;
			var fecha_nacimiento_edad = document.getElementById("fecha_nacimiento_edad").value;
			var fecha_nacimiento_1 = document.getElementById("fecha_nacimiento_1").value;
			var fecha_nacimiento_2 = document.getElementById("fecha_nacimiento_2").value;
			var programas_apoyos = document.getElementById("programas_apoyos").value;
			var id_programa_apoyo_input = document.getElementById("id_programa_apoyo");
			var id_programa_apoyo_array = [];
			for (var i = 0; i < id_programa_apoyo_input.length; i++) {
				if (id_programa_apoyo_input.options[i].selected){
					id_programa_apoyo_array.push(id_programa_apoyo_input.options[i].value);
				}
			}
			id_programa_apoyo = id_programa_apoyo_array.join(",");
			var num_seguimiento = document.getElementById("num_seguimiento").value;
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(",");
			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
				}
			}
			id_municipio = id_municipio_array.join(",");
			var id_localidad_input = document.getElementById("id_localidad");
			var id_localidad_array = [];
			for (var i = 0; i < id_localidad_input.length; i++) {
				if (id_localidad_input.options[i].selected){
					id_localidad_array.push(id_localidad_input.options[i].value);
				}
			}
			id_localidad = id_localidad_array.join(",");

			var searchTable = [];
			var data = {
					'clave' : clave,
					'folio' : folio,
					'clave_elector' : clave_elector,
					'curp' : curp,
					'info_vigente' : info_vigente,
					'id_seccion_ine' : id_seccion_ine, 
					'sexo' : sexo,
					'clave_elector' : clave_elector,
					'id_tipo_ciudadano' : id_tipo_ciudadano,
					'id_tipo_categoria_ciudadano' : id_tipo_categoria_ciudadano,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					'sexo' : sexo,
					'fecha_nacimiento_dia' : fecha_nacimiento_dia,
					'fecha_nacimiento_mes' : fecha_nacimiento_mes,
					'fecha_nacimiento_edad' : fecha_nacimiento_edad,
					'fecha_nacimiento_1' : fecha_nacimiento_1,
					'fecha_nacimiento_2' : fecha_nacimiento_2,
					'programas_apoyos' :programas_apoyos,
					'id_programa_apoyo' :id_programa_apoyo,
					'num_seguimiento' : num_seguimiento,
					'id_seccion_ine' : id_seccion_ine,
					'id_municipio' :id_municipio,
					'id_localidad' :id_localidad,
				}
			searchTable.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneCiudadanos/table.php",
				data: {searchTable: searchTable},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
		}
	</script>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Amigos</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave</label><br>
		<input data-column="0" id="clave" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Folio</label><br>
		<input data-column="0" id="folio" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">clave elector</label><br>
		<input data-column="0" id="clave_elector" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">C.U.R.P</label><br>
		<input data-column="0" id="curp" autocomplete="off" type="text" onchange ="searchTable();" > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Info Vigente</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="info_vigente" >
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Vencidos</option>
			<option value="x">No Vencido</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipos Ciudadanos</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_ciudadano" >
			<?php
			echo tipos_ciudadanos('','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Categorías</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_tipo_categoria_ciudadano" >
			<?php
			echo tipos_categorias_ciudadanos('','SIN');
			?>
			<option value="0">Sin Categoría</option>
		</select><br>
	</div>
	<div style=" width: 100%;display: block;float: left;">
		<hr style=" display: block; margin-top: 0.5em;  margin-bottom: 0.5em; margin-left: auto; margin-right: auto; border-style: inset;  border-width: 1px;"> 
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Nombre</label><br>
		<input data-column="1" id="nombre" autocomplete="off" type="text"  > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Paterno</label><br>
		<input data-column="1" id="apellido_paterno" autocomplete="off" type="text"  > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Apellido Materno</label><br>
		<input data-column="1" id="apellido_materno" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Sexo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="sexo" >
			<option selected="selected" value="">Seleccione</option>
			<option value="Mujer">Mujer</option>
			<option value="Hombre">Hombre</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Día</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_dia" >
			<option selected="selected" value="">Seleccione</option>
			<option value="01" >01</option>
			<option value="02" >02</option>
			<option value="03" >03</option>
			<option value="04" >04</option>
			<option value="05" >05</option>
			<option value="06" >06</option>
			<option value="07" >07</option>
			<option value="08" >08</option>
			<option value="09" >09</option>
			<option value="10" >10</option>
			<option value="11" >11</option>
			<option value="12" >12</option>
			<option value="13" >13</option>
			<option value="14" >14</option>
			<option value="15" >15</option>
			<option value="16" >16</option>
			<option value="17" >17</option>
			<option value="18" >18</option>
			<option value="19" >19</option>
			<option value="20" >20</option>
			<option value="21" >21</option>
			<option value="22" >22</option>
			<option value="23" >23</option>
			<option value="24" >24</option>
			<option value="25" >25</option>
			<option value="26" >26</option>
			<option value="27" >27</option>
			<option value="28" >28</option>
			<option value="29" >29</option>
			<option value="30" >30</option>
			<option value="31" >31</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Mes</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_mes" >
			<option selected="selected" value="">Seleccione</option>
			<option value="01" >Enero</option>
			<option value="02" >Febrero</option>
			<option value="03" >Marzo</option>
			<option value="04" >Abril</option>
			<option value="05" >Mayo</option>
			<option value="06" >Junio</option>
			<option value="07" >Julio</option>
			<option value="08" >Agosto</option>
			<option value="09" >Septiembre</option>
			<option value="10" >Octubre</option>
			<option value="11" >Noviembre</option>
			<option value="12" >Diciembre</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Edad</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="fecha_nacimiento_edad" >
			<option selected="selected" value="">Seleccione</option>
			<option value="18" >18</option>
			<option value="19" >19</option>
			<option value="20" >20</option>
			<option value="21" >21</option>
			<option value="22" >22</option>
			<option value="23" >23</option>
			<option value="24" >24</option>
			<option value="25" >25</option>
			<option value="26" >26</option>
			<option value="27" >27</option>
			<option value="28" >28</option>
			<option value="29" >29</option>
			<option value="30" >30</option>
			<option value="31" >31</option>
			<option value="32" >32</option>
			<option value="33" >33</option>
			<option value="34" >34</option>
			<option value="35" >35</option>
			<option value="36" >36</option>
			<option value="37" >37</option>
			<option value="38" >38</option>
			<option value="39" >39</option>
			<option value="40" >40</option>
			<option value="41" >41</option>
			<option value="42" >42</option>
			<option value="43" >43</option>
			<option value="44" >44</option>
			<option value="45" >45</option>
			<option value="46" >46</option>
			<option value="47" >47</option>
			<option value="48" >48</option>
			<option value="49" >49</option>
			<option value="50" >50</option>
			<option value="51" >51</option>
			<option value="52" >52</option>
			<option value="53" >53</option>
			<option value="54" >54</option>
			<option value="55" >55</option>
			<option value="56" >56</option>
			<option value="57" >57</option>
			<option value="58" >58</option>
			<option value="59" >59</option>
			<option value="60" >60</option>
			<option value="61" >61</option>
			<option value="62" >62</option>
			<option value="63" >63</option>
			<option value="64" >64</option>
			<option value="65" >65</option>
			<option value="66" >66</option>
			<option value="67" >67</option>
			<option value="68" >68</option>
			<option value="69" >69</option>
			<option value="70" >70</option>
			<option value="71" >71</option>
			<option value="72" >72</option>
			<option value="73" >73</option>
			<option value="74" >74</option>
			<option value="75" >75</option>
			<option value="76" >76</option>
			<option value="77" >77</option>
			<option value="78" >78</option>
			<option value="79" >79</option>
			<option value="80" >80</option>
			<option value="81" >81</option>
			<option value="82" >82</option>
			<option value="83" >83</option>
			<option value="84" >84</option>
			<option value="85" >85</option>
			<option value="86" >86</option>
			<option value="87" >87</option>
			<option value="88" >88</option>
			<option value="89" >89</option>
			<option value="90" >90</option>
			<option value="91" >91</option>
			<option value="92" >92</option>
			<option value="93" >93</option>
			<option value="94" >94</option>
			<option value="95" >95</option>
			<option value="96" >96</option>
			<option value="97" >97</option>
			<option value="98" >98</option>
			<option value="99" >99</option>
			<option value="100" >100</option>
			<option value="101" >101</option>
			<option value="102" >102</option>
			<option value="103" >103</option>
			<option value="104" >104</option>
			<option value="105" >105</option>
			<option value="106" >106</option>
			<option value="107" >107</option>
			<option value="108" >108</option>
			<option value="109" >109</option>
			<option value="110" >110</option>
			<option value="111" >111</option>
			<option value="112" >112</option>
			<option value="113" >113</option>
			<option value="114" >114</option>
			<option value="115" >115</option>
			<option value="116" >116</option>
			<option value="117" >117</option>
			<option value="118" >118</option>
			<option value="119" >119</option>
			<option value="120" >120</option>
			<option value="121" >121</option>
			<option value="122" >122</option>
			<option value="123" >123</option>
			<option value="124" >124</option>
			<option value="125" >125</option>
			<option value="126" >126</option>
			<option value="127" >127</option>
			<option value="128" >128</option>
			<option value="129" >129</option>
			<option value="130" >130</option>
			<option value="131" >131</option>
			<option value="132" >132</option>
			<option value="133" >133</option>
			<option value="134" >134</option>
			<option value="135" >135</option>
			<option value="136" >136</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Nacimiento(1)</label><br>
		<input id="fecha_nacimiento_1" autocomplete="off" type="text"  > <br>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Fecha Nacimiento(2)</label><br>
		<input id="fecha_nacimiento_2" autocomplete="off" type="text"  > <br>
	</div>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Otros</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="programas_apoyos" >
			<option selected="selected" value="">Seleccione</option>
			<option value="1">Con</option>
			<option value="0">Sin</option>
		</select>
	</div>

	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Programas Apoyos</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_programa_apoyo" >
			<?php
			echo programas_apoyos('','SIN');
			?>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">No. Seguimiento</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="num_seguimiento" >
			<option selected="selected" value="">Seleccione</option>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
	</div>
	<div class="sucFormTitulo">
		<label class="labelForm" id="labeltemaname">Filtros Ubicación</label>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" >
			<?php
			echo secciones_ine('','','','','SIN');
			?>
		</select>
	</div>
	<div class="sucForm" <?= $display_municipio ?>>
		<label class="labelForm" id="labeltemaname">Municipio</label><br>
		<select name="id_municipio" id="id_municipio" class='myselect' onchange="locationMunicipio(this)">
			<?php
			echo municipios($id_municipio,$id_estado,'');
			?>
		</select>
	</div>
 
	<div class="sucForm" <?= $display_municipio ?>>
		<label class="labelForm" id="labeltemaname">Localidad</label><br>
		<select name="id_localidad" id="id_localidad" class='myselect' >  
			<?php
			echo localidades();
			?>
		</select>
	</div>
	<div class="sucForm" style="width: 100%">
		<input style="width: 100%;margin-bottom: 10px" type="button" onclick="searchTable()" value="Buscar">
	</div>
	<style>
		.ui-autocomplete {
			max-height: 180px;
			margin-bottom: 10px;
			overflow-x: hidden;
			overflow-y: auto;
		}
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 38px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>
	<script type="text/javascript">
		$(".myselect").select2();
		$('.selectpicker').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>