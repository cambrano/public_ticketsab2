<?php
	include '../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('perfiles','cuentas_actividades',$_COOKIE["id_usuario"]);
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
			$( "#fecha_emision" ).datepicker({ 
				changeMonth: true,
				changeYear: true,
				showButtonPanel: true, 
				dateFormat: 'yy-mm-dd', 
				onSelect: function (date) { 
					document.getElementById("fecha_emision").style.border= "";
				}
			});
			$('#hora_emision').timepicker({ 
				timeFormat: 'H:i:s',
				showDuration: true,
				interval: 15,
				scrollDefault: "now",
				onSelect: function (date) { 
					document.getElementById("hora_emision").style.border= "";
				}
			}); 
		});
	</script>
	<script type="text/javascript">
		function doInsert(ctl)
		{
		    vInit = ctl.value;
		    ctl.value = ctl.value.replace(/[^a-f0-9:]/ig, "");
		    //ctl.value = ctl.value.replace(/:\s*$/, "");
		    vCurrent = ctl.value;
		    if(vInit != vCurrent)
		        return false;   

		    var v = ctl.value;
		    var l = v.length;
		    var lMax = 17;

		    if(l >= lMax)
		    {
		        return false;
		    }

		    if(l >= 2 && l < lMax)
		    {
		        var v1 = v;     
		        /* Removing all ':' to calculate get actaul text */
		        while(!(v1.indexOf(":") < 0)) { // Better use RegEx
		            v1 = v1.replace(":", "");           //console.log('v1:'+v1);
		        }

		        /* Insert ':' after ever 2 chars */     
		        var arrv1 = v1.match(/.{1,2}/g); // ["ab", "dc","a"]        
		        ctl.value = arrv1.join(":");
		    }
		}
		</script>
	<div style=" width: 100%; display:inline-block; text-align: left;">
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Emision</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Identidad<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_identidad" <?= $disbale_id_pricipal ?> >
				<?php
				echo identidades($cuenta_red_social_actividadDatos['id_identidad']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Red Social<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_red_social" <?= $disbale_id_pricipal ?> >
				<?php
				echo redes_sociales($cuenta_red_social_actividadDatos['id_red_social']);
				?>
			</select><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Fecha Emisión<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="fecha_emision" autocomplete="off"  id="fecha_emision" value="<?= $cuenta_red_social_actividadDatos['fecha_emision'] ?>" placeholder="" /><br>
		</div>

		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Hora<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="hora_emision" autocomplete="off"  id="hora_emision" value="<?= $cuenta_red_social_actividadDatos['hora_emision'] ?>" placeholder="" /><br>
		</div>

		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos Actividad</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Clave<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" <?= $claveF['input'] ?> type="text" name="clave" autocomplete="off"  id="clave" value="<?= $cuenta_red_social_actividadDatos['clave'] ?>" placeholder="" maxlength="120" onkeyup="clave(this.value)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
			<select class="myselect" id="id_tipo_actividad">
				<?php
				echo tipos_actividades($cuenta_red_social_actividadDatos['id_tipo_actividad']);
				?>
			</select><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">Url<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="url" autocomplete="off"  id="url" value="<?= $cuenta_red_social_actividadDatos['url'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">detalle<font color="#FF0004">*</font></label><br>
			<textarea id="detalle" style="width: 99%;height: 150px"><?= $cuenta_red_social_actividadDatos['detalle'] ?></textarea> <br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Datos de IP y Navegador Web</label>
		</div>
		<div class="sucForm" style="width: 100%">
			<label class="labelForm" id="labeltemaname">User Agent<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="user_agent" autocomplete="off"  id="user_agent" value="<?= $cuenta_red_social_actividadDatos['user_agent'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">MAC ADDRESS<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="mac_address" autocomplete="off"  id="mac_address" value="<?= $cuenta_red_social_actividadDatos['mac_address'] ?>" maxlength="17" onkeyup="doInsert(this)" onblur="aMays(event, this)"/><br>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">IP<font color="#FF0004">*</font></label><br>
			<input class="inputlogin" type="text" name="ip" autocomplete="off"  id="ip" value="<?= $cuenta_red_social_actividadDatos['ip'] ?>" maxlength="250" /><br>
		</div>
		<div class="sucFormTitulo">
			<label class="labelForm" id="labeltemaname">Ubicación</label>
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Latitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="latitud_script" id="latitud_script" value="<?= $cuenta_red_social_actividadDatos['latitud_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Longitud<font color="#FF0004">*</font></label><br>
			<input type="text" name="longitud_script" id="longitud_script" value="<?= $cuenta_red_social_actividadDatos['longitud_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Presición<font color="#FF0004">*</font></label><br>
			<input type="text" name="precision_script" id="precision_script" value="<?= $cuenta_red_social_actividadDatos['precision_script'] ?>">
		</div>
		<div class="sucForm">
			<label class="labelForm" id="labeltemaname">Location<font color="#FF0004">*</font></label><br>
			<input type="text" name="loc_script" id="loc_script" value="<?= $cuenta_red_social_actividadDatos['loc_script'] ?>">
		</div>

		<div class="sucForm" style="width: 100%" >
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