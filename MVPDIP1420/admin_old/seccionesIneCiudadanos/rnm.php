		var status_rnm = document.getElementById("status_rnm").value; 
			status_rnm = status_rnm.replace(espacios_invalidos, ''); 
			if(status_rnm == ""){
				document.getElementById("status_rnm").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estatus RNM requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var folio_rnm = document.getElementById("folio_rnm").value; 
			folio_rnm = folio_rnm.replace(espacios_invalidos, ''); 
			if(folio_rnm == ""){
				document.getElementById("folio_rnm").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Folio RNM requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var fecha_rnm = document.getElementById("fecha_rnm").value;
			fecha_rnm = fecha_rnm.replace(espacios_invalidos, '');
			if(fecha_rnm == ""){
				document.getElementById("fecha_rnm").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha RNM Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(fecha_rnm)){ 
					document.getElementById("fecha_rnm").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha RNM Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			var hora_rnm = document.getElementById("hora_rnm").value; 
			hora_rnm = hora_rnm.replace(espacios_invalidos, '');
			if(hora_rnm == ""){
				document.getElementById("hora_rnm").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Hora RNM requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var fecha_hora_rnm = fecha_rnm+' '+hora_rnm;

			var celular_rnm = document.getElementById("celular_rnm").value;
			celular_rnm = celular_rnm.replace(espacios_invalidos, '');
			if(celular_rnm!=''){
				if(isNaN(celular_rnm)){
					document.getElementById("celular_rnm").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Celular RNM valido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}else{
					if(celular_rnm.length != '10' ){
						document.getElementById("celular_rnm").focus(); 
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html("Celular RNM valido de 10 digitos");
						document.getElementById("mensaje").classList.add("mensajeError");
						return false;
					}
				}
			}

			var correo_electronico_rnm = document.getElementById("correo_electronico_rnm").value;
			correo_electronico_rnm = correo_electronico_rnm.replace(espacios_invalidos, '');  
			if(correo_electronico_rnm == ""){
				document.getElementById("correo_electronico_rnm").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Correo Electronico RNM Válido requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}else{
				if(!validarEmail(correo_electronico_rnm)){
					document.getElementById("correo_electronico_rnm").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Correo Electronico RNM Válido requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}

			'status_rnm' : status_rnm,
					'folio_rnm' : folio_rnm,
					'fecha_rnm' : fecha_rnm,
					'hora_rnm' : hora_rnm,
					'fecha_hora_rnm' : fecha_hora_rnm,
					'celular_rnm' : celular_rnm,
					'correo_electronico_rnm' : correo_electronico_rnm,
		<div>
			<div class="sucFormTitulo">
				<label class="labelForm" id="labeltemaname">Datos Registro Nacional de Militantes</label>
			</div>
			<div class="sucForm" style="width: 100%">
				<a href="https://www.rnm.mx/Afiliacion/Registro">Enlace Registro</a>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Estatus RNM<font color="#FF0004">*</font></label><br>
				<?php
					//$seccion_ine_ciudadanoDatos['status_rnm']=0;
					$select_status_rnm[$seccion_ine_ciudadanoDatos['status_rnm']]='selected="selected"';
				?>
				<select name="status_rnm" id="status_rnm" class='myselect'>  
					<option value="">Seleccione</option>
					<option <?= $select_status_rnm[0] ?> >Pendiente</option>
					<option <?= $select_status_rnm[1] ?> value="1">Registrado</option>
					<option <?= $select_status_rnm[2] ?> value="2">Sin Curso</option>
					<option <?= $select_status_rnm[3] ?> value="3">Completo</option>
				</select>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">FOLIO RNM<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="folio_rnm" autocomplete="off"  id="folio_rnm" value="<?= $seccion_ine_ciudadanoDatos['folio_rnm'] ?>" onkeyup="aMays(event, this)" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Fecha RNM<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="fecha_rnm" autocomplete="off"  id="fecha_rnm" value="<?= $seccion_ine_ciudadanoDatos['fecha_rnm'] ?>" placeholder="" /><br>
			</div>

			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Hora RNM<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="hora_rnm" autocomplete="off"  id="hora_rnm" value="<?= $seccion_ine_ciudadanoDatos['hora_rnm'] ?>" placeholder="" /><br>
			</div>
			<div class="sucForm">
				<label class="labelForm" id="labeltemaname">Celular RNM</label>(Solo Numero 10 Digitos, Si no tiene dejar en blanco)<br>
				<input class="inputlogin" type="text" name="celular_rnm" autocomplete="off"  id="celular_rnm" value="<?= $seccion_ine_ciudadanoDatos['celular_rnm'] ?>" placeholder="9991742151" onkeypress="return CheckNumeric()" /><br>
			</div>
			<div class="sucForm" style="width: 100%">
				<label class="labelForm" id="labeltemaname">Correo Eletrónico RNM<font color="#FF0004">*</font></label><br>
				<input class="inputlogin" type="text" name="correo_electronico_rnm" autocomplete="off"  id="correo_electronico_rnm" value="<?= $seccion_ine_ciudadanoDatos['correo_electronico_rnm'] ?>" placeholder="" /><br>
			</div>
		</div>