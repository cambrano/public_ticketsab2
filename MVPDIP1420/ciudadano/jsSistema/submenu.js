function subConfiguracion() {
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
	$("#homebody").load(link+"?refresh=1");
}


function subSeccionesIneCiudadanos() {
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
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionPerfilesPersonas() {
	////ajax
	link="setupPerfilesPersonas/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionLogistica() {
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
	$("#homebody").load(link+"?refresh=1");
}





function subIdentidades() {
	////ajax
	link="identidades/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subCuentasRedesSociales() {
	////ajax
	link="cuentasRedesSociales/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionMunicipiosReportes2018() {
	////ajax
	link="municipiosReportes2018/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionMunicipiosSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/municipio/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}


function subConfiguracionDistritosLocalesReportes2018() {
	////ajax
	link="distritosLocalesReportes2018/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosLocalesSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/distrito_local/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subConfiguracionDistritosFederalesReportes2018() {
	////ajax
	link="distritosFederalesReportes2018/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosFederalesSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/distrito_federal/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subConfiguracionMunicipiosReportes2021() {
	////ajax
	link="municipiosReportes2021/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionMunicipiosSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/municipio/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}


function subConfiguracionDistritosLocalesReportes2021() {
	////ajax
	link="distritosLocalesReportes2021/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosLocalesSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/distrito_local/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subConfiguracionDistritosFederalesReportes2021() {
	////ajax
	link="distritosFederalesReportes2021/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosFederalesSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/distrito_federal/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subConfiguracionMunicipiosReportes2024() {
	////ajax
	link="municipiosReportes2024/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionMunicipiosSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/municipio/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}


function subConfiguracionDistritosLocalesReportes2024() {
	////ajax
	link="distritosLocalesReportes2024/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosLocalesSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/distrito_local/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}
function subConfiguracionDistritosFederalesReportes2024() {
	////ajax
	link="distritosFederalesReportes2024/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}

function subConfiguracionDistritosFederalesSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/distrito_federal/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
}