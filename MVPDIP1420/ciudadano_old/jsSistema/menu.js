$(document).ready(function() {
						
		$("#homeLogo").click(function(event) { 
			////ajax
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load('home.php');
		});
		$("#home").click(function(event) { 
			////ajax
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load('home.php');
		});

		$("#home1").click(function(event) { 
			////ajax
			urlink="home.php";
			dataString = 'urlink='+urlink; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load('home.php');
		});
	
		 

		$("#settings").click(function(event) {  
		////ajax
			link="setupmanagerpanel/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});

		$("#configuracion_ciudadano").click(function(event) {  
		////ajax
			link="seccionesIneCiudadanos/update_config.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});

		$("#cerrarWeb").click(function(event) { 
		///ajax
			urlink="cerrar.php";
			$("#homebody").load(urlink); 
		});
		$("#secciones_ine_ciudadanos").click(function(event) {  
		////ajax
			link="seccionesIneCiudadanos/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});
		$("#secciones_ine_ciudadanos_entrega").click(function(event) {  
		////ajax
			link="seccionesIneCiudadanosEntrega/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});
		$("#casillas_votos_2021_a").click(function(event) {
		////ajax
			link="casillasVotos2021/index.php";
			link="casillasVotos2021/index_ayuntamiento.php"; 
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});
		$("#casillas_votos_2021_df").click(function(event) {
		////ajax
			link="casillasVotos2021/index.php";
			link="casillasVotos2021/index_d_federal.php"; 
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});
		$("#casillas_votos_2021_dl").click(function(event) {
		////ajax
			link="casillasVotos2021/index.php";
			link="casillasVotos2021/index_d_local.php"; 
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
		});
	}); 