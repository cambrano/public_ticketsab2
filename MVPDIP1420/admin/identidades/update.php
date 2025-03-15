<?php
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/redirect_security.php";
	include __DIR__."/../functions/claves.php";
	include __DIR__."/../functions/paises.php";
	include __DIR__."/../functions/estados.php";
	include __DIR__."/../functions/municipios.php";
	include __DIR__."/../functions/localidades.php";
	include __DIR__."/../functions/identidades.php";
	include '../functions/tool_xhpzab.php';
	@session_start();  
	if(!empty($_GET)){
		$id = $_GET['id'];
		setcookie("paguinaId",encrypt_ab_check($id),time()+(60*60*24*650),"/",false);
	}else{
		$id = decrypt_ab_checkFinal($_COOKIE['paguinaId']);
	}
	echo $redirectSecurity=redirectSecurity($id,'identidades','identidades','index');
	if($redirectSecurity!=""){
		die;
	}
	$claveF= clave('identidades');
	$identidadDatos=identidadDatos($id);
	if($identidadDatos['clave']==""){
		$identidadDatos['clave']=$claveF['clave'];
	}
	//var_dump($usuario_identidadDatos);
	$permiso="update"; 
?>
	<title>Update</title>
	<script language="javascript" type="text/javascript">
		function cerrar(){
			urlink="identidades/index.php";
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

			var clave = document.getElementById("clave").value; 
			if(clave == ""){
				document.getElementById("clave").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var tipo = document.getElementById("tipo").value; 
			if(tipo == ""){
				document.getElementById("tipo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Tipo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var sexo = document.getElementById("sexo").value; 
			if(sexo == ""){
				document.getElementById("sexo").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Sexo requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var fecha_nacimiento = document.getElementById("fecha_nacimiento").value; 
			if(fecha_nacimiento == ""){
				document.getElementById("fecha_nacimiento").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Fecha Nacimiento Válida requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			} else{
				if(!fechaValida(fecha_nacimiento)){ 
					document.getElementById("fecha_nacimiento").focus(); 
					document.getElementById("sumbmit").disabled = false;
					$("#mensaje").html("Fecha Nacimiento Válida requerido");
					document.getElementById("mensaje").classList.add("mensajeError");
					return false;
				}
			}
			var nombre = document.getElementById("nombre").value; 
			if(nombre == ""){
				document.getElementById("nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var apellido_paterno = document.getElementById("apellido_paterno").value; 
			if(apellido_paterno == ""){
				document.getElementById("apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var apellido_materno = document.getElementById("apellido_materno").value; 
			if(apellido_materno == ""){
				document.getElementById("apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var clave_elector = document.getElementById("clave_elector").value; 
			if(clave_elector == ""){
				document.getElementById("clave_elector").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Clave de Elector requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var curp = document.getElementById("curp").value; 
			if(curp == ""){
				document.getElementById("curp").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("C.U.R.P requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var rfc = document.getElementById("rfc").value; 
			if(rfc == ""){
				document.getElementById("rfc").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("R.F.C requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			var id_pais = document.getElementById("id_pais").value; 
			if(id_pais == ""){
				document.getElementById("id_pais").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Pais requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			//alert(codigo_postal);
			var id_estado = document.getElementById("id_estado").value; 
			if(id_estado == ""){
				document.getElementById("id_estado").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Estado requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			//alert(id_estado);
			var id_municipio = document.getElementById("id_municipio").value; 
			if(id_municipio == ""){
				document.getElementById("id_municipio").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Municipio requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(id_municipio);

			var id_localidad = document.getElementById("id_localidad").value; 
			if(id_localidad == ""){
				document.getElementById("id_localidad").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Localidad requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var calle = document.getElementById("calle").value; 
			if(calle == ""){
				document.getElementById("calle").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Calle requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			//alert(calle);

			var colonia = document.getElementById("colonia").value; 
			if(colonia == ""){
				document.getElementById("colonia").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Colonia requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}

			var codigo_postal = document.getElementById("codigo_postal").value;
			if(codigo_postal == ""){
				document.getElementById("codigo_postal").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Codigo Postal requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var longitud = document.getElementById("longitud").value;
			var latitud = document.getElementById("latitud").value;
			 
			var identidad = []; 
			var data = {    
					'id' : id,
					'tipo' : tipo,
					'clave' : clave,
					'sexo' : sexo,
					'fecha_nacimiento' : fecha_nacimiento,
					'nombre' : nombre,
					'apellido_paterno' : apellido_paterno,
					'apellido_materno' : apellido_materno,
					
					'clave_elector' : clave_elector,
					'curp' : curp,
					'rfc' : rfc,

					'id_pais' : id_pais,
					'id_estado' : id_estado,
					'id_municipio' : id_municipio,
					'id_localidad' : id_localidad,
					'calle' : calle,
					'colonia' : colonia, 
					'codigo_postal' : codigo_postal,
					'latitud' : latitud,
					'longitud' : longitud,
				}
			identidad.push(data);

			$.ajax({
				type: "POST",
				url: "identidades/db_edit.php",
				data: {identidad: identidad},
				success: function(data) {
					//document.getElementById("form").reset();  
					//document.getElementById("form").style.border="";
					//
					if(data=="SI"){
						document.getElementById("sumbmit").disabled = true;
						document.getElementById("mensaje").classList.remove("mensajeError");
						$("#mensaje").html("&nbsp;");
						$("#mensaje").html("Guardado con éxito"); 
						document.getElementById("mensaje").classList.add("mensajeSucces");
						urlink="identidades/index.php";
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
							urlink="identidades/index.php";
							dataString = 'urlink='+urlink; 
							$.ajax({
								type: "POST",
								url: "functions/backarray.php",
								data: dataString,
								success: function(data) { 	}
							});
							$("#homebody").load(urlink);
						}else{
							$("#mensaje").html(data);
							document.getElementById("mensaje").classList.add("mensajeError");
							document.getElementById("sumbmit").disabled = false;
						}
						
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
					<font style="font-size: 25px;">Modificar Identidad</font>
				</label><br>
				<label class="descripcionForm">
					<font style="font-size: 13px;">Campos para modificar a identidad.</font><br><br>
				</label><br>
				<font style="font-size: 15px;"><strong></strong></font>
				
			</div>
		</div>
		<div class="bodyinput">
			<?php include "form.php";?>
		</div>
	</div>