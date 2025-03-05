<?php
	function ipinfo_io($ip = null){
		if($ip != ""){
			$json = file_get_contents("https://ipinfo.io/{$ip}/geo");
			$data = json_decode($json, true);
		}else{
			$data = false;
		}
		return $data;
	}


	function freegeoip_app($ip = null){
		if($ip != ""){
			$json = file_get_contents("https://freegeoip.app/json/{$_POST['ip']}");
			$data = json_decode($json, true);
		}else{
			$data = false;
		}
		return $data;
	}

	function extreme_ip_lookup($ip = null){
		if($ip != ""){
			$json = file_get_contents("http://extreme-ip-lookup.com/json/{$_POST['ip']}?key=jidr1wki00K7iOUfyaew");
			$data = json_decode($json, true);
		}else{
			$data = false;
		}
		return $data;
	}

	function ip_api($ip = null){
		if($ip != ""){
			$json = file_get_contents("http://ip-api.com/json/{$_POST['ip']}?fields=status,message,asname,mobile,proxy,hosting,query");
			$data = json_decode($json, true);
		}else{
			$data = false;
		}
		return $data;
	}

	function api_ipdata($ip = null){
		if($ip != ""){
			$json = file_get_contents("https://api.ipdata.co/{$_POST['ip']}?api-key=1ee6c3e0c29d83baeaf6502c2a27c0bff4361e24a89de22d4ff5bee8");
			$data = json_decode($json, true);
		}else{
			$data = false;
		}
		return $data;
	}