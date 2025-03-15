        $(document).ready(function() {
			$("#clave_elector_mensaje").click(function(event) { 
				$("#clave_elector_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#clave_elector_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#clave_elector_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#clave_elector_mensaje").css("padding", "0px");
			});
            $("#telefono_mensaje").click(function(event) { 
				$("#telefono_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#telefono_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#telefono_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#telefono_mensaje").css("padding", "0px");
			});
            $("#imagen_mensaje").click(function(event) { 
				$("#imagen_mensaje").html("");
                // Cambiar el color de fondo a white
                $("#imagen_mensaje").css("background-color", "white");
                // Cambiar el color del texto a blanco
                $("#imagen_mensaje").css("color", "white");
                // Aplicar el relleno de 0px
                $("#imagen_mensaje").css("padding", "0px");
			});
		});

        // Función para cambiar el fondo del div al seleccionar un candidato
        function cambiarFondoCandidato(id) {
            // Restablecer el fondo de todos los div de candidatos
            $(".cand_item div").css("background-color", "");
            // Obtener el ID del voto correspondiente al candidato seleccionado
            var votoId = "#voto_" + id;
            // Cambiar el fondo del div del candidato seleccionado a rojo
            $(votoId).css("background-color", "#e6b958");
        }
        // Llamar a la función al hacer clic en un radio de candidato
        $("input[name='foto_ine']").change(function () {
            var value = $(this).attr("value");
            cambiarFondoCandidato(value);
        });