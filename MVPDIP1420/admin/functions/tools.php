<?php
function sig_letra($letra){
	for($x = $letra; $x < 'ZZZ'; $x++){
		$x++;
		$next = $x;
		break;
	}
	return $next;
}
function coloresRandom($tipo=null){

	$colores_array['DarkShades1'][0] = array('hex' => '271e4b', 'rgb' => '39,30,75');
	$colores_array['DarkShades1'][1] = array('hex' => '20123c', 'rgb' => '32,18,60');
	$colores_array['DarkShades1'][2] = array('hex' => '241056', 'rgb' => '36,16,86');
	$colores_array['DarkShades1'][3] = array('hex' => '3b2763', 'rgb' => '59,39,99');
	$colores_array['DarkShades1'][4] = array('hex' => '55417b', 'rgb' => '85,65,123');
	$colores_array['DarkShades2'][0] = array('hex' => '080a3f', 'rgb' => '8,10,63');
	$colores_array['DarkShades2'][1] = array('hex' => '01263f', 'rgb' => '1,38,63');
	$colores_array['DarkShades2'][2] = array('hex' => '0e384b', 'rgb' => '14,56,75');
	$colores_array['DarkShades2'][3] = array('hex' => '0b3243', 'rgb' => '11,50,67');
	$colores_array['DarkShades2'][4] = array('hex' => '0c242b', 'rgb' => '12,36,43');
	$colores_array['DarkShades3'][0] = array('hex' => '1d380e', 'rgb' => '29,56,14');
	$colores_array['DarkShades3'][1] = array('hex' => '182917', 'rgb' => '24,41,23');
	$colores_array['DarkShades3'][2] = array('hex' => '193c1b', 'rgb' => '25,60,27');
	$colores_array['DarkShades3'][3] = array('hex' => '273d0d', 'rgb' => '39,61,13');
	$colores_array['DarkShades3'][4] = array('hex' => '0c3220', 'rgb' => '12,50,32');
	$colores_array['Chinese Red Dark'][0] = array('hex' => 'aa381e', 'rgb' => '170,56,30');
	$colores_array['Chinese Red Dark'][1] = array('hex' => 'cc230a', 'rgb' => '204,35,10');
	$colores_array['Chinese Red Dark'][2] = array('hex' => 'cc3300', 'rgb' => '204,51,0');
	$colores_array['Chinese Red Dark'][3] = array('hex' => 'b51f09', 'rgb' => '181,31,9');
	$colores_array['Chinese Red Dark'][4] = array('hex' => '9e1b08', 'rgb' => '158,27,8');
	$colores_array['DarkerShades'][0] = array('hex' => '7e747c', 'rgb' => '126,116,124');
	$colores_array['DarkerShades'][1] = array('hex' => 'abb27f', 'rgb' => '171,178,127');
	$colores_array['DarkerShades'][2] = array('hex' => 'a96969', 'rgb' => '169,105,105');
	$colores_array['DarkerShades'][3] = array('hex' => '709ca4', 'rgb' => '112,156,164');
	$colores_array['DarkerShades'][4] = array('hex' => '6f936e', 'rgb' => '111,147,110');
	$colores_array['Dark-a-dot 2'][0] = array('hex' => '511c1c', 'rgb' => '81,28,28');
	$colores_array['Dark-a-dot 2'][1] = array('hex' => '1f421f', 'rgb' => '31,66,31');
	$colores_array['Dark-a-dot 2'][2] = array('hex' => '12122b', 'rgb' => '18,18,43');
	$colores_array['Dark-a-dot 2'][3] = array('hex' => '3a1b35', 'rgb' => '58,27,53');
	$colores_array['Dark-a-dot 2'][4] = array('hex' => '2f533e', 'rgb' => '47,83,62');
	$colores_array['dark tones 1'][0] = array('hex' => '333333', 'rgb' => '51,51,51');
	$colores_array['dark tones 1'][1] = array('hex' => '304f2d', 'rgb' => '48,79,45');
	$colores_array['dark tones 1'][2] = array('hex' => '453823', 'rgb' => '69,56,35');
	$colores_array['dark tones 1'][3] = array('hex' => '003366', 'rgb' => '0,51,102');
	$colores_array['dark tones 1'][4] = array('hex' => '003333', 'rgb' => '0,51,51');
	$colores_array['Favorite Darks'][0] = array('hex' => '1d1d1d', 'rgb' => '29,29,29');
	$colores_array['Favorite Darks'][1] = array('hex' => '1d0320', 'rgb' => '29,3,32');
	$colores_array['Favorite Darks'][2] = array('hex' => '111118', 'rgb' => '17,17,24');
	$colores_array['Favorite Darks'][3] = array('hex' => '380e0e', 'rgb' => '56,14,14');
	$colores_array['Favorite Darks'][4] = array('hex' => '021808', 'rgb' => '2,24,8');
	$colores_array['shades of dark turquoise'][0] = array('hex' => '7fe6e8', 'rgb' => '127,230,232');
	$colores_array['shades of dark turquoise'][1] = array('hex' => '99ebec', 'rgb' => '153,235,236');
	$colores_array['shades of dark turquoise'][2] = array('hex' => 'b2f0f1', 'rgb' => '178,240,241');
	$colores_array['shades of dark turquoise'][3] = array('hex' => 'ccf5f5', 'rgb' => '204,245,245');
	$colores_array['shades of dark turquoise'][4] = array('hex' => 'e5fafa', 'rgb' => '229,250,250');
	$colores_array['Dark Blues and Blacks'][0] = array('hex' => '232c31', 'rgb' => '35,44,49');
	$colores_array['Dark Blues and Blacks'][1] = array('hex' => '1d2528', 'rgb' => '29,37,40');
	$colores_array['Dark Blues and Blacks'][2] = array('hex' => '37464d', 'rgb' => '55,70,77');
	$colores_array['Dark Blues and Blacks'][3] = array('hex' => '1f272a', 'rgb' => '31,39,42');
	$colores_array['Dark Blues and Blacks'][4] = array('hex' => '3c4d54', 'rgb' => '60,77,84');
	$colores_array['Dark Rainbow'][0] = array('hex' => '511313', 'rgb' => '81,19,19');
	$colores_array['Dark Rainbow'][1] = array('hex' => '54391e', 'rgb' => '84,57,30');
	$colores_array['Dark Rainbow'][2] = array('hex' => '4f4416', 'rgb' => '79,68,22');
	$colores_array['Dark Rainbow'][3] = array('hex' => '0f401f', 'rgb' => '15,64,31');
	$colores_array['Dark Rainbow'][4] = array('hex' => '171438', 'rgb' => '23,20,56');
	$colores_array['Dark Mist'][0] = array('hex' => '1a6d74', 'rgb' => '26,109,116');
	$colores_array['Dark Mist'][1] = array('hex' => '354674', 'rgb' => '53,70,116');
	$colores_array['Dark Mist'][2] = array('hex' => '6bccc4', 'rgb' => '107,204,196');
	$colores_array['Dark Mist'][3] = array('hex' => '4bc95a', 'rgb' => '75,201,90');
	$colores_array['Dark Mist'][4] = array('hex' => '969696', 'rgb' => '150,150,150');
	$colores_array['NEWS dark'][0] = array('hex' => 'ffffff', 'rgb' => '255,255,255');
	$colores_array['NEWS dark'][1] = array('hex' => '7a6889', 'rgb' => '122,104,137');
	$colores_array['NEWS dark'][2] = array('hex' => 'a97199', 'rgb' => '169,113,153');
	$colores_array['NEWS dark'][3] = array('hex' => 'aea76e', 'rgb' => '174,167,110');
	$colores_array['NEWS dark'][4] = array('hex' => '7db674', 'rgb' => '125,182,116');
	$colores_array['Blood In The Dark'][0] = array('hex' => '000000', 'rgb' => '0,0,0');
	$colores_array['Blood In The Dark'][1] = array('hex' => '110000', 'rgb' => '17,0,0');
	$colores_array['Blood In The Dark'][2] = array('hex' => '220000', 'rgb' => '34,0,0');
	$colores_array['Blood In The Dark'][3] = array('hex' => '330000', 'rgb' => '51,0,0');
	$colores_array['Blood In The Dark'][4] = array('hex' => '440000', 'rgb' => '68,0,0');
	$colores_array['Darkside Tales'][0] = array('hex' => '393c49', 'rgb' => '57,60,73');
	$colores_array['Darkside Tales'][1] = array('hex' => '405867', 'rgb' => '64,88,103');
	$colores_array['Darkside Tales'][2] = array('hex' => '3d6669', 'rgb' => '61,102,105');
	$colores_array['Darkside Tales'][3] = array('hex' => '3c786d', 'rgb' => '60,120,109');
	$colores_array['Darkside Tales'][4] = array('hex' => 'ab2929', 'rgb' => '171,41,41');
	$colores_array['Dark Blues - Links'][0] = array('hex' => '084c9e', 'rgb' => '8,76,158');
	$colores_array['Dark Blues - Links'][1] = array('hex' => '002880', 'rgb' => '0,40,128');
	$colores_array['Dark Blues - Links'][2] = array('hex' => '303c49', 'rgb' => '48,60,73');
	$colores_array['Dark Blues - Links'][3] = array('hex' => '040d3c', 'rgb' => '4,13,60');
	$colores_array['Dark Blues - Links'][4] = array('hex' => '022e3e', 'rgb' => '2,46,62');
	$colores_array['Darky'][0] = array('hex' => '510000', 'rgb' => '81,0,0');
	$colores_array['Darky'][1] = array('hex' => '401a00', 'rgb' => '64,26,0');
	$colores_array['Darky'][2] = array('hex' => '353600', 'rgb' => '53,54,0');
	$colores_array['Darky'][3] = array('hex' => '002f01', 'rgb' => '0,47,1');
	$colores_array['Darky'][4] = array('hex' => '000a3e', 'rgb' => '0,10,62');
	$colores_array['Dark Kitchen'][0] = array('hex' => '199d99', 'rgb' => '25,157,153');
	$colores_array['Dark Kitchen'][1] = array('hex' => '734930', 'rgb' => '115,73,48');
	$colores_array['Dark Kitchen'][2] = array('hex' => 'bf988f', 'rgb' => '191,152,143');
	$colores_array['Dark Kitchen'][3] = array('hex' => 'a67063', 'rgb' => '166,112,99');
	$colores_array['Dark Kitchen'][4] = array('hex' => '593027', 'rgb' => '89,48,39');

	$color_tipo = array_rand($colores_array, 1);
	$color_key = array_rand($colores_array[$color_tipo], 1);

	if($tipo != null){
		$return = $colores_array[$color_tipo][$color_key][$tipo];
	}else{
		$return = $colores_array[$color_tipo][$color_key];
	}

	return $return;
}
function celda_excel($key){
	$arraAbecedario= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$keyvalor=$key;
	$val=0;
	$dir=0;
	$celda="";
	for ($i=0; $i <= $keyvalor ; $i++) {
		if($val==26){
			
			$val=0;
			$dir=$dir+1;
		}
		if($dir>0){
			$celda= celda_excel($dir-1).$arraAbecedario[$val];
		}else{
			$celda= $arraAbecedario[$val];
		}
		
		$val=$val+1;
	}
	return $celda;
}
function numero_abecedario($value){
	$arraAbecedario= array(0=>'@',1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M',14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
	
	if($value <= 26){
		$celda[] = $arraAbecedario[$value];
	}else{
		$indx = 1;
		for ($i=1; $i <= $value; $i++) { 
			if($indx == 27){
				$indx = 1;
				echo '-'.$arraAbecedario[$indx];
				$indx ++;
				echo "<br>";
			}else{
				echo $celda = $arraAbecedario[$indx];
				echo "<br>";
				$indx ++;
			}
			
		}
	}

	

	return $celda;
}
function primeros_pasos(){
	include 'db.php';
	//claves
	$sql="SELECT * FROM configuracion WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$configuracion=$row['id'];
	if($configuracion!=""){
		$array['configuracion_inicial']=1;
	}else{
		$array['configuracion_inicial']=0;
	}

	//claves
	$sql="SELECT * FROM claves WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$claves=$row['id'];
	if($claves!=""){
		$array['claves']=1;
	}else{
		$array['claves']=0;
	}

	//usuarios
	$sql="SELECT apellido_paterno FROM empleados WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$empleados=$row['apellido_paterno'];
	if($empleados!=""){
		$array['usuarios']=1;
	}else{
		$array['usuarios']=0;
	}

	//banco debito
	$sql="SELECT id FROM bancos WHERE id_tipo_cuenta=1   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$bancos=$row['id'];
	if($bancos!=""){
		$array['bancos_debito']=1;
	}else{
		$array['bancos_debito']=0;
	}

	//banco credito
	$sql="SELECT id FROM bancos WHERE id_tipo_cuenta=2   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$bancos=$row['id'];
	if($bancos!=""){
		$array['bancos_credito']=1;
	}else{
		$array['bancos_credito']=0;
	}

	//correo sistema
	$sql="SELECT id FROM correo_sistema WHERE status=1   ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$correo_sistema=$row['id'];
	if($correo_sistema!=""){
		$array['correo_sistema']=1;
	}else{
		$array['correo_sistema']=0;
	}

	//notificaciones
	$sql="SELECT id FROM notificaciones_sistema WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$notificaciones_sistema=$row['id'];
	if($notificaciones_sistema!=""){
		$array['notificaciones_sistema']=1;
	}else{
		$array['notificaciones_sistema']=0;
	}

	//sucursales
	$sql="SELECT id FROM sucursales WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$sucursales=$row['id'];
	if($sucursales!=""){
		$array['sucursales']=1;
	}else{
		$array['sucursales']=0;
	}

	//tipos_productos
	$sql="SELECT id FROM tipos_productos WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$tipos_productos=$row['id'];
	if($tipos_productos!=""){
		$array['tipos_productos']=1;
	}else{
		$array['tipos_productos']=0;
	}

	//tipos_habitaciones
	$sql="SELECT id FROM tipos_habitaciones WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$tipos_habitaciones=$row['id'];
	if($tipos_habitaciones!=""){
		$array['tipos_habitaciones']=1;
	}else{
		$array['tipos_habitaciones']=0;
	}

	//tipos_gastos
	$sql="SELECT id FROM tipos_gastos WHERE 1 = 1  ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$tipos_gastos=$row['id'];
	if($tipos_gastos!=""){
		$array['tipos_gastos']=1;
	}else{
		$array['tipos_gastos']=0;
	}
	$conexion->close();
	return $array;
}
function columnasFornaraneas($hija=null,$padre=null){
	include 'db.php';
	$sql="SELECT table_name, column_name, referenced_table_name, referenced_column_name
		FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
		WHERE 
		table_name = '{$hija}' AND
		referenced_table_name IS NOT NULL
		AND referenced_table_name='{$padre}'";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$datos=$row; 
	$conexion->close();
	return $datos;
}
function detectUsuarioDatos(){
	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
	$os=array("WIN","MAC","LINUX");
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$info['browser'] = "OTHER";
	$info['os'] = "OTHER";
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent){
		$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			$info['version'] = $version;
		}
	}
	$getBrowser=getBrowser($_SERVER['HTTP_USER_AGENT']);
	$info['browser'] = $getBrowser;
	# obtenemos el sistema operativo
	$getPlatform=getPlatform($_SERVER['HTTP_USER_AGENT']);
	$info['os'] = $getPlatform;
	# obtenemos puerto
	$info['port'] = $_SERVER['REMOTE_PORT'];
	# obtenemos user agent
	$info['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	# obtenemos user agent
	//$info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	//$info['ip'] = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$info['ip']= $_SERVER['REMOTE_ADDR'];
	}
	# obtenemos user hostname
	$info['hostname'] = gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
	# devolvemos el array de valores
	return $info;
}
function detectUsuarioPOST_Datos($SERVERDATA=null){
	$browser=array("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME");
	$os=array("WIN","MAC","LINUX");
	# definimos unos valores por defecto para el navegador y el sistema operativo
	$info['browser'] = "OTHER";
	$info['os'] = "OTHER";
	# buscamos el navegador con su sistema operativo
	foreach($browser as $parent){
		$s = strpos(strtoupper($SERVERDATA['HTTP_USER_AGENT']), $parent);
		$f = $s + strlen($parent);
		$version = substr($SERVERDATA['HTTP_USER_AGENT'], $f, 15);
		$version = preg_replace('/[^0-9,.]/','',$version);
		if ($s)
		{
			$info['version'] = $version;
		}
	}
	$getBrowser=getBrowser($SERVERDATA['HTTP_USER_AGENT']);
	$info['browser'] = $getBrowser;
	# obtenemos el sistema operativo
	$getPlatform=getPlatform($SERVERDATA['HTTP_USER_AGENT']);
	$info['os'] = $getPlatform;
	# obtenemos puerto
	$info['port'] = $SERVERDATA['REMOTE_PORT'];
	# obtenemos user agent
	$info['user_agent'] = $SERVERDATA['HTTP_USER_AGENT'];
	# obtenemos user agent
	$info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$info['ip'] = $_SERVER['REMOTE_ADDR'];
	# obtenemos user hostname
	$info['hostname'] = gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
	# devolvemos el array de valores
	return $info;
}
function getPlatform($user_agent){
	if(strpos($user_agent, 'Windows NT 10.0') !== FALSE)
		return "Windows 10";
	elseif(strpos($user_agent, 'Windows NT 6.3') !== FALSE)
		return "Windows 8.1";
	elseif(strpos($user_agent, 'Windows NT 6.2') !== FALSE)
		return "Windows 8";
	elseif(strpos($user_agent, 'Windows NT 6.1') !== FALSE)
		return "Windows 7";
	elseif(strpos($user_agent, 'Windows NT 6.0') !== FALSE)
		return "Windows Vista";
	elseif(strpos($user_agent, 'Windows NT 5.1') !== FALSE)
		return "Windows XP";
	elseif(strpos($user_agent, 'Windows NT 5.2') !== FALSE)
		return 'Windows 2003';
	elseif(strpos($user_agent, 'Windows NT 5.0') !== FALSE)
		return 'Windows 2000';
	elseif(strpos($user_agent, 'Windows ME') !== FALSE)
		return 'Windows ME';
	elseif(strpos($user_agent, 'Win98') !== FALSE)
		return 'Windows 98';
	elseif(strpos($user_agent, 'Win95') !== FALSE)
		return 'Windows 95';
	elseif(strpos($user_agent, 'WinNT4.0') !== FALSE)
		return 'Windows NT 4.0';
	elseif(strpos($user_agent, 'Windows Phone') !== FALSE)
		return 'Windows Phone';
	elseif(strpos($user_agent, 'Windows') !== FALSE)
		return 'Windows';
	elseif(strpos($user_agent, 'iPhone') !== FALSE)
		return 'iPhone';
	elseif(strpos($user_agent, 'iPad') !== FALSE)
		return 'iPad';
	elseif(strpos($user_agent, 'Debian') !== FALSE)
		return 'Debian';
	elseif(strpos($user_agent, 'Ubuntu') !== FALSE)
		return 'Ubuntu';
	elseif(strpos($user_agent, 'Slackware') !== FALSE)
		return 'Slackware';
	elseif(strpos($user_agent, 'Linux Mint') !== FALSE)
		return 'Linux Mint';
	elseif(strpos($user_agent, 'Gentoo') !== FALSE)
		return 'Gentoo';
	elseif(strpos($user_agent, 'Elementary OS') !== FALSE)
		return 'ELementary OS';
	elseif(strpos($user_agent, 'Fedora') !== FALSE)
		return 'Fedora';
	elseif(strpos($user_agent, 'Kubuntu') !== FALSE)
		return 'Kubuntu';
	elseif(strpos($user_agent, 'Linux') !== FALSE)
		return 'Linux';
	elseif(strpos($user_agent, 'FreeBSD') !== FALSE)
		return 'FreeBSD';
	elseif(strpos($user_agent, 'OpenBSD') !== FALSE)
		return 'OpenBSD';
	elseif(strpos($user_agent, 'NetBSD') !== FALSE)
		return 'NetBSD';
	elseif(strpos($user_agent, 'SunOS') !== FALSE)
		return 'Solaris';
	elseif(strpos($user_agent, 'BlackBerry') !== FALSE)
		return 'BlackBerry';
	elseif(strpos($user_agent, 'Android') !== FALSE)
		return 'Android';
	elseif(strpos($user_agent, 'Mobile') !== FALSE)
		return 'Firefox OS';
	elseif(strpos($user_agent, 'Mac OS X+') || strpos($user_agent, 'CFNetwork+') !== FALSE)
		return 'Mac OS X';
	elseif(strpos($user_agent, 'Macintosh') !== FALSE)
		return 'Mac OS X';
	elseif(strpos($user_agent, 'OS/2') !== FALSE)
		return 'OS/2';
	elseif(strpos($user_agent, 'BeOS') !== FALSE)
		return 'BeOS';
	elseif(strpos($user_agent, 'Nintendo') !== FALSE)
		return 'Nintendo';
	else
		return 'Unknown Platform';
}
function getBrowser($user_agent){
	if(strpos($user_agent, 'Maxthon') !== FALSE)
		return "Maxthon";
	elseif(strpos($user_agent, 'SeaMonkey') !== FALSE)
		return "SeaMonkey";
	elseif(strpos($user_agent, 'Vivaldi') !== FALSE)
		return "Vivaldi";
	elseif(strpos($user_agent, 'Arora') !== FALSE)
		return "Arora";
	elseif(strpos($user_agent, 'Avant Browser') !== FALSE)
		return "Avant Browser";
	elseif(strpos($user_agent, 'Beamrise') !== FALSE)
		return "Beamrise";
	elseif(strpos($user_agent, 'Epiphany') !== FALSE)
		return 'Epiphany';
	elseif(strpos($user_agent, 'Chromium') !== FALSE)
		return 'Chromium';
	elseif(strpos($user_agent, 'Iceweasel') !== FALSE)
		return 'Iceweasel';
	elseif(strpos($user_agent, 'Galeon') !== FALSE)
		return 'Galeon';
	elseif(strpos($user_agent, 'Edge') !== FALSE)
		return 'Microsoft Edge';
	elseif(strpos($user_agent, 'Trident') !== FALSE) //IE 11
		return 'Internet Explorer';
	elseif(strpos($user_agent, 'MSIE') !== FALSE)
		return 'Internet Explorer';
	elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
		return "Opera Mini";
	elseif(strpos($user_agent, 'Opera') || strpos($user_agent, 'OPR') !== FALSE)
		return "Opera";
	elseif(strpos($user_agent, 'Firefox') !== FALSE)
		return 'Mozilla Firefox';
	elseif(strpos($user_agent, 'Chrome') !== FALSE)
		return 'Google Chrome';
	elseif(strpos($user_agent, 'Safari') !== FALSE)
		return "Safari";
	elseif(strpos($user_agent, 'iTunes') !== FALSE)
		return 'iTunes';
	elseif(strpos($user_agent, 'Konqueror') !== FALSE)
		return 'Konqueror';
	elseif(strpos($user_agent, 'Dillo') !== FALSE)
		return 'Dillo';
	elseif(strpos($user_agent, 'Netscape') !== FALSE)
		return 'Netscape';
	elseif(strpos($user_agent, 'Midori') !== FALSE)
		return 'Midori';
	elseif(strpos($user_agent, 'ELinks') !== FALSE)
		return 'ELinks';
	elseif(strpos($user_agent, 'Links') !== FALSE)
		return 'Links';
	elseif(strpos($user_agent, 'Lynx') !== FALSE)
		return 'Lynx';
	elseif(strpos($user_agent, 'w3m') !== FALSE)
		return 'w3m';
	else
		return 'No hemos podido detectar su navegador';
}
function detectBrowserAndOS($userAgent) {
	$browser = array("IE", "OPERA", "MOZILLA", "NETSCAPE", "FIREFOX", "SAFARI", "CHROME");
	$os = array("WIN", "MAC", "LINUX");
	$log = array();

	$log['browser'] = "OTHER";
	$log['os'] = "OTHER";

	foreach ($browser as $parent) {
		$s = strpos(strtoupper($userAgent), $parent);
		$f = $s + strlen($parent);
		$version = substr($userAgent, $f, 15);
		$version = preg_replace('/[^0-9,.]/', '', $version);
		if ($s) {
			$log['browser'] = $parent;
			$log['version'] = $version;
			break;  // Terminamos el bucle si encontramos el navegador
		}
	}

	foreach ($os as $parent) {
		if (strpos(strtoupper($userAgent), $parent)) {
			$log['os'] = $parent;
			break;  // Terminamos el bucle si encontramos el sistema operativo
		}
	}

	return $log;
}

function server_name($input) {
    $rest = substr($input, 0, 4);
    if ($rest == "www.") {
        return substr($input, 4);
    } else {
        return $input;
    }
}
?>