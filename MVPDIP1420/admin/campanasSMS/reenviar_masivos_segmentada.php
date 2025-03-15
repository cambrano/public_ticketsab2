<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/campanas_sms.php";
	include __DIR__."/../functions/campanas_sms_cuerpos.php";
	include __DIR__."/../functions/tablas_relacionadas.php";

	include __DIR__."/../functions/encuestas.php";
	include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/tipos_categorias_ciudadanos.php";

	include __DIR__."/../functions/correos_mailing.php";
	include __DIR__."/../functions/timemex.php";
	//include __DIR__."/../functions/tipos_ciudadanos.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/distritos_locales.php";
	include __DIR__."/../functions/distritos_federales.php";
	include __DIR__."/../functions/secciones_ine.php";
	include __DIR__."/../functions/secciones_ine_ciudadanos.php";
	include '../functions/configuracion.php';
	include '../functions/usuarios.php';

	include '../functions/usuario_permisos.php';
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	
	 
	echo $redirectSecurity=redirectSecurity($id,'campanas_sms','campanasSMS','index');
	if($redirectSecurity!=""){
		die;
	}

	$inputFecha ='disabled="disabled"';
	$inputHora ='disabled="disabled"';
	$div_programa='none';
	$div_encuesta='none';

	$selectCartografia='<option value="">Seleccione</option>';
	$disable_id_tipo_cartografia ='disabled="disabled"';
	$cartografia_texto = 'Cartografía';

	$tipos_ciudadanosDatos = tipos_ciudadanosDatos();
	$tipos_categorias_ciudadanosDatos = tipos_categorias_ciudadanosDatos();

	$campana_smsDatos=campana_smsDatos($id);
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','campañas_sms',$_COOKIE["id_usuario"]);

	$campana_sms_cuerpoDatos = campana_sms_cuerpoDatos('',$id);
	if($campana_smsDatos['tipo']==1){
		$tipo_campana ='Bienvenida';
	}elseif ($campana_smsDatos['tipo']==2) {
		$tipo_campana ='Programada';
	}else{
		$tipo_campana ='Encuesta';
	} 

	include 'preview.php';


?>
	<title>Cancelar </title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="campanasSMS/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}

		function tipo_cartografia(value){
			var cartografia = [];
			var data = {
					'tipo_cartografia' : value, 
				}
			cartografia.push(data); 
			if (value == 'secciones_ine'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Sección INE');
			}else if (value == 'municipios'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Municipio');
			}else if (value == 'distritos_locales'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Distrito Local');
			}else if (value == 'distritos_federales'){
				document.getElementById("id_tipo_cartografia").disabled = false;
				$("#labelCartografia").html('Distrito Federal');
			}else{
				document.getElementById("id_tipo_cartografia").disabled = true;
				$("#labelCartografia").html('Cartografía');
			}

			$.ajax({
				type: "POST",
				url: "campanasSMS/cartografias.php",
				data: {cartografia: cartografia},
				success: function(data) {
					$("#id_tipo_cartografia").html(data);
				}
			});

		}

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");
			var id = '<?= $id?>';
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_encuesta = document.getElementById("id_encuesta").value; 

			var tipo_cartografia = document.getElementById("tipo_cartografia").value; 
			var id_tipo_cartografia = document.getElementById("id_tipo_cartografia").value; 


			var campana_sms = [];
			var data = {
					'id' : id,
				}
			campana_sms.push(data); 

			var campana_sms_encuesta = [];
			var data = {
					'id_encuesta' : id_encuesta,
				}
			campana_sms_encuesta.push(data);

			var campana_sms_cartografia = [];
			var data = {
					'tipo_cartografia' : tipo_cartografia,
					'id_tipo_cartografia' : id_tipo_cartografia,
				}
			campana_sms_cartografia.push(data);


			var campana_sms_tipo_ciudadano = [];
			<?php
				foreach ($tipos_ciudadanosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_tc<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
					}else{
						check = 0;
					}
					var data = {
							'id_tipo_ciudadano' : '<?= $value['id'] ?>',
							'check' : check,
						}
					campana_sms_tipo_ciudadano.push(data);
					<?php
				}
			?>


			var campana_sms_tipo_categoria_ciudadano = [];
			<?php
				foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
					?>
					var check = document.getElementById("chk_tcc<?=$value['id'] ?>").checked;
					if(check == true){
						check = 1
					}else{
						check = 0;
					}
					var data = {
							'id_tipo_categoria_ciudadano' : '<?= $value['id'] ?>',
							'check' : check,
						}
					campana_sms_tipo_categoria_ciudadano.push(data);
					<?php
				}
			?>
			$.ajax({
				type: "POST",
				url: "campanasSMS/db_reenviar_masivos_segmentada.php",
				data: {campana_sms: campana_sms,campana_sms_cartografia:campana_sms_cartografia,campana_sms_encuesta:campana_sms_encuesta,campana_sms_tipo_ciudadano:campana_sms_tipo_ciudadano,campana_sms_tipo_categoria_ciudadano:campana_sms_tipo_categoria_ciudadano},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						$("#mensaje").html("&nbsp;");
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="campanasSMS/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink+'?refresh=1');
					}else{
						document.getElementById("mensaje").classList.add("mensajeError");
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
					}
				}
			});
		}
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#mensaje").click(function(event) { 
				document.getElementById("mensaje").classList.remove("mensajeSucces");
				document.getElementById("mensaje").classList.remove("mensajeError");
				$("#mensaje").html("&nbsp");
			});
		});
	</script>
	<div class="bodymanager" id="bodymanager"> 
		<div id="mensaje" class="mensajeSolo" ><br></div>
		<div class="bodyform">
			<div class= "bodyheader">
				<label class="tituloForm">
					<font style="font-size: 25px;">SMS Masivos Segmentados</font>
				</label>
			</div>
		</div>
		<div class="bodyinput">
			<div style=" width: 100%; display:inline-block; text-align: left;">

				<div class="sucForm" style="width: 100%">
					<label class="labelForm" id="labeltemaname">Campaña SMS</label><br>
					<label class="descripcionForm">
						<strong><?= $campana_smsDatos['nombre']?></strong>
					</label><br>
				</div>

				<div class="sucForm" style="width: 100%">
					<label class="labelForm" id="labeltemaname">Tipo</label><br>
					<label class="descripcionForm">
						<strong><?= $tipo_campana ?></strong>
					</label><br>
				</div>

				<div class="sucForm" style="width: 100%">
					<label class="labelForm" id="labeltemaname">Campaña SMS</label><br>
					<label class="descripcionForm">
						<strong><?= $campana_smsDatos['nombre']?></strong>
					</label><br>
				</div>

				<div class="sucForm" style="width: 100%">
					<label class="labelForm" id="labeltemaname">Cuerpo</label><br>
					<label class="descripcionForm">
						<strong><?=	$bodyHTML;	?></strong>
					</label>
					<br><br>
				</div>

				<div class="sucFormTitulo">
					<label class="labelForm" id="labeltemaname">Tipo de Campaña</label>
				</div>

				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">Encuesta<font color="#FF0004">*</font></label><br>
					<select id="id_encuesta" class="myselect" name="id_encuesta" >
						<?= encuestas($campana_sms_encuestaDatos['id_encuesta']); ?>
					</select>
				</div>

				<div class="sucFormTitulo">
					<label class="labelForm" id="labeltemaname">Tipo de Cartografía</label>
				</div>

				<div class="sucForm">
					<label class="labelForm" id="labeltemaname">Tipo<font color="#FF0004">*</font></label><br>
					<select id="tipo_cartografia" class="myselect" name="tipo_cartografia" onchange="tipo_cartografia(this.value)" >
						<?php
						$selectTipoCartografia[$campana_sms_cartografiaDatos['tipo_cartografia']]='selected="selected"';
						?>
						<option value="">Todas</option>
						<option <?= $selectTipoCartografia['municipios'] ?> value="municipios">Municipio</option>
						<option <?= $selectTipoCartografia['distritos_locales'] ?> value="distritos_locales">Distrito Local</option>
						<option <?= $selectTipoCartografia['distritos_federales'] ?> value="distritos_federales">Distrito Federal</option>
						<option <?= $selectTipoCartografia['secciones_ine'] ?> value="secciones_ine">Seccion INE</option>
					</select>
				</div>

				<div class="sucForm">
					<label class="labelForm" id="labelCartografia"><?= $cartografia_texto ?></label><font color="#FF0004">*</font><br>
					<select id="id_tipo_cartografia" class="myselect" name="id_tipo_cartografia" <?= $disable_id_tipo_cartografia ?>>
						<?= $selectCartografia ?>
					</select>
				</div>

				<div class="sucFormTitulo">
					<label class="labelForm" id="labeltemaname">Tipo de Ciudadanos</label>
				</div>
				<?php
				foreach ($tipos_ciudadanosDatos as $key => $value) {
					if($campanas_sms_tipos_ciudadanosIdDatos[$value['id']]['id']!=''){
						$checked = 'checked="checked"';
					}else{
						$checked = '';
					}
					?>
					<div class="sucForm" style="padding:5px 20px 5px 20px ">
						<input <?= $checked ?> class="inputlogin" type="checkbox" name="chk_tc<?= $value['id'] ?>" autocomplete="off"  id="chk_tc<?= $value['id'] ?>" value=""/>
						<label class="labelForm" for="chk_tc<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
					</div>
					<?php
				}
				?>

				<div class="sucFormTitulo">
					<label class="labelForm" id="labeltemaname">Tipo de Categorías Ciudadanos</label>
				</div>
				<?php
				foreach ($tipos_categorias_ciudadanosDatos as $key => $value) {
					if($campanas_sms_tipos_categorias_ciudadanosIdDatos[$value['id']]['id']!=''){
						$checked = 'checked="checked"';
					}else{
						$checked = '';
					}
					?>
					<div class="sucForm" style="padding:5px 20px 5px 20px ">
						<input <?= $checked ?> class="inputlogin" type="checkbox" name="" autocomplete="off"  id="chk_tcc<?= $value['id'] ?>" value=""/>
						<label class="labelForm" for="chk_tcc<?= $value['id'] ?>" style="letter-spacing:2px;text-transform:none;" ><?= $value['nombre'] ?></label>
					</div>
					<?php
				}
				?>

				<div class="sucForm" style="width: 100%">
					<br> 
					<input type="button" id="sumbmit" onclick="guardar()" value="Enviar">
					<input type="button" value="Salir" onclick="cerrar()">
				</div>

			</div>
		</div>
		<script type="text/javascript">
			$(".myselect").select2();
		</script>
	</div>