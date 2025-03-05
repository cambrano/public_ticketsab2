<?php
function sig_letra($letra){
	for($x = $letra; $x < 'ZZZ'; $x++){
		$x++;
		$next = $x;
		break;
	}
	return $next;
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
		$info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
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
		# obtenemos user hostname
		$info['hostname'] = gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
		# devolvemos el array de valores
		return $info;
	}

	$detectUsuarioDatos=detectUsuarioDatos();
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
?>