<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/empleados.php";
	include __DIR__."/../functions/secciones.php";
	include __DIR__."/../functions/modulos.php";
	include __DIR__."/../functions/permisos.php";
	include __DIR__."/../functions/usuarios_modulos.php";
	include __DIR__."/../functions/usuarios_permisos.php";
	include __DIR__."/../functions/plataformas.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	
	$id_empleado = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId_1",encrypt_ab_check($id), array('expires' => time() + (60*60*24), 'path' => '/', 'secure' => true, 'httponly' => true, 'samesite' => 'Strict'));
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}

	validar_plataforma_vista($id_empleado,'empleados','adminGenerales','index',$codigo_plataforma);
	echo $redirectSecurity=redirectSecurity($id,'usuarios_modulos','usuarioPermisos','index');
	if($redirectSecurity!=""){
		die;
	}
	$id;
	$usuario_moduloDatos=usuario_moduloDatos($id,'',$id_empleado);
	$usuario_permisosDatos = usuario_permisosDatos('',$id);
	$id_modulo=$usuario_moduloDatos['id_modulo'];

	foreach ($usuario_permisosDatos as $key => $value) {
		$ids_permiso[]= $value['id_permiso'];
	}
	$empleadoDatos=empleadoDatos($id_empleado);
	$permiso="update";

	$disabled = 'disabled="disabled"';
?>
<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="usuarioPermisos/index.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			$("#homebody").load(urlink);
		}
		 

		function guardar() {
			document.getElementById("sumbmit").disabled = true;
			document.getElementById("mensaje").classList.remove("mensajeSucces");
			document.getElementById("mensaje").classList.remove("mensajeError");
			$("#mensaje").html("&nbsp");


			var id = '<?= $id ?>'; 
			if(id == ""){
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Id requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var id_empleado = '<?=$id_empleado ?>'; 
			if(id_empleado == ""){
				document.getElementById("id_empleado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Empleado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_seccion = document.getElementById("id_seccion").value; 
			if(id_seccion == ""){
				document.getElementById("id_seccion").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Seccion requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var id_modulo = document.getElementById("id_modulo").value; 
			if(id_modulo == ""){
				document.getElementById("id_modulo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Modulo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} 
			//var dataString = 'id_seccion='+id_seccion+'&id_modulo='+id_modulo+'&id_permiso='+id_permiso+'&id_empleado=<?=$id_empleado ?>';
			var permiso_modulo = []; 
			var data = {
					'id' : id,
					'id_empleado' : id_empleado,
					'id_seccion' : id_seccion,
					'id_modulo' : id_modulo,
				}
			permiso_modulo.push(data);
			var chkval=0;
			var chk = document.getElementsByName('permiso');
			var permisos_valor = {};
			for (var i=0 ; i < chk.length ; i++){
				if(chk[i].checked==true){
					chkval=chkval=1;
					var name=chk[i].value;
					var val='SI';
					permisos_valor[name] = val;
				}else{
					var name=chk[i].value;
					var val='NO';
					permisos_valor[name] = val;
				}
			}
			if(chkval==0){
				//document.getElementById("sumbmit").disabled = false;
				//$("#mensaje").html("Permiso requerido");
				//document.getElementById("mensaje").classList.add("mensajeError");
				//return false;
			}
			var chkvalPermisos = []; 
			var data = {
					'chkvalPermisos' : chkval, 
				}
			chkvalPermisos.push(data);
			$.ajax({
			type: "POST",
			url: "usuarioPermisos/db_edit.php",
			data: {permiso_modulo: permiso_modulo,permisos_valor: permisos_valor,chkvalPermisos:chkvalPermisos},
			success: function(data) {
				if(data=="SI"){
					document.getElementById("sumbmit").disabled = true;
					document.getElementById("mensaje").classList.remove("mensajeError");
					$("#mensaje").html("&nbsp;");
					$("#mensaje").html("Guardado con éxito"); 
					document.getElementById("mensaje").classList.add("mensajeSucces");
					urlink="usuarioPermisos/index.php";
					dataString = 'urlink='+urlink; 
					$.ajax({
						type: "POST",
						url: "functions/backarray.php",
						data: dataString,
						success: function(data) { 	}
					});
					$("#homebody").load(urlink);
				}else{
					if(data==""){
						document.getElementById("sumbmit").disabled = false;
						urlink="usuarioPermisos/index.php";
						dataString = 'urlink='+urlink; 
						$.ajax({
							type: "POST",
							url: "functions/backarray.php",
							data: dataString,
							success: function(data) { 	}
						});
						$("#homebody").load(urlink);
					}else{
						document.getElementById("sumbmit").disabled = false;
						$("#mensaje").html(data);
						document.getElementById("mensaje").classList.add("mensajeError");
						
					}
					
				}
			}
		});
		}
		$(document).ready(function() {
			 
			$('#id_seccion').blur(function(){
				var id_seccion = document.getElementById("id_seccion").value;
				var id_seccion = id_seccion.replace(/^\s+|\s+$/g, ""); 
				document.getElementById("id_seccion").value=id_seccion;
				if(id_seccion == ""){
					document.getElementById("id_modulo").value="";
					var dataString = 'id_seccion=x';
					$.ajax({
						type: "POST",
						url: "modulos/ajax.php",
						data: dataString,
						success: function(data) {
							$("#id_modulo").html(data);
						}
					});
					document.getElementById("permisos").value="";
					var dataString = 'id_modulo=x';
					$.ajax({
						type: "POST",
						url: "permisos/ajax.php",
						data: dataString,
						success: function(data) {
							$("#permisos").html(data);
						}
					});
				}else{
					var dataString = 'id_seccion='+id_seccion;
					$.ajax({
						type: "POST",
						url: "modulos/ajax.php",
						data: dataString,
						success: function(data) {
							$("#id_modulo").html(data);
						}
					});
				}
			});

			$('#id_modulo').blur(function(){
				var id_modulo = document.getElementById("id_modulo").value;
				var id_modulo = id_modulo.replace(/^\s+|\s+$/g, ""); 
				document.getElementById("id_modulo").value=id_modulo;
				if(id_modulo == ""){
					document.getElementById("permisos").value="";
					var dataString = 'id_modulo=x';
					$.ajax({
						type: "POST",
						url: "permisos/ajax.php",
						data: dataString,
						success: function(data) {
							$("#permisos").html(data);
						}
					});
				}else{
					var dataString = 'id_modulo='+id_modulo;
					$.ajax({
						type: "POST",
						url: "permisos/ajax.php",
						data: dataString,
						success: function(data) {
							$("#permisos").html(data);
						}
					});
				}
			});
		});  
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
			<label class="tituloForm">
					<font style="font-size: 25px;">Modificar Permiso de Empleado</font>
				</label>
				<h3>
					Empleado: <?= $empleadoDatos['nombre_empleado'] ?>
				</h3>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar permiso a empleado.</font><br><br>
				</label>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>