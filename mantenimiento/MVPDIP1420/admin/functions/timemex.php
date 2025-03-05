<?php 
	date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
	setlocale(LC_ALL,"es_ES");
	$fechaH=date('Y-m-d H:i:s');
	$fechaSH=date('H:i:s');
	$fechaSF=date('Y-m-d');
	$nombreSemana= array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
	$numeroSemanaActual=date('w');
	$diaSemanaActual=$nombreSemana[$numeroSemanaActual];
	$numeroMesActual=date('n');
	$nombreMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
	$mesNombreAcutal=$nombreMes[$numeroMesActual];

	function sumFechaSF($dias=null){
		if($dias==""){
			$dias=0;
		}
		$fecha = date('Y-m-d');
		$nuevafecha = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
		$nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		return $nuevafecha;
	}
	function restaFechaHora($datehora=null,$horas=null){
		if($datehora==""){
			$datehora=$fechaSH;
		}
		$date = new DateTime($datehora);
		$date->modify('+'.$horas.' hours'); 
		$nuevafecha=$date->format('Y-m-d H:i:s');
		return $nuevafecha;
	}

	function convertidorhoras($hora=null,$tipo=null){
		$time = explode(":", $hora);
		if($tipo="minutos"){
			$multiHoras=60;
			$multiMinutos=1;
			$multiSegundos=0;
		}
		$hora=$time[0]*$multiHoras;
		$minutos=$time[1]*$multiMinutos;
		$segundos=$time[2]*$multiSegundos;

		$total=$hora+$minutos+$segundos;
		
		$return['total']=$total;
		$return['unidad']=$tipo;

		return $return; 
	}

	function convertidorAMPM($hora=null,$segundos=null,$tipo_letra=null){
		
		if($tipo_letra=="mayuscula"){
			if($segundos==""){
				$return=date("g:i A",strtotime($hora));
			}else{
				$return=date("g:i:s A",strtotime($hora));
			}
		}else{
			if($segundos==""){
				$return=date("g:i a",strtotime($hora));
			}else{
				$return=date("g:i:s a",strtotime($hora));
			}
		}

		return $return; 
	}



	function fechaNormal($fecha){
		$nombreSemana= array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." de ".$nombreMes[$mes]." del ".$ano;
		return $return;
	}
	function fechaNormal_EN($fecha){
		$nombreSemana= array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','January','February','March','April','May','June','July','August','September','Octubre','November','December'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}

	function fechaNormal_ES($fecha){
		$nombreSemana= array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." de ".$nombreMes[$mes]." del ".$ano;
		return $return;
	}

	function fechaNormalSimpleDDMMAA_EN($fecha){
		$nombreSemana= array('Sun','Mon','Tues','Wed','Thu','Fri','Sat');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 

		$return=$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}
	function fechaNormalSimpleDDMMAA_ES($fecha){
		$nombreSemana= array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Ene','Feb','Mar','Abr','Ma','Jun','Jul','Ago','Sep','Oct','Nov','Dic'); 

		$return=$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}

	function fechaNormalSimpleWDDMMAA_EN($fecha){
		$nombreSemana= array('Sun','Mon','Tues','Wed','Thu','Fri','Sat');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}
	function fechaNormalSimpleWDDMMAA_ES($fecha){
		$nombreSemana= array('Dom','Lun','Mar','Mie','Jue','Vie','Sab');
		$diaSemana=date("w", strtotime($fecha));
		$nombreSemana[$diaSemana];
		$dia=date("d", strtotime($fecha));
		$mes=date("n", strtotime($fecha));
		$ano=date("Y", strtotime($fecha));
		$nombreMes = array('','Ene','Feb','Mar','Abr','Ma','Jun','Jul','Ago','Sep','Oct','Nov','Dic'); 

		$return=$nombreSemana[$diaSemana]." ".$dia." ".$nombreMes[$mes]." ".$ano;
		return $return;
	}
	function edadAnos($fecha_nacimiento){
		$fecha_actual = date('Y-m-d');
		// Se convierten las fechas en objetos DateTime
		$fecha_nacimiento = new DateTime($fecha_nacimiento);
		$fecha_actual = new DateTime($fecha_actual);

		// Se calcula la diferencia entre las fechas
		$intervalo = $fecha_nacimiento->diff($fecha_actual);
		return $intervalo->y;
	}
	?>