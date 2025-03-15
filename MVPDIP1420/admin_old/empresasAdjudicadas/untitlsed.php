			var representante_nombre = document.getElementById("representante_nombre").value; 
			if(representante_nombre == ""){
				document.getElementById("representante_nombre").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Nombre requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			var representante_apellido_paterno = document.getElementById("representante_apellido_paterno").value; 
			if(representante_apellido_paterno == ""){
				document.getElementById("representante_apellido_paterno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Apellido Paterno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}
			

			var representante_apellido_materno = document.getElementById("representante_apellido_materno").value; 
			if(representante_apellido_materno == ""){
				document.getElementById("representante_apellido_materno").focus(); 
				document.getElementById("sumbmit").disabled = false;
				$("#mensaje").html("Representante Apellido Materno requerido");
				document.getElementById("mensaje").classList.add("mensajeError");
				return false;
			}


			'representante_nombre' : representante_nombre,
			'representante_apellido_paterno' : representante_apellido_paterno,
			'representante_apellido_materno' : representante_apellido_materno,
			'correo_electronico' : correo_electronico,