<?php
if(!empty($_POST)){
	$info=$_POST['info'][0];
	$codigo_plataforma32='Odwty1613011468X97BM464547302784';
	$stringValid='083T0dTMz+HH5M/P4tbQ0crWm9G6p5Gwk6eWoKiekpyaoMSfr6a6oZapk6KmmpqdmZqj';
	$info['usuario'] = $stringValid;
	$info['password'] = '9leb;b}jA4N4*CwL96w+';
	//$info['fbclid'] = $_GET['fbclid'];
	//$info['lg'] = $_GET['lg'];
	$info['codigo_plataforma'] = $codigo_plataforma32;
	$info['remote_port'] = $_SERVER['REMOTE_PORT'];
	$info['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$info['ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$info['ip']= $_SERVER['REMOTE_ADDR'];
	}
	$info['port'] = $_SERVER['REMOTE_PORT'];
	$info['hostname'] = gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
	$info['server_name'] = $_SERVER['SERVER_NAME'];
	$info['document_root'] = $_SERVER['DOCUMENT_ROOT'];
	$info['request_scheme'] = $_SERVER['REQUEST_SCHEME'];
	$info['server_software'] = $_SERVER['SERVER_SOFTWARE'];
	$info['server_addr'] = $_SERVER['SERVER_ADDR'];
	
	//$info['request_method'] = $_SERVER['REQUEST_METHOD'];
	//$info['request_uri'] = $_SERVER['REQUEST_URI'];
	//$info['script_name'] = $_SERVER['SCRIPT_NAME'];
	$info['php_self'] = $_SERVER['PHP_SELF'];
	
	$info['unique_id'] = $_SERVER['UNIQUE_ID'];
	$info['server_port'] = $_SERVER['SERVER_PORT'];
	$info['server_admin'] = $_SERVER['SERVER_ADMIN'];
	$info['request_time_float'] = $_SERVER['REQUEST_TIME_FLOAT'];

	$usuario_sesiones = $info['usuario_sesiones'];
	$id_usuario = $info['id_usuario'];
	$tipo_usuario = $info['tipo_usuario'];
	unset($info['usuario_sesiones']);
	unset($info['id_usuario']);
	unset($info['tipo_usuario']);

	/*
	//$url = 'https://www.gpsmanager.tk.softwaresada.com/soap/api.php';
	$ch = curl_init($url);
	$var =http_build_query($info);
	$jsonDataEncoded = json_encode($jsonData);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$var);
	$result = curl_exec($ch);
	$obj = json_decode($result);
	*/
	if($usuario_sesiones==2){
		@session_start(); 
		include 'MVPDIP1420/admin/functions/tool_xhpzab.php';
		include 'MVPDIP1420/admin/functions/usuarios.php';
		$_COOKIE['IG2A7DOFSI'];
		$id_usuario = decrypt_ab_checkFinal($_COOKIE['IG2A7DOFSI']);
		$usuarioId = usuarioDatos($id_usuario);
		if($usuarioId['tabla']==''){
			//echo "good";
			die;
		}
		if($usuarioId['tabla']=='secciones_ine_ciudadanos'){
			$tipo_usuario = 'ciudadano';
			$usuario_sesiones = 1;
		}elseif ($usuarioId['tabla']=='empleados') {
			$tipo_usuario = 'usuario';
			$usuario_sesiones = 1;
		}else{
			$usuario_sesiones = '';
		}
	}
	function esDireccionIP($host) {
		// Si es una dirección IP válida
		if (filter_var($host, FILTER_VALIDATE_IP)) {
			return true;
		}
		
		// Si no es una dirección IP, verifica si es un dominio
		$ip = gethostbyname($host);
		return $ip != $host;
	}
	if (esDireccionIP($info['server_name'])) {
		$urlhttps =  "https://";
	} else {
		$urlhttps =  "https://";
	}
	if($usuario_sesiones==1){ 
		$info['tipo_usuario'] = $tipo_usuario;
		$info['id_usuario'] = $id_usuario;

		$url = $urlhttps.$info['server_name'].'/ops/gpstracking_usuarios.php';
		$ch = curl_init($url);
		$var =http_build_query($info);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$var);
		$result = curl_exec($ch);
		$obj = json_decode($result);
		//echo "<pre>";
		//print_r($obj);
		//echo "</pre>";
	}else{
		//echo "<pre>";
		//print_r($info);
		//echo "</pre>";
		$url = $urlhttps.$info['server_name'].'/ops/gpstracking.php';
		$ch = curl_init($url);
		$var =http_build_query($info);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$var);
		$result = curl_exec($ch);
		$obj = json_decode($result);
		//echo "<pre>";
		//print_r($obj);
		//echo "</pre>";
	}
}
