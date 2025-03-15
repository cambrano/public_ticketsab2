<?php
	$dbhost="database-1-instance-1-us-east-2b.cywmkfwca0fn.us-east-2.rds.amazonaws.com";
	$dbport="3306";
	//$dbusuario_user = $dbusuario="cambrano_perMVP";
	//$dbpassword_user = $dbpassword="Z225a3wwZeYd";
	$db="irapuato";
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab1', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab2', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab3', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab4', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab5', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab6', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab7', 'password' => 'JxKjHCdO6vRX', );
	$database_users_12X12[] = array('usuario' => 'cambrano_perTab8', 'password' => 'JxKjHCdO6vRX', );
	$datauser_random = array_rand($database_users_12X12, 1);
	$dbusuario_user = $dbusuario = $database_users_12X12[$datauser_random]['usuario']="admin";
	$dbpassword_user = $dbpassword = $database_users_12X12[$datauser_random]['password']="m3rm3l4d4;";


 
	$tipo_uso_plataforma = 'all'; // estatal,municipio, distrito_local distrito_federal all
	if($tipo_uso_plataforma == 'municipio'){


		$mun_array_020202asd['2300'] = array ('municipio' => 'Abalá' , 'longitud' => '20.65776961013225' , 'latitud' => '-89.67944870345349' , 'id_estado' => '31');
		$mun_array_020202asd['2301'] = array ('municipio' => 'Acanceh' , 'longitud' => '20.841422554444847' , 'latitud' => '-89.45359397116677' , 'id_estado' => '31');
		$mun_array_020202asd['2302'] = array ('municipio' => 'Akil' , 'longitud' => '20.28144569946031' , 'latitud' => '-89.34544382793123' , 'id_estado' => '31');
		$mun_array_020202asd['2303'] = array ('municipio' => 'Baca' , 'longitud' => '21.113922223493976' , 'latitud' => '-89.39021020747991' , 'id_estado' => '31');
		$mun_array_020202asd['2304'] = array ('municipio' => 'Bokobá' , 'longitud' => '21.00304461323227' , 'latitud' => '-89.17785402217017' , 'id_estado' => '31');
		$mun_array_020202asd['2305'] = array ('municipio' => 'Buctzotz' , 'longitud' => '21.22624967742546' , 'latitud' => '-88.6416606042975' , 'id_estado' => '31');
		$mun_array_020202asd['2306'] = array ('municipio' => 'Cacalchén' , 'longitud' => '20.98614454815471' , 'latitud' => '-89.25713964934124' , 'id_estado' => '31');
		$mun_array_020202asd['2307'] = array ('municipio' => 'Calotmul' , 'longitud' => '21.007960704403445' , 'latitud' => '-88.1237393761171' , 'id_estado' => '31');
		$mun_array_020202asd['2308'] = array ('municipio' => 'Cansahcab' , 'longitud' => '21.14980472191569' , 'latitud' => '-89.1095552378221' , 'id_estado' => '31');
		$mun_array_020202asd['2309'] = array ('municipio' => 'Cantamayec' , 'longitud' => '20.43649819712509' , 'latitud' => '-89.04631833732155' , 'id_estado' => '31');
		$mun_array_020202asd['2310'] = array ('municipio' => 'Celestún' , 'longitud' => '20.898804763969338' , 'latitud' => '-90.2739865998002' , 'id_estado' => '31');
		$mun_array_020202asd['2311'] = array ('municipio' => 'Cenotillo' , 'longitud' => '21.027623540740212' , 'latitud' => '-88.60112557925682' , 'id_estado' => '31');
		$mun_array_020202asd['2312'] = array ('municipio' => 'Conkal' , 'longitud' => '21.07542905426204' , 'latitud' => '-89.51313267385919' , 'id_estado' => '31');
		$mun_array_020202asd['2313'] = array ('municipio' => 'Cuncunul' , 'longitud' => '20.626464641159526' , 'latitud' => '-88.34501830022836' , 'id_estado' => '31');
		$mun_array_020202asd['2314'] = array ('municipio' => 'Cuzamá' , 'longitud' => '20.736304202145647' , 'latitud' => '-89.35524052111062' , 'id_estado' => '31');
		$mun_array_020202asd['2315'] = array ('municipio' => 'Chacsinkín' , 'longitud' => '20.224590854485218' , 'latitud' => '-89.00454376340072' , 'id_estado' => '31');
		$mun_array_020202asd['2316'] = array ('municipio' => 'Chankom' , 'longitud' => '20.541176944245723' , 'latitud' => '-88.54645849074349' , 'id_estado' => '31');
		$mun_array_020202asd['2317'] = array ('municipio' => 'Chapab' , 'longitud' => '20.477657632555225' , 'latitud' => '-89.47803953397163' , 'id_estado' => '31');
		$mun_array_020202asd['2318'] = array ('municipio' => 'Chemax' , 'longitud' => '20.744833682992333' , 'latitud' => '-87.81176958305825' , 'id_estado' => '31');
		$mun_array_020202asd['2319'] = array ('municipio' => 'Chicxulub Pueblo' , 'longitud' => '21.15455178776047' , 'latitud' => '-89.53859060480714' , 'id_estado' => '31');
		$mun_array_020202asd['2320'] = array ('municipio' => 'Chichimilá' , 'longitud' => '20.421603196133226' , 'latitud' => '-88.16948449992456' , 'id_estado' => '31');
		$mun_array_020202asd['2321'] = array ('municipio' => 'Chikindzonot' , 'longitud' => '20.28575117810459' , 'latitud' => '-88.52907644856138' , 'id_estado' => '31');
		$mun_array_020202asd['2322'] = array ('municipio' => 'Chocholá' , 'longitud' => '20.742382796061513' , 'latitud' => '-89.87874050665495' , 'id_estado' => '31');
		$mun_array_020202asd['2323'] = array ('municipio' => 'Chumayel' , 'longitud' => '20.466755270706994' , 'latitud' => '-89.28816058741882' , 'id_estado' => '31');
		$mun_array_020202asd['2324'] = array ('municipio' => 'Dzán' , 'longitud' => '20.386522198971612' , 'latitud' => '-89.45051663088671' , 'id_estado' => '31');
		$mun_array_020202asd['2325'] = array ('municipio' => 'Dzemul' , 'longitud' => '21.250773825837534' , 'latitud' => '-89.35682286860984' , 'id_estado' => '31');
		$mun_array_020202asd['2326'] = array ('municipio' => 'Dzidzantún' , 'longitud' => '21.283053899772742' , 'latitud' => '-89.017150666536' , 'id_estado' => '31');
		$mun_array_020202asd['2327'] = array ('municipio' => 'Dzilam de Bravo' , 'longitud' => '21.423325480372238' , 'latitud' => '-88.69320164583921' , 'id_estado' => '31');
		$mun_array_020202asd['2328'] = array ('municipio' => 'Dzilam González' , 'longitud' => '21.331977358345824' , 'latitud' => '-88.7408582248939' , 'id_estado' => '31');
		$mun_array_020202asd['2329'] = array ('municipio' => 'Dzitás' , 'longitud' => '20.819146910878317' , 'latitud' => '-88.5368509791957' , 'id_estado' => '31');
		$mun_array_020202asd['2330'] = array ('municipio' => 'Dzoncauich' , 'longitud' => '21.09286977118709' , 'latitud' => '-88.8385534053941' , 'id_estado' => '31');
		$mun_array_020202asd['2331'] = array ('municipio' => 'Espita' , 'longitud' => '21.013959146168027' , 'latitud' => '-88.36649363016818' , 'id_estado' => '31');
		$mun_array_020202asd['2332'] = array ('municipio' => 'Halachó' , 'longitud' => '20.53981523507309' , 'latitud' => '-90.18978004974966' , 'id_estado' => '31');
		$mun_array_020202asd['2333'] = array ('municipio' => 'Hocabá' , 'longitud' => '20.810035616447646' , 'latitud' => '-89.2239257089906' , 'id_estado' => '31');
		$mun_array_020202asd['2334'] = array ('municipio' => 'Hoctún' , 'longitud' => '20.892616644472234' , 'latitud' => '-89.18379467280573' , 'id_estado' => '31');
		$mun_array_020202asd['2335'] = array ('municipio' => 'Homún' , 'longitud' => '20.687075777689856' , 'latitud' => '-89.25665811777615' , 'id_estado' => '31');
		$mun_array_020202asd['2336'] = array ('municipio' => 'Huhí' , 'longitud' => '20.693105296884696' , 'latitud' => '-89.12810244926399' , 'id_estado' => '31');
		$mun_array_020202asd['2337'] = array ('municipio' => 'Hunucmá' , 'longitud' => '21.065532431819264' , 'latitud' => '-89.98228249261828' , 'id_estado' => '31');
		$mun_array_020202asd['2338'] = array ('municipio' => 'Ixil' , 'longitud' => '21.232763906380892' , 'latitud' => '-89.46367007778217' , 'id_estado' => '31');
		$mun_array_020202asd['2339'] = array ('municipio' => 'Izamal' , 'longitud' => '20.918544911888773' , 'latitud' => '-89.00836425059292' , 'id_estado' => '31');
		$mun_array_020202asd['2340'] = array ('municipio' => 'Kanasín' , 'longitud' => '20.919819636417458' , 'latitud' => '-89.54282646103067' , 'id_estado' => '31');
		$mun_array_020202asd['2341'] = array ('municipio' => 'Kantunil' , 'longitud' => '20.77071216699344' , 'latitud' => '-88.99751077919484' , 'id_estado' => '31');
		$mun_array_020202asd['2342'] = array ('municipio' => 'Kaua' , 'longitud' => '20.61666203013991' , 'latitud' => '-88.41608435934995' , 'id_estado' => '31');
		$mun_array_020202asd['2343'] = array ('municipio' => 'Kinchil' , 'longitud' => '20.869868027688412' , 'latitud' => '-90.07118423398532' , 'id_estado' => '31');
		$mun_array_020202asd['2344'] = array ('municipio' => 'Kopomá' , 'longitud' => '20.642440049131718' , 'latitud' => '-89.87266243707855' , 'id_estado' => '31');
		$mun_array_020202asd['2345'] = array ('municipio' => 'Mama' , 'longitud' => '20.491525324959465' , 'latitud' => '-89.38178110811408' , 'id_estado' => '31');
		$mun_array_020202asd['2346'] = array ('municipio' => 'Maní' , 'longitud' => '20.385048267139076' , 'latitud' => '-89.36671171041075' , 'id_estado' => '31');
		$mun_array_020202asd['2347'] = array ('municipio' => 'Maxcanú' , 'longitud' => '20.658961021929937' , 'latitud' => '-90.1207985096158' , 'id_estado' => '31');
		$mun_array_020202asd['2348'] = array ('municipio' => 'Mayapán' , 'longitud' => '20.47298524874929' , 'latitud' => '-89.21158855571808' , 'id_estado' => '31');
		$mun_array_020202asd['2349'] = array ('municipio' => 'Mérida' , 'longitud' => '20.983675165925643' , 'latitud' => '-89.63707224448973' , 'id_estado' => '31');
		$mun_array_020202asd['2350'] = array ('municipio' => 'Mocochá' , 'longitud' => '21.119458400951014' , 'latitud' => '-89.45317227350314' , 'id_estado' => '31');
		$mun_array_020202asd['2351'] = array ('municipio' => 'Motul' , 'longitud' => '21.119468581558916' , 'latitud' => '-89.28210583227045' , 'id_estado' => '31');
		$mun_array_020202asd['2352'] = array ('municipio' => 'Muna' , 'longitud' => '20.48778514560519' , 'latitud' => '-89.6970684425367' , 'id_estado' => '31');
		$mun_array_020202asd['2353'] = array ('municipio' => 'Muxupip' , 'longitud' => '21.044462541222092' , 'latitud' => '-89.32226570028503' , 'id_estado' => '31');
		$mun_array_020202asd['2354'] = array ('municipio' => 'Opichén' , 'longitud' => '20.511885887340636' , 'latitud' => '-89.85228920337165' , 'id_estado' => '31');
		$mun_array_020202asd['2355'] = array ('municipio' => 'Oxkutzcab' , 'longitud' => '20.109182198978505' , 'latitud' => '-89.54487211419936' , 'id_estado' => '31');
		$mun_array_020202asd['2356'] = array ('municipio' => 'Panabá' , 'longitud' => '21.354640750219794' , 'latitud' => '-88.3139809036322' , 'id_estado' => '31');
		$mun_array_020202asd['2357'] = array ('municipio' => 'Peto' , 'longitud' => '20.077550816840176' , 'latitud' => '-88.81881296693267' , 'id_estado' => '31');
		$mun_array_020202asd['2358'] = array ('municipio' => 'Progreso' , 'longitud' => '21.228194121311823' , 'latitud' => '-89.6896135917762' , 'id_estado' => '31');
		$mun_array_020202asd['2359'] = array ('municipio' => 'Quintana Roo' , 'longitud' => '20.846798630531843' , 'latitud' => '-88.62500266551139' , 'id_estado' => '31');
		$mun_array_020202asd['2360'] = array ('municipio' => 'Río Lagartos' , 'longitud' => '21.5439823527898' , 'latitud' => '-88.10176460613106' , 'id_estado' => '31');
		$mun_array_020202asd['2361'] = array ('municipio' => 'Sacalum' , 'longitud' => '20.518966393285105' , 'latitud' => '-89.58352945498393' , 'id_estado' => '31');
		$mun_array_020202asd['2362'] = array ('municipio' => 'Samahil' , 'longitud' => '20.828266658231023' , 'latitud' => '-89.9689198461189' , 'id_estado' => '31');
		$mun_array_020202asd['2363'] = array ('municipio' => 'Sanahcat' , 'longitud' => '20.778620667766454' , 'latitud' => '-89.14489033660787' , 'id_estado' => '31');
		$mun_array_020202asd['2364'] = array ('municipio' => 'San Felipe' , 'longitud' => '21.496488113059254' , 'latitud' => '-88.32316382417538' , 'id_estado' => '31');
		$mun_array_020202asd['2365'] = array ('municipio' => 'Santa Elena' , 'longitud' => '20.29454810252674' , 'latitud' => '-89.73358470736916' , 'id_estado' => '31');
		$mun_array_020202asd['2366'] = array ('municipio' => 'Seyé' , 'longitud' => '20.855998045744393' , 'latitud' => '-89.34530694044521' , 'id_estado' => '31');
		$mun_array_020202asd['2367'] = array ('municipio' => 'Sinanché' , 'longitud' => '21.26252270258178' , 'latitud' => '-89.18408406328803' , 'id_estado' => '31');
		$mun_array_020202asd['2368'] = array ('municipio' => 'Sotuta' , 'longitud' => '20.606474509903848' , 'latitud' => '-88.99122315128858' , 'id_estado' => '31');
		$mun_array_020202asd['2369'] = array ('municipio' => 'Sucilá' , 'longitud' => '21.200174449038332' , 'latitud' => '-88.37415220044697' , 'id_estado' => '31');
		$mun_array_020202asd['2370'] = array ('municipio' => 'Sudzal' , 'longitud' => '20.80470407425432' , 'latitud' => '-88.880180473065' , 'id_estado' => '31');
		$mun_array_020202asd['2371'] = array ('municipio' => 'Suma' , 'longitud' => '21.089250003133174' , 'latitud' => '-89.13912819359207' , 'id_estado' => '31');
		$mun_array_020202asd['2372'] = array ('municipio' => 'Tahdziú' , 'longitud' => '20.2500135177885' , 'latitud' => '-88.90231470190794' , 'id_estado' => '31');
		$mun_array_020202asd['2373'] = array ('municipio' => 'Tahmek' , 'longitud' => '20.911357504591027' , 'latitud' => '-89.25399136295842' , 'id_estado' => '31');
		$mun_array_020202asd['2374'] = array ('municipio' => 'Teabo' , 'longitud' => '20.378872515825528' , 'latitud' => '-89.23169800344729' , 'id_estado' => '31');
		$mun_array_020202asd['2375'] = array ('municipio' => 'Tecoh' , 'longitud' => '20.672033961312994' , 'latitud' => '-89.4814218050031' , 'id_estado' => '31');
		$mun_array_020202asd['2376'] = array ('municipio' => 'Tekal de Venegas' , 'longitud' => '21.016826554820604' , 'latitud' => '-88.85733168149815' , 'id_estado' => '31');
		$mun_array_020202asd['2377'] = array ('municipio' => 'Tekantó' , 'longitud' => '21.004736752170654' , 'latitud' => '-89.10822773638644' , 'id_estado' => '31');
		$mun_array_020202asd['2378'] = array ('municipio' => 'Tekax' , 'longitud' => '19.921371651329544' , 'latitud' => '-89.29537078068807' , 'id_estado' => '31');
		$mun_array_020202asd['2379'] = array ('municipio' => 'Tekit' , 'longitud' => '20.57503247904895' , 'latitud' => '-89.28254479182972' , 'id_estado' => '31');
		$mun_array_020202asd['2380'] = array ('municipio' => 'Tekom' , 'longitud' => '20.513445640333252' , 'latitud' => '-88.38700957945413' , 'id_estado' => '31');
		$mun_array_020202asd['2381'] = array ('municipio' => 'Telchac Pueblo' , 'longitud' => '21.221787114440477' , 'latitud' => '-89.25372982873796' , 'id_estado' => '31');
		$mun_array_020202asd['2382'] = array ('municipio' => 'Telchac Puerto' , 'longitud' => '21.303954638322036' , 'latitud' => '-89.29347012129543' , 'id_estado' => '31');
		$mun_array_020202asd['2383'] = array ('municipio' => 'Temax' , 'longitud' => '21.171922354478458' , 'latitud' => '-88.92026598769557' , 'id_estado' => '31');
		$mun_array_020202asd['2384'] = array ('municipio' => 'Temozón' , 'longitud' => '20.878310190607653' , 'latitud' => '-88.10390242254142' , 'id_estado' => '31');
		$mun_array_020202asd['2385'] = array ('municipio' => 'Tepakán' , 'longitud' => '21.04121219278584' , 'latitud' => '-89.01383323778657' , 'id_estado' => '31');
		$mun_array_020202asd['2386'] = array ('municipio' => 'Tetiz' , 'longitud' => '20.96526234874531' , 'latitud' => '-90.04902706468832' , 'id_estado' => '31');
		$mun_array_020202asd['2387'] = array ('municipio' => 'Teya' , 'longitud' => '21.06780429932593' , 'latitud' => '-89.07732821313114' , 'id_estado' => '31');
		$mun_array_020202asd['2388'] = array ('municipio' => 'Ticul' , 'longitud' => '20.338902100489175' , 'latitud' => '-89.54483915553767' , 'id_estado' => '31');
		$mun_array_020202asd['2389'] = array ('municipio' => 'Timucuy' , 'longitud' => '20.82654215070659' , 'latitud' => '-89.53321786223056' , 'id_estado' => '31');
		$mun_array_020202asd['2390'] = array ('municipio' => 'Tinum' , 'longitud' => '20.747421155659715' , 'latitud' => '-88.47762174306182' , 'id_estado' => '31');
		$mun_array_020202asd['2391'] = array ('municipio' => 'Tixcacalcupul' , 'longitud' => '20.3745052632104' , 'latitud' => '-88.32680566963336' , 'id_estado' => '31');
		$mun_array_020202asd['2392'] = array ('municipio' => 'Tixkokob' , 'longitud' => '20.981180836468475' , 'latitud' => '-89.3661595677746' , 'id_estado' => '31');
		$mun_array_020202asd['2393'] = array ('municipio' => 'Tixmehuac' , 'longitud' => '20.250287554306848' , 'latitud' => '-89.09529258829325' , 'id_estado' => '31');
		$mun_array_020202asd['2394'] = array ('municipio' => 'Tixpéhual' , 'longitud' => '20.962889134575157' , 'latitud' => '-89.45870655682023' , 'id_estado' => '31');
		$mun_array_020202asd['2395'] = array ('municipio' => 'Tizimín' , 'longitud' => '21.250701519180694' , 'latitud' => '-87.85209223983351' , 'id_estado' => '31');
		$mun_array_020202asd['2396'] = array ('municipio' => 'Tunkás' , 'longitud' => '20.89124733950059' , 'latitud' => '-88.75374896368004' , 'id_estado' => '31');
		$mun_array_020202asd['2397'] = array ('municipio' => 'Tzucacab' , 'longitud' => '19.947054495835005' , 'latitud' => '-89.05378665974706' , 'id_estado' => '31');
		$mun_array_020202asd['2398'] = array ('municipio' => 'Uayma' , 'longitud' => '20.760972645748012' , 'latitud' => '-88.34256230138627' , 'id_estado' => '31');
		$mun_array_020202asd['2399'] = array ('municipio' => 'Ucú' , 'longitud' => '21.089604779600986' , 'latitud' => '-89.79950612807905' , 'id_estado' => '31');
		$mun_array_020202asd['2400'] = array ('municipio' => 'Umán' , 'longitud' => '20.83047054561591' , 'latitud' => '-89.76428367937159' , 'id_estado' => '31');
		$mun_array_020202asd['2401'] = array ('municipio' => 'Valladolid' , 'longitud' => '20.61820371074003' , 'latitud' => '-88.10333477481727' , 'id_estado' => '31');
		$mun_array_020202asd['2402'] = array ('municipio' => 'Xocchel' , 'longitud' => '20.83286432685267' , 'latitud' => '-89.1468066142481' , 'id_estado' => '31');
		$mun_array_020202asd['2403'] = array ('municipio' => 'Yaxcabá' , 'longitud' => '20.49341335963353' , 'latitud' => '-88.75560306482376' , 'id_estado' => '31');
		$mun_array_020202asd['2404'] = array ('municipio' => 'Yaxkukul' , 'longitud' => '21.060574027741666' , 'latitud' => '-89.42372323769732' , 'id_estado' => '31');
		$mun_array_020202asd['2405'] = array ('municipio' => 'Yobaín' , 'longitud' => '21.27269306997122' , 'latitud' => '-89.10829913986545' , 'id_estado' => '31');


		$valor_seguridad_key_nasdajsd = '2349';
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

		$id_distrito_local = 12;
		$latitud="19.68915827418944";
		$longitud="-88.1089707365155";
		$estado_nombre = "Yuc.";
		$extranjeros_mode=false;

	}elseif($tipo_uso_plataforma=='distrito_federal'){

		$id_distrito_federal = 1;
		$latitud="20.79594881668799";
		$longitud="-87.37538269760637";
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