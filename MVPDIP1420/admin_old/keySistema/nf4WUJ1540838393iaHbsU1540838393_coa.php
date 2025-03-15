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


		$mun_array_020202asd['2300'] = array ('municipio' => 'Abalá' , 'latitud' => '20.65776961013225' , 'longitud' => '-89.67944870345349' , 'id_estado' => '31');
		$mun_array_020202asd['2301'] = array ('municipio' => 'Acanceh' , 'latitud' => '20.841422554444847' , 'longitud' => '-89.45359397116677' , 'id_estado' => '31');
		$mun_array_020202asd['2302'] = array ('municipio' => 'Akil' , 'latitud' => '20.28144569946031' , 'longitud' => '-89.34544382793123' , 'id_estado' => '31');
		$mun_array_020202asd['2303'] = array ('municipio' => 'Baca' , 'latitud' => '21.113922223493976' , 'longitud' => '-89.39021020747991' , 'id_estado' => '31');
		$mun_array_020202asd['2304'] = array ('municipio' => 'Bokobá' , 'latitud' => '21.00304461323227' , 'longitud' => '-89.17785402217017' , 'id_estado' => '31');
		$mun_array_020202asd['2305'] = array ('municipio' => 'Buctzotz' , 'latitud' => '21.22624967742546' , 'longitud' => '-88.6416606042975' , 'id_estado' => '31');
		$mun_array_020202asd['2306'] = array ('municipio' => 'Cacalchén' , 'latitud' => '20.98614454815471' , 'longitud' => '-89.25713964934124' , 'id_estado' => '31');
		$mun_array_020202asd['2307'] = array ('municipio' => 'Calotmul' , 'latitud' => '21.007960704403445' , 'longitud' => '-88.1237393761171' , 'id_estado' => '31');
		$mun_array_020202asd['2308'] = array ('municipio' => 'Cansahcab' , 'latitud' => '21.14980472191569' , 'longitud' => '-89.1095552378221' , 'id_estado' => '31');
		$mun_array_020202asd['2309'] = array ('municipio' => 'Cantamayec' , 'latitud' => '20.43649819712509' , 'longitud' => '-89.04631833732155' , 'id_estado' => '31');
		$mun_array_020202asd['2310'] = array ('municipio' => 'Celestún' , 'latitud' => '20.898804763969338' , 'longitud' => '-90.2739865998002' , 'id_estado' => '31');
		$mun_array_020202asd['2311'] = array ('municipio' => 'Cenotillo' , 'latitud' => '21.027623540740212' , 'longitud' => '-88.60112557925682' , 'id_estado' => '31');
		$mun_array_020202asd['2312'] = array ('municipio' => 'Conkal' , 'latitud' => '21.07542905426204' , 'longitud' => '-89.51313267385919' , 'id_estado' => '31');
		$mun_array_020202asd['2313'] = array ('municipio' => 'Cuncunul' , 'latitud' => '20.626464641159526' , 'longitud' => '-88.34501830022836' , 'id_estado' => '31');
		$mun_array_020202asd['2314'] = array ('municipio' => 'Cuzamá' , 'latitud' => '20.736304202145647' , 'longitud' => '-89.35524052111062' , 'id_estado' => '31');
		$mun_array_020202asd['2315'] = array ('municipio' => 'Chacsinkín' , 'latitud' => '20.224590854485218' , 'longitud' => '-89.00454376340072' , 'id_estado' => '31');
		$mun_array_020202asd['2316'] = array ('municipio' => 'Chankom' , 'latitud' => '20.541176944245723' , 'longitud' => '-88.54645849074349' , 'id_estado' => '31');
		$mun_array_020202asd['2317'] = array ('municipio' => 'Chapab' , 'latitud' => '20.477657632555225' , 'longitud' => '-89.47803953397163' , 'id_estado' => '31');
		$mun_array_020202asd['2318'] = array ('municipio' => 'Chemax' , 'latitud' => '20.744833682992333' , 'longitud' => '-87.81176958305825' , 'id_estado' => '31');
		$mun_array_020202asd['2319'] = array ('municipio' => 'Chicxulub Pueblo' , 'latitud' => '21.15455178776047' , 'longitud' => '-89.53859060480714' , 'id_estado' => '31');
		$mun_array_020202asd['2320'] = array ('municipio' => 'Chichimilá' , 'latitud' => '20.421603196133226' , 'longitud' => '-88.16948449992456' , 'id_estado' => '31');
		$mun_array_020202asd['2321'] = array ('municipio' => 'Chikindzonot' , 'latitud' => '20.28575117810459' , 'longitud' => '-88.52907644856138' , 'id_estado' => '31');
		$mun_array_020202asd['2322'] = array ('municipio' => 'Chocholá' , 'latitud' => '20.742382796061513' , 'longitud' => '-89.87874050665495' , 'id_estado' => '31');
		$mun_array_020202asd['2323'] = array ('municipio' => 'Chumayel' , 'latitud' => '20.466755270706994' , 'longitud' => '-89.28816058741882' , 'id_estado' => '31');
		$mun_array_020202asd['2324'] = array ('municipio' => 'Dzán' , 'latitud' => '20.386522198971612' , 'longitud' => '-89.45051663088671' , 'id_estado' => '31');
		$mun_array_020202asd['2325'] = array ('municipio' => 'Dzemul' , 'latitud' => '21.250773825837534' , 'longitud' => '-89.35682286860984' , 'id_estado' => '31');
		$mun_array_020202asd['2326'] = array ('municipio' => 'Dzidzantún' , 'latitud' => '21.283053899772742' , 'longitud' => '-89.017150666536' , 'id_estado' => '31');
		$mun_array_020202asd['2327'] = array ('municipio' => 'Dzilam de Bravo' , 'latitud' => '21.423325480372238' , 'longitud' => '-88.69320164583921' , 'id_estado' => '31');
		$mun_array_020202asd['2328'] = array ('municipio' => 'Dzilam González' , 'latitud' => '21.331977358345824' , 'longitud' => '-88.7408582248939' , 'id_estado' => '31');
		$mun_array_020202asd['2329'] = array ('municipio' => 'Dzitás' , 'latitud' => '20.819146910878317' , 'longitud' => '-88.5368509791957' , 'id_estado' => '31');
		$mun_array_020202asd['2330'] = array ('municipio' => 'Dzoncauich' , 'latitud' => '21.09286977118709' , 'longitud' => '-88.8385534053941' , 'id_estado' => '31');
		$mun_array_020202asd['2331'] = array ('municipio' => 'Espita' , 'latitud' => '21.013959146168027' , 'longitud' => '-88.36649363016818' , 'id_estado' => '31');
		$mun_array_020202asd['2332'] = array ('municipio' => 'Halachó' , 'latitud' => '20.53981523507309' , 'longitud' => '-90.18978004974966' , 'id_estado' => '31');
		$mun_array_020202asd['2333'] = array ('municipio' => 'Hocabá' , 'latitud' => '20.810035616447646' , 'longitud' => '-89.2239257089906' , 'id_estado' => '31');
		$mun_array_020202asd['2334'] = array ('municipio' => 'Hoctún' , 'latitud' => '20.892616644472234' , 'longitud' => '-89.18379467280573' , 'id_estado' => '31');
		$mun_array_020202asd['2335'] = array ('municipio' => 'Homún' , 'latitud' => '20.687075777689856' , 'longitud' => '-89.25665811777615' , 'id_estado' => '31');
		$mun_array_020202asd['2336'] = array ('municipio' => 'Huhí' , 'latitud' => '20.693105296884696' , 'longitud' => '-89.12810244926399' , 'id_estado' => '31');
		$mun_array_020202asd['2337'] = array ('municipio' => 'Hunucmá' , 'latitud' => '21.065532431819264' , 'longitud' => '-89.98228249261828' , 'id_estado' => '31');
		$mun_array_020202asd['2338'] = array ('municipio' => 'Ixil' , 'latitud' => '21.232763906380892' , 'longitud' => '-89.46367007778217' , 'id_estado' => '31');
		$mun_array_020202asd['2339'] = array ('municipio' => 'Izamal' , 'latitud' => '20.918544911888773' , 'longitud' => '-89.00836425059292' , 'id_estado' => '31');
		$mun_array_020202asd['2340'] = array ('municipio' => 'Kanasín' , 'latitud' => '20.919819636417458' , 'longitud' => '-89.54282646103067' , 'id_estado' => '31');
		$mun_array_020202asd['2341'] = array ('municipio' => 'Kantunil' , 'latitud' => '20.77071216699344' , 'longitud' => '-88.99751077919484' , 'id_estado' => '31');
		$mun_array_020202asd['2342'] = array ('municipio' => 'Kaua' , 'latitud' => '20.61666203013991' , 'longitud' => '-88.41608435934995' , 'id_estado' => '31');
		$mun_array_020202asd['2343'] = array ('municipio' => 'Kinchil' , 'latitud' => '20.869868027688412' , 'longitud' => '-90.07118423398532' , 'id_estado' => '31');
		$mun_array_020202asd['2344'] = array ('municipio' => 'Kopomá' , 'latitud' => '20.642440049131718' , 'longitud' => '-89.87266243707855' , 'id_estado' => '31');
		$mun_array_020202asd['2345'] = array ('municipio' => 'Mama' , 'latitud' => '20.491525324959465' , 'longitud' => '-89.38178110811408' , 'id_estado' => '31');
		$mun_array_020202asd['2346'] = array ('municipio' => 'Maní' , 'latitud' => '20.385048267139076' , 'longitud' => '-89.36671171041075' , 'id_estado' => '31');
		$mun_array_020202asd['2347'] = array ('municipio' => 'Maxcanú' , 'latitud' => '20.658961021929937' , 'longitud' => '-90.1207985096158' , 'id_estado' => '31');
		$mun_array_020202asd['2348'] = array ('municipio' => 'Mayapán' , 'latitud' => '20.47298524874929' , 'longitud' => '-89.21158855571808' , 'id_estado' => '31');
		$mun_array_020202asd['2349'] = array ('municipio' => 'Mérida' , 'latitud' => '20.983675165925643' , 'longitud' => '-89.63707224448973' , 'id_estado' => '31');
		$mun_array_020202asd['2350'] = array ('municipio' => 'Mocochá' , 'latitud' => '21.119458400951014' , 'longitud' => '-89.45317227350314' , 'id_estado' => '31');
		$mun_array_020202asd['2351'] = array ('municipio' => 'Motul' , 'latitud' => '21.119468581558916' , 'longitud' => '-89.28210583227045' , 'id_estado' => '31');
		$mun_array_020202asd['2352'] = array ('municipio' => 'Muna' , 'latitud' => '20.48778514560519' , 'longitud' => '-89.6970684425367' , 'id_estado' => '31');
		$mun_array_020202asd['2353'] = array ('municipio' => 'Muxupip' , 'latitud' => '21.044462541222092' , 'longitud' => '-89.32226570028503' , 'id_estado' => '31');
		$mun_array_020202asd['2354'] = array ('municipio' => 'Opichén' , 'latitud' => '20.511885887340636' , 'longitud' => '-89.85228920337165' , 'id_estado' => '31');
		$mun_array_020202asd['2355'] = array ('municipio' => 'Oxkutzcab' , 'latitud' => '20.109182198978505' , 'longitud' => '-89.54487211419936' , 'id_estado' => '31');
		$mun_array_020202asd['2356'] = array ('municipio' => 'Panabá' , 'latitud' => '21.354640750219794' , 'longitud' => '-88.3139809036322' , 'id_estado' => '31');
		$mun_array_020202asd['2357'] = array ('municipio' => 'Peto' , 'latitud' => '20.077550816840176' , 'longitud' => '-88.81881296693267' , 'id_estado' => '31');
		$mun_array_020202asd['2358'] = array ('municipio' => 'Progreso' , 'latitud' => '21.228194121311823' , 'longitud' => '-89.6896135917762' , 'id_estado' => '31');
		$mun_array_020202asd['2359'] = array ('municipio' => 'Quintana Roo' , 'latitud' => '20.846798630531843' , 'longitud' => '-88.62500266551139' , 'id_estado' => '31');
		$mun_array_020202asd['2360'] = array ('municipio' => 'Río Lagartos' , 'latitud' => '21.5439823527898' , 'longitud' => '-88.10176460613106' , 'id_estado' => '31');
		$mun_array_020202asd['2361'] = array ('municipio' => 'Sacalum' , 'latitud' => '20.518966393285105' , 'longitud' => '-89.58352945498393' , 'id_estado' => '31');
		$mun_array_020202asd['2362'] = array ('municipio' => 'Samahil' , 'latitud' => '20.828266658231023' , 'longitud' => '-89.9689198461189' , 'id_estado' => '31');
		$mun_array_020202asd['2363'] = array ('municipio' => 'Sanahcat' , 'latitud' => '20.778620667766454' , 'longitud' => '-89.14489033660787' , 'id_estado' => '31');
		$mun_array_020202asd['2364'] = array ('municipio' => 'San Felipe' , 'latitud' => '21.496488113059254' , 'longitud' => '-88.32316382417538' , 'id_estado' => '31');
		$mun_array_020202asd['2365'] = array ('municipio' => 'Santa Elena' , 'latitud' => '20.29454810252674' , 'longitud' => '-89.73358470736916' , 'id_estado' => '31');
		$mun_array_020202asd['2366'] = array ('municipio' => 'Seyé' , 'latitud' => '20.855998045744393' , 'longitud' => '-89.34530694044521' , 'id_estado' => '31');
		$mun_array_020202asd['2367'] = array ('municipio' => 'Sinanché' , 'latitud' => '21.26252270258178' , 'longitud' => '-89.18408406328803' , 'id_estado' => '31');
		$mun_array_020202asd['2368'] = array ('municipio' => 'Sotuta' , 'latitud' => '20.606474509903848' , 'longitud' => '-88.99122315128858' , 'id_estado' => '31');
		$mun_array_020202asd['2369'] = array ('municipio' => 'Sucilá' , 'latitud' => '21.200174449038332' , 'longitud' => '-88.37415220044697' , 'id_estado' => '31');
		$mun_array_020202asd['2370'] = array ('municipio' => 'Sudzal' , 'latitud' => '20.80470407425432' , 'longitud' => '-88.880180473065' , 'id_estado' => '31');
		$mun_array_020202asd['2371'] = array ('municipio' => 'Suma' , 'latitud' => '21.089250003133174' , 'longitud' => '-89.13912819359207' , 'id_estado' => '31');
		$mun_array_020202asd['2372'] = array ('municipio' => 'Tahdziú' , 'latitud' => '20.2500135177885' , 'longitud' => '-88.90231470190794' , 'id_estado' => '31');
		$mun_array_020202asd['2373'] = array ('municipio' => 'Tahmek' , 'latitud' => '20.911357504591027' , 'longitud' => '-89.25399136295842' , 'id_estado' => '31');
		$mun_array_020202asd['2374'] = array ('municipio' => 'Teabo' , 'latitud' => '20.378872515825528' , 'longitud' => '-89.23169800344729' , 'id_estado' => '31');
		$mun_array_020202asd['2375'] = array ('municipio' => 'Tecoh' , 'latitud' => '20.672033961312994' , 'longitud' => '-89.4814218050031' , 'id_estado' => '31');
		$mun_array_020202asd['2376'] = array ('municipio' => 'Tekal de Venegas' , 'latitud' => '21.016826554820604' , 'longitud' => '-88.85733168149815' , 'id_estado' => '31');
		$mun_array_020202asd['2377'] = array ('municipio' => 'Tekantó' , 'latitud' => '21.004736752170654' , 'longitud' => '-89.10822773638644' , 'id_estado' => '31');
		$mun_array_020202asd['2378'] = array ('municipio' => 'Tekax' , 'latitud' => '19.921371651329544' , 'longitud' => '-89.29537078068807' , 'id_estado' => '31');
		$mun_array_020202asd['2379'] = array ('municipio' => 'Tekit' , 'latitud' => '20.57503247904895' , 'longitud' => '-89.28254479182972' , 'id_estado' => '31');
		$mun_array_020202asd['2380'] = array ('municipio' => 'Tekom' , 'latitud' => '20.513445640333252' , 'longitud' => '-88.38700957945413' , 'id_estado' => '31');
		$mun_array_020202asd['2381'] = array ('municipio' => 'Telchac Pueblo' , 'latitud' => '21.221787114440477' , 'longitud' => '-89.25372982873796' , 'id_estado' => '31');
		$mun_array_020202asd['2382'] = array ('municipio' => 'Telchac Puerto' , 'latitud' => '21.303954638322036' , 'longitud' => '-89.29347012129543' , 'id_estado' => '31');
		$mun_array_020202asd['2383'] = array ('municipio' => 'Temax' , 'latitud' => '21.171922354478458' , 'longitud' => '-88.92026598769557' , 'id_estado' => '31');
		$mun_array_020202asd['2384'] = array ('municipio' => 'Temozón' , 'latitud' => '20.878310190607653' , 'longitud' => '-88.10390242254142' , 'id_estado' => '31');
		$mun_array_020202asd['2385'] = array ('municipio' => 'Tepakán' , 'latitud' => '21.04121219278584' , 'longitud' => '-89.01383323778657' , 'id_estado' => '31');
		$mun_array_020202asd['2386'] = array ('municipio' => 'Tetiz' , 'latitud' => '20.96526234874531' , 'longitud' => '-90.04902706468832' , 'id_estado' => '31');
		$mun_array_020202asd['2387'] = array ('municipio' => 'Teya' , 'latitud' => '21.06780429932593' , 'longitud' => '-89.07732821313114' , 'id_estado' => '31');
		$mun_array_020202asd['2388'] = array ('municipio' => 'Ticul' , 'latitud' => '20.338902100489175' , 'longitud' => '-89.54483915553767' , 'id_estado' => '31');
		$mun_array_020202asd['2389'] = array ('municipio' => 'Timucuy' , 'latitud' => '20.82654215070659' , 'longitud' => '-89.53321786223056' , 'id_estado' => '31');
		$mun_array_020202asd['2390'] = array ('municipio' => 'Tinum' , 'latitud' => '20.747421155659715' , 'longitud' => '-88.47762174306182' , 'id_estado' => '31');
		$mun_array_020202asd['2391'] = array ('municipio' => 'Tixcacalcupul' , 'latitud' => '20.3745052632104' , 'longitud' => '-88.32680566963336' , 'id_estado' => '31');
		$mun_array_020202asd['2392'] = array ('municipio' => 'Tixkokob' , 'latitud' => '20.981180836468475' , 'longitud' => '-89.3661595677746' , 'id_estado' => '31');
		$mun_array_020202asd['2393'] = array ('municipio' => 'Tixmehuac' , 'latitud' => '20.250287554306848' , 'longitud' => '-89.09529258829325' , 'id_estado' => '31');
		$mun_array_020202asd['2394'] = array ('municipio' => 'Tixpéhual' , 'latitud' => '20.962889134575157' , 'longitud' => '-89.45870655682023' , 'id_estado' => '31');
		$mun_array_020202asd['2395'] = array ('municipio' => 'Tizimín' , 'latitud' => '21.250701519180694' , 'longitud' => '-87.85209223983351' , 'id_estado' => '31');
		$mun_array_020202asd['2396'] = array ('municipio' => 'Tunkás' , 'latitud' => '20.89124733950059' , 'longitud' => '-88.75374896368004' , 'id_estado' => '31');
		$mun_array_020202asd['2397'] = array ('municipio' => 'Tzucacab' , 'latitud' => '19.947054495835005' , 'longitud' => '-89.05378665974706' , 'id_estado' => '31');
		$mun_array_020202asd['2398'] = array ('municipio' => 'Uayma' , 'latitud' => '20.760972645748012' , 'longitud' => '-88.34256230138627' , 'id_estado' => '31');
		$mun_array_020202asd['2399'] = array ('municipio' => 'Ucú' , 'latitud' => '21.089604779600986' , 'longitud' => '-89.79950612807905' , 'id_estado' => '31');
		$mun_array_020202asd['2400'] = array ('municipio' => 'Umán' , 'latitud' => '20.83047054561591' , 'longitud' => '-89.76428367937159' , 'id_estado' => '31');
		$mun_array_020202asd['2401'] = array ('municipio' => 'Valladolid' , 'latitud' => '20.61820371074003' , 'longitud' => '-88.10333477481727' , 'id_estado' => '31');
		$mun_array_020202asd['2402'] = array ('municipio' => 'Xocchel' , 'latitud' => '20.83286432685267' , 'longitud' => '-89.1468066142481' , 'id_estado' => '31');
		$mun_array_020202asd['2403'] = array ('municipio' => 'Yaxcabá' , 'latitud' => '20.49341335963353' , 'longitud' => '-88.75560306482376' , 'id_estado' => '31');
		$mun_array_020202asd['2404'] = array ('municipio' => 'Yaxkukul' , 'latitud' => '21.060574027741666' , 'longitud' => '-89.42372323769732' , 'id_estado' => '31');
		$mun_array_020202asd['2405'] = array ('municipio' => 'Yobaín' , 'latitud' => '21.27269306997122' , 'longitud' => '-89.10829913986545' , 'id_estado' => '31');


		$valor_seguridad_key_nasdajsd = '2349';
		$id_estado = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['id_estado'];
		$id_municipio = $valor_seguridad_key_nasdajsd;
		$latitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['latitud'];
		$longitud = $mun_array_020202asd[$valor_seguridad_key_nasdajsd]['longitud'];
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;


		//$id_estado = 23;
		//$id_municipio = 1813;
		//$longitud="21.1398997";
		//$latitud="-86.8663978";
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

		$id_estado = 5;
		$latitud="27.058676";
		$longitud="-101.7068294";
		$estado_nombre = "Coah.";
		$extranjeros_mode=false;

		/*
		$id_estado = 31;
		$latitud="20.7098786";
		$longitud="-89.0943377";
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;
		*/

	}


	///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
	
	$dbhost = 'localhost'; 
	$db="cambrano_coahuila";
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="root";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="root";
	
?>