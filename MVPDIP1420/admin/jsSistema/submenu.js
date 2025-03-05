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
	$("#homebody").load(link);
}
function subConfiguracionGobernadorReportes2016() {
	////ajax
	link="gobernadorReportes2016/index.php";
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
function subConfiguracionGobernadorSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/gobernador/index.php";
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
function subConfiguracionSenadorReportes2016() {
	////ajax
	link="senadorReportes2016/index.php";
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
function subConfiguracionSenadorSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/senador/index.php";
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
function subConfiguracionMunicipiosForzarDipLocalSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/forzar_distrito_federal/index.php";
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
function subConfiguracionMunicipiosReportes2016() {
	////ajax
	link="municipiosReportes2016/index.php";
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
function subConfiguracionMunicipiosSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/municipio/index.php";
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
function subConfiguracionGobernadorReportes2018() {
	////ajax
	link="gobernadorReportes2018/index.php";
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
function subConfiguracionGobernadorSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/gobernador/index.php";
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
function subConfiguracionMunicipiosForzarDipLocalSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipLocalSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipLocalSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/forzar_distrito_federal/index.php";
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
function subConfiguracionSenadorSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/senador/index.php";
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
function subConfiguracionSenadorReportes2018() {
	////ajax
	link="senadorReportes2018/index.php";
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
function subConfiguracionGobernadorReportes2021() {
	////ajax
	link="gobernadorReportes2021/index.php";
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
function subConfiguracionGobernadorSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/gobernador/index.php";
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
function subConfiguracionSenadorSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/senador/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_federal/index.php";
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
function subConfiguracionSenadorReportes2021() {
	////ajax
	link="senadorReportes2021/index.php";
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
function subConfiguracionGobernadorReportes2024() {
	////ajax
	link="gobernadorReportes2024/index.php";
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
function subConfiguracionGobernadorSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/gobernador/index.php";
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
function subConfiguracionSenadorSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/senador/index.php";
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
function subConfiguracionSenadorReportes2024() {
	////ajax
	link="senadorReportes2024/index.php";
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
function subConfiguracionDistritosLocalesReportes2016() {
	////ajax
	link="distritosLocalesReportes2016/index.php";
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
function subConfiguracionDistritosLocalesSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/distrito_local/index.php";
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
function subConfiguracionDistritosFederalesReportes2016() {
	////ajax
	link="distritosFederalesReportes2016/index.php";
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
function subConfiguracionDistritosFederalesSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/distrito_federal/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/forzar_distrito_local/index.php";
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
function subConfiguracionMunicipiosForzarDipFederalSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/forzar_distrito_federal/index.php";
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
function subCasillasVotos2024() {
	////ajax
	link="casillasVotos2024/index.php";
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
function subQRScannerCiudadano() {
	////ajax
	link="qrScannerCiudadano/index.php";
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
}
function subSemaforoSecciones() {
	////ajax
	link="seccionesIneCiudadanosSeccionesAvanceSemaforo/index.php";
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
}



function subConfiguracionF_Distrito_FederalSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/forzar_distrito_federal/index.php";
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

function subConfiguracionF_Distrito_LocalSeccionesIneReportes2024() {
	////ajax
	link="seccionesIneReportes2024/forzar_distrito_local/index.php";
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



function subConfiguracionF_Distrito_FederalSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_federal/index.php";
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

function subConfiguracionF_Distrito_LocalSeccionesIneReportes2021() {
	////ajax
	link="seccionesIneReportes2021/forzar_distrito_local/index.php";
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




function subConfiguracionF_Distrito_FederalSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/forzar_distrito_federal/index.php";
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

function subConfiguracionF_Distrito_LocalSeccionesIneReportes2018() {
	////ajax
	link="seccionesIneReportes2018/forzar_distrito_local/index.php";
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



function subConfiguracionF_Distrito_FederalSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/forzar_distrito_federal/index.php";
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

function subConfiguracionF_Distrito_LocalSeccionesIneReportes2016() {
	////ajax
	link="seccionesIneReportes2016/forzar_distrito_local/index.php";
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

function subDependencias(){
	link="dependencias/index.php";
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
function subDirectorios(){
	link="directorios/index.php";
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

