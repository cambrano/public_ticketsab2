	function clave(value) {
		document.getElementById("clave").value=value.toUpperCase();
	}
	function clave_cliente(value) {
		document.getElementById("clave_cliente").value=value.toUpperCase();
	}

	function clave_transferencia(value) {
		document.getElementById("clave_transferencia").value=value.toUpperCase();
	}

	function aMays(e, elemento) {
		tecla=(document.all) ? e.keyCode : e.which; 
			elemento.value = elemento.value.toUpperCase();
	}

	function rfc(value) {
		document.getElementById("rfc").value=value.toUpperCase();
	}

	 


	function soloNumeros(e){ 
		var key = window.Event ? e.which : e.keyCode 
		return ((key >= 48 && key <= 57) || (key==8)) 
	}

	function CheckNumeric() {
		return event.keyCode >= 48 && event.keyCode <= 57 || event.keyCode == 46;
	}

	function FormatCurrency(ctrl) {
				//Check if arrow keys are pressed - we want to allow navigation around textbox using arrow keys
		if (event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40) {
			return;
		}
		var val = ctrl.value;
		val = val.replace(/,/g, "")
		ctrl.value = "";
		val += '';
		x = val.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		ctrl.value = x1 + x2;
	}

	function validarEmail(valor) {
		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		if ( !expr.test(valor) ){
			return false;
		}else{
			return true;
		}
	}

	function locationEstado(id_estado){
		var id_estado = document.getElementById("id_estado").value;
		var id_estado = id_estado.replace(/^\s+|\s+$/g, ""); 
		document.getElementById("id_estado").value=id_estado;
		if(id_estado == ""){
			document.getElementById("id_municipio").value="";
			var dataString = 'id_estado=x';
			$.ajax({
				type: "POST",
				url: "municipios/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_municipio").html(data);
				}
			});
		}else{
			var dataString = 'id_estado='+id_estado;
			$.ajax({
				type: "POST",
				url: "municipios/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_municipio").html(data);
				}
			});
		}
	}
	function locationMunicipio(id_estado){
		var id_estado = document.getElementById("id_estado").value;
		var id_municipio = document.getElementById("id_municipio").value;
		var id_municipio = id_municipio.replace(/^\s+|\s+$/g, ""); 
		document.getElementById("id_municipio").value=id_municipio;
		if(id_municipio == ""){
			document.getElementById("id_localidad").value="";
			var dataString = 'id_estado=x';
			$.ajax({
				type: "POST",
				url: "localidades/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_localidad").html(data);
				}
			});
		}else{
			var dataString = 'id_estado='+id_estado+'&id_municipio='+id_municipio;
			$.ajax({
				type: "POST",
				url: "localidades/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_localidad").html(data);
				}
			}); 
		}
	}

	function fechaValida(dateString){
		var regEx = /^\d{4}-\d{2}-\d{2}$/;
		if(!dateString.match(regEx)) return false;  // Invalid format
		var d = new Date(dateString);
		if(Number.isNaN(d.getTime())) return false; // Invalid date
		return d.toISOString().slice(0,10) === dateString;
	}

	function codigo_reserva(value) {
		document.getElementById("codigo_reserva").value=value.toUpperCase();
	}

	function pasajerosTotales(){
		var menores = document.getElementById("menores").value;
		var adultos = document.getElementById("adultos").value;
		var juniors = document.getElementById("juniors").value;
		var suma= parseInt(menores)+parseInt(adultos)+parseInt(juniors);
		document.getElementById("pasajeros_totales").value=suma;
	}
	function pasajeros(){
		var id_cliente = document.getElementById("id_cliente").value; 
		if(id_cliente == ""){
			var dataString = 'id_cliente=x';
			$.ajax({
				type: "POST",
				url: "pasajeros/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_pasajero").html(data);
				}
			});
		}else{
			var dataString = 'id_cliente='+id_cliente;
			$.ajax({
				type: "POST",
				url: "pasajeros/ajax.php",
				data: dataString,
				success: function(data) {
					$("#id_pasajero").html(data);
				}
			});
		}
	}
	$(function() {
		$('#hora').timepicker({ 
			timeFormat: 'H:i:s',
			showDuration: true,
			interval: 15,
			scrollDefault: "now",
		}); 
	});
	$( function() {
		$( "#fecha" ).datepicker({ 
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true, 
			dateFormat: 'yy-mm-dd', 
			onSelect: function (date) { 
				document.getElementById("fecha").style.border= "";
			}
		}); 
	});
	Number.prototype.format = function(n, x, s, c) {
	    var re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\D' : '$') + ')',
	        num = this.toFixed(Math.max(0, ~~n));

	    return (c ? num.replace('.', c) : num).replace(new RegExp(re, 'g'), '$&' + (s || ','));
	};
