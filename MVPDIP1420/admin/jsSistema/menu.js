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
			$("#homebody").hide();
			setTimeout(function () {
				location.reload();
			}, 500);
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
			$("#homebody").hide();
			setTimeout(function () {
				location.reload();
			}, 500);
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
			$("#homebody").hide();
			setTimeout(function () {
				location.reload();
			}, 500);
		});
	
		$("#setup_perfiles_personas").click(function(event) { 
		////ajax 
			link="setupPerfilesPersonas/index.php?cot=NO";  
			link2="setupPerfilesPersonas/index.php";
			dataString = 'urlink='+link2;
			//location.reload();
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			////
			$("#homebody").load(link);
			$("#homebody").hide();
			setTimeout(function () {
				location.reload();
			}, 500);
		});

		$("#log_clicks").click(function(event) { 
		////ajax 
			link="logClicks/index.php?cot=NO";  
			link2="logClicks/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			////
			$("#homebody").load(link);
		});

		$("#log_sesiones").click(function(event) { 
		////ajax 
			link="logSesiones/index.php?cot=NO";  
			link2="logSesiones/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			////
			$("#homebody").load(link);
		});

		$("#log_usuarios_tracking").click(function(event) { 
		////ajax 
			link="logUsuariosTracking/index.php?cot=NO";  
			link2="logUsuariosTracking/index.php";
			dataString = 'urlink='+link2;
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) {}
			});
			////
			$("#homebody").load(link);
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
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
		});

		$("#cerrarWeb").click(function(event) { 
		///ajax
			urlink="cerrar.php";
			$("#homebody").load(urlink); 
		});
		$("#operatividad").click(function(event) {  
		////ajax
			link="setupLogistica/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
		});

		$("#security").click(function(event) {  
		////ajax
			link="setupSecurity/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
		});

		$("#soporte").click(function(event) {  
		////ajax
			link="soporte/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			$("#homebody").load(link);
			$("#homebody").hide();
			setTimeout(function () {
				location.reload();
			}, 500);
		});


		$("#dia_d").click(function(event) {  
		////ajax
			link="setupDiaD/index.php";
			dataString = 'urlink='+link; 
			$.ajax({
				type: "POST",
				url: "functions/backarray.php",
				data: dataString,
				success: function(data) { 	}
			});
			////
			//$("#homebody").load(link);
			$("#homebody").load(link+"?refresh=1");
		});
		$("#reportes").click(function(event) {  
			////ajax
				link="setupReportes/index.php";
				dataString = 'urlink='+link; 
				$.ajax({
					type: "POST",
					url: "functions/backarray.php",
					data: dataString,
					success: function(data) { 	}
				});
				////
				$("#homebody").load(link);
				$("#homebody").hide();
				setTimeout(function () {
					location.reload();
				}, 500);
			});
	}); 