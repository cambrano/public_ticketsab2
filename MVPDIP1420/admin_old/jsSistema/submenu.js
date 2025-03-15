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
function subEmpleados() {
	////ajax
	link="adminGenerales/index.php";
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

function subConfiguracionPadrones() {
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
}

function subConfiguracionDiaD() {
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
	//$("#homebody").load(link);
	$("#homebody").load(link+"?refresh=1");
}

function subSeccionesIneCiudadanosSeccion() {
	////ajax
	link="seccionesIneCiudadanosSeccion/index.php";
	dataString = 'urlink='+link; 
	$.ajax({
		type: "POST",
		url: "functions/backarray.php",
		data: dataString,
		success: function(data) { 	}
	});
	////
	$("#homebody").load(link+"?refresh=1");
	//$("#homebody").load(link);
}



function subEncuestas() {
	////ajax
	link="encuestas/index.php";
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

function subEncuestasMunicipios() {
	////ajax
	link="encuestasMunicipios/index.php";
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
}

function subEncuestasDistritosLocales() {
	////ajax
	link="encuestasDistritosLocales/index.php";
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
}
function subEncuestasDistritosFederales() {
	////ajax
	link="encuestasDistritosFederales/index.php";
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
}

function subEncuestasMunicipio() {
	////ajax
	link="encuestasSecciones/municipio/index.php";
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
}

function subEncuestasDistritoLocal() {
	////ajax
	link="encuestasSecciones/distrito_local/index.php";
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
}

function subEncuestasDistritoFederal() {
	////ajax
	link="encuestasSecciones/distrito_federal/index.php";
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
}



function subSecurity() {
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
	//$("#homebody").load(link);
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
	//$("#homebody").load(link);
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
	//$("#homebody").load(link);
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

function subConfiguracionCiudadanos() {
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
function subCasillasVotos2021() {
	////ajax
	link="casillasVotos2021/index.php";
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

function subConfiguracionReportes() {
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
}
function subPartidosLegados() {
	////ajax
	link="partidosLegados/index.php";
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
function subProgramasApoyos() {
	////ajax
	link="programasApoyos/index.php";
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
function subSeccionesIneGrupos() {
	////ajax
	link="seccionesIneGrupos/index.php";
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
function subSeccionesIneGiras() {
	////ajax
	link="seccionesIneGiras/index.php";
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

function subMilitantePartido(){
	link="militantesPartidos/index.php";
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
function subMilitantePartidoTotales(){
	link="militantesPartidosTotales/index.php";
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










