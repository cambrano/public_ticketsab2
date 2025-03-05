<?php
	function encrypt_ab_check($string) {
		include 'keySistema/key.php'; 
		$string.= "_".rand();
		$key = 'puto maricon el que lo lea y mandame mensaje si lo logras por favor '.$codigo_plataforma;
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}

	function encrypt_ab_checkSnRnd($string) {
		include 'keySistema/key.php'; 
		$key = 'puto maricon el que lo lea y mandame mensaje si lo logras por favor '.$codigo_plataforma;
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}

	function decrypt_ab_check($string) {
		include 'keySistema/key.php'; 
		$key = 'puto maricon el que lo lea y mandame mensaje si lo logras por favor '.$codigo_plataforma;
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}