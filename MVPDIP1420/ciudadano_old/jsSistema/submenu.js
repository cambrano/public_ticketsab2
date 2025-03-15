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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
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
	$("#homebody").load(link);
}