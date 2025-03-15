<?php
	//$dbhost="database-1-instance-1-us-east-2b.cywmkfwca0fn.us-east-2.rds.amazonaws.com";
	/*
	$dbhost="mysql1005.mochahost.com";
	$dbport="3306";
	//$dbusuario_user = $dbusuario="cambrano_perMVP";
	//$dbpassword_user = $dbpassword="Z225a3wwZeYd";
	$db="cambrano_yuc";
	$database_users_12X12[] = array('usuario' => 'cambrano_yuc', 'password' => 'yuc2224', );
	$database_users_12X12[] = array('usuario' => 'cambrano_yuc1', 'password' => 'yuc2224', );
	$database_users_12X12[] = array('usuario' => 'cambrano_yuc2', 'password' => 'yuc2224', );
	$database_users_12X12[] = array('usuario' => 'cambrano_yuc3', 'password' => 'yuc2224', );
	$datauser_random = array_rand($database_users_12X12, 1);
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario'];
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password'];
	*/

	$tipo_uso_plataforma = 'all'; // estatal,municipio, distrito_local distrito_federal all
	if($tipo_uso_plataforma == 'municipio'){


		$mun_array_020202asd['2305'] = array ('municipio' => 'ABALA' , 'longitud' => '-89.679448' , 'latitud' => '20.6577696' , 'id_estado' => '31');
		$mun_array_020202asd['2306'] = array ('municipio' => 'ACANCEH' , 'longitud' => '-89.453593' , 'latitud' => '20.8414225' , 'id_estado' => '31');
		$mun_array_020202asd['2307'] = array ('municipio' => 'AKIL' , 'longitud' => '-89.345443' , 'latitud' => '20.2814456' , 'id_estado' => '31');
		$mun_array_020202asd['2308'] = array ('municipio' => 'BACA' , 'longitud' => '-89.39021' , 'latitud' => '21.1139222' , 'id_estado' => '31');
		$mun_array_020202asd['2309'] = array ('municipio' => 'BOKOBA' , 'longitud' => '-89.177854' , 'latitud' => '21.0030446' , 'id_estado' => '31');
		$mun_array_020202asd['2310'] = array ('municipio' => 'BUCTZOTZ' , 'longitud' => '-88.64166' , 'latitud' => '21.2262496' , 'id_estado' => '31');
		$mun_array_020202asd['2311'] = array ('municipio' => 'CACALCHEN' , 'longitud' => '-89.257139' , 'latitud' => '20.9861445' , 'id_estado' => '31');
		$mun_array_020202asd['2312'] = array ('municipio' => 'CALOTMUL' , 'longitud' => '-88.123739' , 'latitud' => '21.0079607' , 'id_estado' => '31');
		$mun_array_020202asd['2313'] = array ('municipio' => 'CANSAHCAB' , 'longitud' => '-89.109555' , 'latitud' => '21.1498047' , 'id_estado' => '31');
		$mun_array_020202asd['2314'] = array ('municipio' => 'CANTAMAYEC' , 'longitud' => '-89.046318' , 'latitud' => '20.4364981' , 'id_estado' => '31');
		$mun_array_020202asd['2315'] = array ('municipio' => 'CELESTUN' , 'longitud' => '-90.273986' , 'latitud' => '20.8988047' , 'id_estado' => '31');
		$mun_array_020202asd['2316'] = array ('municipio' => 'CENOTILLO' , 'longitud' => '-88.601125' , 'latitud' => '21.0276235' , 'id_estado' => '31');
		$mun_array_020202asd['2317'] = array ('municipio' => 'CONKAL' , 'longitud' => '-89.513132' , 'latitud' => '21.075429' , 'id_estado' => '31');
		$mun_array_020202asd['2318'] = array ('municipio' => 'CUNCUNUL' , 'longitud' => '-88.345018' , 'latitud' => '20.6264646' , 'id_estado' => '31');
		$mun_array_020202asd['2319'] = array ('municipio' => 'CUZAMA' , 'longitud' => '-89.35524' , 'latitud' => '20.7363042' , 'id_estado' => '31');
		$mun_array_020202asd['2320'] = array ('municipio' => 'CHACSINKIN' , 'longitud' => '-89.004543' , 'latitud' => '20.2245908' , 'id_estado' => '31');
		$mun_array_020202asd['2321'] = array ('municipio' => 'CHANKOM' , 'longitud' => '-88.546458' , 'latitud' => '20.5411769' , 'id_estado' => '31');
		$mun_array_020202asd['2322'] = array ('municipio' => 'CHAPAB' , 'longitud' => '-89.478039' , 'latitud' => '20.4776576' , 'id_estado' => '31');
		$mun_array_020202asd['2323'] = array ('municipio' => 'CHEMAX' , 'longitud' => '-87.811769' , 'latitud' => '20.7448336' , 'id_estado' => '31');
		$mun_array_020202asd['2324'] = array ('municipio' => 'CHICXULUB PUEBLO' , 'longitud' => '-89.53859' , 'latitud' => '21.1545517' , 'id_estado' => '31');
		$mun_array_020202asd['2325'] = array ('municipio' => 'CHICHIMILA' , 'longitud' => '-88.169484' , 'latitud' => '20.4216031' , 'id_estado' => '31');
		$mun_array_020202asd['2326'] = array ('municipio' => 'CHIKINDZONOT' , 'longitud' => '-88.529076' , 'latitud' => '20.2857511' , 'id_estado' => '31');
		$mun_array_020202asd['2327'] = array ('municipio' => 'CHOCHOLA' , 'longitud' => '-89.87874' , 'latitud' => '20.7423827' , 'id_estado' => '31');
		$mun_array_020202asd['2328'] = array ('municipio' => 'CHUMAYEL' , 'longitud' => '-89.28816' , 'latitud' => '20.4667552' , 'id_estado' => '31');
		$mun_array_020202asd['2329'] = array ('municipio' => 'DZAN' , 'longitud' => '-89.450516' , 'latitud' => '20.3865221' , 'id_estado' => '31');
		$mun_array_020202asd['2330'] = array ('municipio' => 'DZEMUL' , 'longitud' => '-89.356822' , 'latitud' => '21.2507738' , 'id_estado' => '31');
		$mun_array_020202asd['2331'] = array ('municipio' => 'DZIDZANTUN' , 'longitud' => '-89.01715' , 'latitud' => '21.2830538' , 'id_estado' => '31');
		$mun_array_020202asd['2332'] = array ('municipio' => 'DZILAM DE BRAVO' , 'longitud' => '-88.693201' , 'latitud' => '21.4233254' , 'id_estado' => '31');
		$mun_array_020202asd['2333'] = array ('municipio' => 'DZILAM GONZALEZ' , 'longitud' => '-88.740858' , 'latitud' => '21.3319773' , 'id_estado' => '31');
		$mun_array_020202asd['2334'] = array ('municipio' => 'DZITAS' , 'longitud' => '-88.53685' , 'latitud' => '20.8191469' , 'id_estado' => '31');
		$mun_array_020202asd['2335'] = array ('municipio' => 'DZONCAUICH' , 'longitud' => '-88.838553' , 'latitud' => '21.0928697' , 'id_estado' => '31');
		$mun_array_020202asd['2336'] = array ('municipio' => 'ESPITA' , 'longitud' => '-88.366493' , 'latitud' => '21.0139591' , 'id_estado' => '31');
		$mun_array_020202asd['2337'] = array ('municipio' => 'HALACHO' , 'longitud' => '-90.18978' , 'latitud' => '20.5398152' , 'id_estado' => '31');
		$mun_array_020202asd['2338'] = array ('municipio' => 'HOCABA' , 'longitud' => '-89.223925' , 'latitud' => '20.8100356' , 'id_estado' => '31');
		$mun_array_020202asd['2339'] = array ('municipio' => 'HOCTUN' , 'longitud' => '-89.183794' , 'latitud' => '20.8926166' , 'id_estado' => '31');
		$mun_array_020202asd['2340'] = array ('municipio' => 'HOMUN' , 'longitud' => '-89.256658' , 'latitud' => '20.6870757' , 'id_estado' => '31');
		$mun_array_020202asd['2341'] = array ('municipio' => 'HUHI' , 'longitud' => '-89.128102' , 'latitud' => '20.6931052' , 'id_estado' => '31');
		$mun_array_020202asd['2342'] = array ('municipio' => 'HUNUCMA' , 'longitud' => '-89.982282' , 'latitud' => '21.0655324' , 'id_estado' => '31');
		$mun_array_020202asd['2343'] = array ('municipio' => 'IXIL' , 'longitud' => '-89.46367' , 'latitud' => '21.2327639' , 'id_estado' => '31');
		$mun_array_020202asd['2344'] = array ('municipio' => 'IZAMAL' , 'longitud' => '-89.008364' , 'latitud' => '20.9185449' , 'id_estado' => '31');
		$mun_array_020202asd['2345'] = array ('municipio' => 'KANASIN' , 'longitud' => '-89.542053' , 'latitud' => '20.9207214' , 'id_estado' => '31');
		$mun_array_020202asd['2346'] = array ('municipio' => 'KANTUNIL' , 'longitud' => '-88.99751' , 'latitud' => '20.7707121' , 'id_estado' => '31');
		$mun_array_020202asd['2347'] = array ('municipio' => 'KAUA' , 'longitud' => '-88.416084' , 'latitud' => '20.616662' , 'id_estado' => '31');
		$mun_array_020202asd['2348'] = array ('municipio' => 'KINCHIL' , 'longitud' => '-90.071184' , 'latitud' => '20.869868' , 'id_estado' => '31');
		$mun_array_020202asd['2349'] = array ('municipio' => 'KOPOMA' , 'longitud' => '-89.872662' , 'latitud' => '20.6424401' , 'id_estado' => '31');
		$mun_array_020202asd['2350'] = array ('municipio' => 'MAMA' , 'longitud' => '-89.381781' , 'latitud' => '20.4915253' , 'id_estado' => '31');
		$mun_array_020202asd['2351'] = array ('municipio' => 'MANI' , 'longitud' => '-89.366711' , 'latitud' => '20.3850482' , 'id_estado' => '31');
		$mun_array_020202asd['2352'] = array ('municipio' => 'MAXCANU' , 'longitud' => '-90.120798' , 'latitud' => '20.658961' , 'id_estado' => '31');
		$mun_array_020202asd['2353'] = array ('municipio' => 'MAYAPAN' , 'longitud' => '-89.211588' , 'latitud' => '20.4729852' , 'id_estado' => '31');
		$mun_array_020202asd['2354'] = array ('municipio' => 'MERIDA' , 'longitud' => '-89.638798' , 'latitud' => '20.9800781' , 'id_estado' => '31');
		$mun_array_020202asd['2355'] = array ('municipio' => 'MOCOCHA' , 'longitud' => '-89.453172' , 'latitud' => '21.1194584' , 'id_estado' => '31');
		$mun_array_020202asd['2356'] = array ('municipio' => 'MOTUL' , 'longitud' => '-89.282105' , 'latitud' => '21.1194685' , 'id_estado' => '31');
		$mun_array_020202asd['2357'] = array ('municipio' => 'MUNA' , 'longitud' => '-89.697068' , 'latitud' => '20.4877851' , 'id_estado' => '31');
		$mun_array_020202asd['2358'] = array ('municipio' => 'MUXUPIP' , 'longitud' => '-89.322265' , 'latitud' => '21.0444625' , 'id_estado' => '31');
		$mun_array_020202asd['2359'] = array ('municipio' => 'OPICHEN' , 'longitud' => '-89.852289' , 'latitud' => '20.5118859' , 'id_estado' => '31');
		$mun_array_020202asd['2360'] = array ('municipio' => 'OXKUTZCAB' , 'longitud' => '-89.544872' , 'latitud' => '20.1091821' , 'id_estado' => '31');
		$mun_array_020202asd['2361'] = array ('municipio' => 'PANABA' , 'longitud' => '-88.31398' , 'latitud' => '21.3546407' , 'id_estado' => '31');
		$mun_array_020202asd['2362'] = array ('municipio' => 'PETO' , 'longitud' => '-88.818812' , 'latitud' => '20.0775508' , 'id_estado' => '31');
		$mun_array_020202asd['2363'] = array ('municipio' => 'PROGRESO' , 'longitud' => '-89.689613' , 'latitud' => '21.2281941' , 'id_estado' => '31');
		$mun_array_020202asd['2364'] = array ('municipio' => 'QUINTANA ROO' , 'longitud' => '-88.625002' , 'latitud' => '20.8467986' , 'id_estado' => '31');
		$mun_array_020202asd['2365'] = array ('municipio' => 'RIO LAGARTOS' , 'longitud' => '-88.101764' , 'latitud' => '21.5439823' , 'id_estado' => '31');
		$mun_array_020202asd['2366'] = array ('municipio' => 'SACALUM' , 'longitud' => '-89.583529' , 'latitud' => '20.5189663' , 'id_estado' => '31');
		$mun_array_020202asd['2367'] = array ('municipio' => 'SAMAHIL' , 'longitud' => '-89.968919' , 'latitud' => '20.8282666' , 'id_estado' => '31');
		$mun_array_020202asd['2368'] = array ('municipio' => 'SANAHCAT' , 'longitud' => '-89.14489' , 'latitud' => '20.7786206' , 'id_estado' => '31');
		$mun_array_020202asd['2369'] = array ('municipio' => 'SAN FELIPE' , 'longitud' => '-88.323163' , 'latitud' => '21.4964881' , 'id_estado' => '31');
		$mun_array_020202asd['2370'] = array ('municipio' => 'SANTA ELENA' , 'longitud' => '-89.733584' , 'latitud' => '20.2945481' , 'id_estado' => '31');
		$mun_array_020202asd['2371'] = array ('municipio' => 'SEYE' , 'longitud' => '-89.345306' , 'latitud' => '20.855998' , 'id_estado' => '31');
		$mun_array_020202asd['2372'] = array ('municipio' => 'SINANCHE' , 'longitud' => '-89.184084' , 'latitud' => '21.2625227' , 'id_estado' => '31');
		$mun_array_020202asd['2373'] = array ('municipio' => 'SOTUTA' , 'longitud' => '-88.991223' , 'latitud' => '20.6064745' , 'id_estado' => '31');
		$mun_array_020202asd['2374'] = array ('municipio' => 'SUCILA' , 'longitud' => '-88.374152' , 'latitud' => '21.2001744' , 'id_estado' => '31');
		$mun_array_020202asd['2375'] = array ('municipio' => 'SUDZAL' , 'longitud' => '-88.88018' , 'latitud' => '20.804704' , 'id_estado' => '31');
		$mun_array_020202asd['2376'] = array ('municipio' => 'SUMA' , 'longitud' => '-89.139128' , 'latitud' => '21.08925' , 'id_estado' => '31');
		$mun_array_020202asd['2377'] = array ('municipio' => 'TAHDZIU' , 'longitud' => '-88.902314' , 'latitud' => '20.2500135' , 'id_estado' => '31');
		$mun_array_020202asd['2378'] = array ('municipio' => 'TAHMEK' , 'longitud' => '-89.253991' , 'latitud' => '20.9113575' , 'id_estado' => '31');
		$mun_array_020202asd['2379'] = array ('municipio' => 'TEABO' , 'longitud' => '-89.231698' , 'latitud' => '20.3788725' , 'id_estado' => '31');
		$mun_array_020202asd['2380'] = array ('municipio' => 'TECOH' , 'longitud' => '-89.481421' , 'latitud' => '20.6720339' , 'id_estado' => '31');
		$mun_array_020202asd['2381'] = array ('municipio' => 'TEKAL DE VENEGAS' , 'longitud' => '-88.857331' , 'latitud' => '21.0168265' , 'id_estado' => '31');
		$mun_array_020202asd['2382'] = array ('municipio' => 'TEKANTO' , 'longitud' => '-89.108227' , 'latitud' => '21.0047367' , 'id_estado' => '31');
		$mun_array_020202asd['2383'] = array ('municipio' => 'TEKAX' , 'longitud' => '-89.29537' , 'latitud' => '19.9213716' , 'id_estado' => '31');
		$mun_array_020202asd['2384'] = array ('municipio' => 'TEKIT' , 'longitud' => '-89.282544' , 'latitud' => '20.5750324' , 'id_estado' => '31');
		$mun_array_020202asd['2385'] = array ('municipio' => 'TEKOM' , 'longitud' => '-88.387009' , 'latitud' => '20.5134456' , 'id_estado' => '31');
		$mun_array_020202asd['2386'] = array ('municipio' => 'TELCHAC PUEBLO' , 'longitud' => '-89.253729' , 'latitud' => '21.2217871' , 'id_estado' => '31');
		$mun_array_020202asd['2387'] = array ('municipio' => 'TELCHAC PUERTO' , 'longitud' => '-89.29347' , 'latitud' => '21.3039546' , 'id_estado' => '31');
		$mun_array_020202asd['2388'] = array ('municipio' => 'TEMAX' , 'longitud' => '-88.920265' , 'latitud' => '21.1719223' , 'id_estado' => '31');
		$mun_array_020202asd['2389'] = array ('municipio' => 'TEMOZON' , 'longitud' => '-88.103902' , 'latitud' => '20.8783101' , 'id_estado' => '31');
		$mun_array_020202asd['2390'] = array ('municipio' => 'TEPAKAN' , 'longitud' => '-89.013833' , 'latitud' => '21.0412121' , 'id_estado' => '31');
		$mun_array_020202asd['2391'] = array ('municipio' => 'TETIZ' , 'longitud' => '-90.049027' , 'latitud' => '20.9652623' , 'id_estado' => '31');
		$mun_array_020202asd['2392'] = array ('municipio' => 'TEYA' , 'longitud' => '-89.077328' , 'latitud' => '21.0678042' , 'id_estado' => '31');
		$mun_array_020202asd['2393'] = array ('municipio' => 'TICUL' , 'longitud' => '-89.544839' , 'latitud' => '20.3389021' , 'id_estado' => '31');
		$mun_array_020202asd['2394'] = array ('municipio' => 'TIMUCUY' , 'longitud' => '-89.530543' , 'latitud' => '20.8232333' , 'id_estado' => '31');
		$mun_array_020202asd['2395'] = array ('municipio' => 'TINUM' , 'longitud' => '-88.477621' , 'latitud' => '20.7474211' , 'id_estado' => '31');
		$mun_array_020202asd['2396'] = array ('municipio' => 'TIXCACALCUPUL' , 'longitud' => '-88.326805' , 'latitud' => '20.3745052' , 'id_estado' => '31');
		$mun_array_020202asd['2397'] = array ('municipio' => 'TIXKOKOB' , 'longitud' => '-89.366159' , 'latitud' => '20.9811808' , 'id_estado' => '31');
		$mun_array_020202asd['2398'] = array ('municipio' => 'TIXMEHUAC' , 'longitud' => '-89.095292' , 'latitud' => '20.2502875' , 'id_estado' => '31');
		$mun_array_020202asd['2399'] = array ('municipio' => 'TIXPEHUAL' , 'longitud' => '-89.458706' , 'latitud' => '20.962889' , 'id_estado' => '31');
		$mun_array_020202asd['2400'] = array ('municipio' => 'TIZIMIN' , 'longitud' => '-87.852092' , 'latitud' => '21.2507015' , 'id_estado' => '31');
		$mun_array_020202asd['2401'] = array ('municipio' => 'TUNKAS' , 'longitud' => '-88.753748' , 'latitud' => '20.8912473' , 'id_estado' => '31');
		$mun_array_020202asd['2402'] = array ('municipio' => 'TZUCACAB' , 'longitud' => '-89.053786' , 'latitud' => '19.9470544' , 'id_estado' => '31');
		$mun_array_020202asd['2403'] = array ('municipio' => 'UAYMA' , 'longitud' => '-88.342562' , 'latitud' => '20.7609726' , 'id_estado' => '31');
		$mun_array_020202asd['2404'] = array ('municipio' => 'UCU' , 'longitud' => '-89.799506' , 'latitud' => '21.0896047' , 'id_estado' => '31');
		$mun_array_020202asd['2405'] = array ('municipio' => 'UMAN' , 'longitud' => '-89.766426' , 'latitud' => '20.8285779' , 'id_estado' => '31');
		$mun_array_020202asd['2406'] = array ('municipio' => 'VALLADOLID' , 'longitud' => '-88.103334' , 'latitud' => '20.6182037' , 'id_estado' => '31');
		$mun_array_020202asd['2407'] = array ('municipio' => 'XOCCHEL' , 'longitud' => '-89.146806' , 'latitud' => '20.8328643' , 'id_estado' => '31');
		$mun_array_020202asd['2408'] = array ('municipio' => 'YAXCABA' , 'longitud' => '-88.755603' , 'latitud' => '20.4934133' , 'id_estado' => '31');
		$mun_array_020202asd['2409'] = array ('municipio' => 'YAXKUKUL' , 'longitud' => '-89.423723' , 'latitud' => '21.060574' , 'id_estado' => '31');
		$mun_array_020202asd['2410'] = array ('municipio' => 'YOBAIN' , 'longitud' => '-89.108299' , 'latitud' => '21.272693' , 'id_estado' => '31');
		

		/* kanasin 2345 */
		/* Merida 2354 */
		/* Maxcanu 2352 */
		$valor_seguridad_key_nasdajsd = '2354';
		$id_estado = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['id_estado'];
		$id_municipio = $valor_seguridad_key_nasdajsd;
		$latitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['latitud'];
		$longitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['longitud'];
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;


		//$id_estado = 23;
		//$id_municipio = 1813;
		//$latitud="21.1398997";
		//$longitud="-86.8663978";
		//$estado_nombre = "Yuc.";
		//$extranjeros_mode=false;

	}elseif($tipo_uso_plataforma=='distrito_local'){
		$id_estado = 31;
		$id_distrito_local = 1;
		$latitud="20.951549719601513";
		$longitud="-89.5950358963144";
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;

	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$id_estado = 31;
		$id_distrito_federal = 4;
		$latitud="21.001305619082103";
		$longitud="-89.60583353300396";
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;

	}else{

		$id_estado = 31;
		$latitud="20.7098786";
		$longitud="-89.0943377";
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;

	}


	///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
	
	$dbhost = 'localhost'; 
	$db="cambrano_yucatan";
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="root";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="root";
	
?>