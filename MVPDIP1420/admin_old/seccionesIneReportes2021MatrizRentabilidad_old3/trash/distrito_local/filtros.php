<?php
	include __DIR__."/../../functions/security.php";
	@session_start(); 
?>
	<script type="text/javascript">
		 
		function searchTable(value){
			let alphabet = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ','DA','DB','DC','DD','DE','DF','DG','DH','DI','DJ','DK','DL','DM','DN','DO','DP','DQ','DR','DS','DT','DU','DV','DW','DX','DY','DZ','EA','EB','EC','ED','EE','EF','EG','EH','EI','EJ','EK','EL','EM','EN','EO','EP','EQ','ER','ES','ET','EU','EV','EW','EX','EY','EZ','FA','FB','FC','FD','FE','FF','FG','FH','FI','FJ','FK','FL','FM','FN','FO','FP','FQ','FR','FS','FT','FU','FV','FW','FX','FY','FZ','GA','GB','GC','GD','GE','GF','GG','GH','GI','GJ','GK','GL','GM','GN','GO','GP','GQ','GR','GS','GT','GU','GV','GW','GX','GY','GZ','HA','HB','HC','HD','HE','HF','HG','HH','HI','HJ','HK','HL','HM','HN','HO','HP','HQ','HR','HS','HT','HU','HV','HW','HX','HY','HZ','IA','IB','IC','ID','IE','IF','IG','IH','II','IJ','IK','IL','IM','IN','IO','IP','IQ','IR','IS','IT','IU','IV','IW','IX','IY','IZ','JA','JB','JC','JD','JE','JF','JG','JH','JI','JJ','JK','JL','JM','JN','JO','JP','JQ','JR','JS','JT','JU','JV','JW','JX','JY','JZ','KA','KB','KC','KD','KE','KF','KG','KH','KI','KJ','KK','KL','KM','KN','KO','KP','KQ','KR','KS','KT','KU','KV','KW','KX','KY','KZ','LA','LB','LC','LD','LE','LF','LG','LH','LI','LJ','LK','LL','LM','LN','LO','LP','LQ','LR','LS','LT','LU','LV','LW','LX','LY','LZ','MA','MB','MC','MD','ME','MF','MG','MH','MI','MJ','MK','ML','MM','MN','MO','MP','MQ','MR','MS','MT','MU','MV','MW','MX','MY','MZ','NA','NB','NC','ND','NE','NF','NG','NH','NI','NJ','NK','NL','NM','NN','NO','NP','NQ','NR','NS','NT','NU','NV','NW','NX','NY','NZ','OA','OB','OC','OD','OE','OF','OG','OH','OI','OJ','OK','OL','OM','ON','OO','OP','OQ','OR','OS','OT','OU','OV','OW','OX','OY','OZ','PA','PB','PC','PD','PE','PF','PG','PH','PI','PJ','PK','PL','PM','PN','PO','PP','PQ','PR','PS','PT','PU','PV','PW','PX','PY','PZ','QA','QB','QC','QD','QE','QF','QG','QH','QI','QJ','QK','QL','QM','QN','QO','QP','QQ','QR','QS','QT','QU','QV','QW','QX','QY','QZ','RA','RB','RC','RD','RE','RF','RG','RH','RI','RJ','RK','RL','RM','RN','RO','RP','RQ','RR','RS','RT','RU','RV','RW','RX','RY','RZ','SA','SB','SC','SD','SE','SF','SG','SH','SI','SJ','SK','SL','SM','SN','SO','SP','SQ','SR','SS','ST','SU','SV','SW','SX','SY','SZ','TA','TB','TC','TD','TE','TF','TG','TH','TI','TJ','TK','TL','TM','TN','TO','TP','TQ','TR','TS','TT','TU','TV','TW','TX','TY','TZ','UA','UB','UC','UD','UE','UF','UG','UH','UI','UJ','UK','UL','UM','UN','UO','UP','UQ','UR','US','UT','UU','UV','UW','UX','UY','UZ','VA','VB','VC','VD','VE','VF','VG','VH','VI','VJ','VK','VL','VM','VN','VO','VP','VQ','VR','VS','VT','VU','VV','VW','VX','VY','VZ','WA','WB','WC','WD','WE','WF','WG','WH','WI','WJ','WK','WL','WM','WN','WO','WP','WQ','WR','WS','WT','WU','WV','WW','WX','WY','WZ','XA','XB','XC','XD','XE','XF','XG','XH','XI','XJ','XK','XL','XM','XN','XO','XP','XQ','XR','XS','XT','XU','XV','XW','XX','XY','XZ','YA','YB','YC','YD','YE','YF','YG','YH','YI','YJ','YK','YL','YM','YN','YO','YP','YQ','YR','YS','YT','YU','YV','YW','YX','YY','YZ','ZA','ZB','ZC','ZD','ZE','ZF','ZG','ZH','ZI','ZJ','ZK','ZL','ZM','ZN','ZO','ZP','ZQ','ZR','ZS','ZT','ZU','ZV','ZW','ZX','ZY','ZZ','AAA','AAB','AAC','AAD','AAE','AAF','AAG','AAH','AAI','AAJ','AAK','AAL','AAM','AAN','AAO','AAP','AAQ','AAR','AAS','AAT','AAU','AAV','AAW','AAX','AAY','AAZ','ABA','ABB','ABC','ABD','ABE','ABF','ABG','ABH','ABI','ABJ','ABK','ABL','ABM','ABN','ABO','ABP','ABQ','ABR','ABS','ABT','ABU','ABV','ABW','ABX','ABY','ABZ','ACA','ACB','ACC','ACD','ACE','ACF','ACG','ACH','ACI','ACJ','ACK','ACL','ACM','ACN','ACO','ACP','ACQ','ACR','ACS','ACT','ACU','ACV','ACW','ACX','ACY','ACZ','ADA','ADB','ADC','ADD','ADE','ADF','ADG','ADH','ADI','ADJ','ADK','ADL','ADM','ADN','ADO','ADP','ADQ','ADR','ADS','ADT','ADU','ADV','ADW','ADX','ADY','ADZ','AEA','AEB','AEC','AED','AEE','AEF','AEG','AEH','AEI','AEJ','AEK','AEL','AEM','AEN','AEO','AEP','AEQ','AER','AES','AET','AEU','AEV','AEW','AEX','AEY','AEZ','AFA','AFB','AFC','AFD','AFE','AFF','AFG','AFH','AFI','AFJ','AFK','AFL','AFM','AFN','AFO','AFP','AFQ','AFR','AFS','AFT','AFU','AFV','AFW','AFX','AFY','AFZ','AGA','AGB','AGC','AGD','AGE','AGF','AGG','AGH','AGI','AGJ','AGK','AGL','AGM','AGN','AGO','AGP','AGQ','AGR','AGS','AGT','AGU','AGV','AGW','AGX','AGY','AGZ','AHA','AHB','AHC','AHD','AHE','AHF','AHG','AHH','AHI','AHJ','AHK','AHL','AHM','AHN','AHO','AHP','AHQ','AHR','AHS','AHT','AHU','AHV','AHW','AHX','AHY','AHZ','AIA','AIB','AIC','AID','AIE','AIF','AIG','AIH','AII','AIJ','AIK','AIL','AIM','AIN','AIO','AIP','AIQ','AIR','AIS','AIT','AIU','AIV','AIW','AIX','AIY','AIZ','AJA','AJB','AJC','AJD','AJE','AJF','AJG','AJH','AJI','AJJ','AJK','AJL','AJM','AJN','AJO','AJP','AJQ','AJR','AJS','AJT','AJU','AJV','AJW','AJX','AJY','AJZ','AKA','AKB','AKC','AKD','AKE','AKF','AKG','AKH','AKI','AKJ','AKK','AKL','AKM','AKN','AKO','AKP','AKQ','AKR','AKS','AKT','AKU','AKV','AKW','AKX','AKY','AKZ','ALA','ALB','ALC','ALD','ALE','ALF','ALG','ALH','ALI','ALJ','ALK','ALL','ALM','ALN','ALO','ALP','ALQ','ALR','ALS','ALT','ALU','ALV','ALW','ALX','ALY','ALZ','AMA','AMB','AMC','AMD','AME','AMF','AMG','AMH','AMI','AMJ','AMK','AML','AMM','AMN','AMO','AMP','AMQ','AMR','AMS','AMT','AMU','AMV','AMW','AMX','AMY','AMZ','ANA','ANB','ANC','AND','ANE','ANF','ANG','ANH','ANI','ANJ','ANK','ANL','ANM','ANN','ANO','ANP','ANQ','ANR','ANS','ANT','ANU','ANV','ANW','ANX','ANY','ANZ','AOA','AOB','AOC','AOD','AOE','AOF','AOG','AOH','AOI','AOJ','AOK','AOL','AOM','AON','AOO','AOP','AOQ','AOR','AOS','AOT','AOU','AOV','AOW','AOX','AOY','AOZ','APA','APB','APC','APD','APE','APF','APG','APH','API','APJ','APK','APL','APM','APN','APO','APP','APQ','APR','APS','APT','APU','APV','APW','APX','APY','APZ','AQA','AQB','AQC','AQD','AQE','AQF','AQG','AQH','AQI','AQJ','AQK','AQL','AQM','AQN','AQO','AQP','AQQ','AQR','AQS','AQT','AQU','AQV','AQW','AQX','AQY','AQZ','ARA','ARB','ARC','ARD','ARE','ARF','ARG','ARH','ARI','ARJ','ARK','ARL','ARM','ARN','ARO','ARP','ARQ','ARR','ARS','ART','ARU','ARV','ARW','ARX','ARY','ARZ','ASA','ASB','ASC','ASD','ASE','ASF','ASG','ASH','ASI','ASJ','ASK','ASL','ASM','ASN','ASO','ASP','ASQ','ASR','ASS','AST','ASU','ASV','ASW','ASX','ASY','ASZ','ATA','ATB','ATC','ATD','ATE','ATF','ATG','ATH','ATI','ATJ','ATK','ATL','ATM','ATN','ATO','ATP','ATQ','ATR','ATS','ATT','ATU','ATV','ATW','ATX','ATY','ATZ','AUA','AUB','AUC','AUD','AUE','AUF','AUG','AUH','AUI','AUJ','AUK','AUL','AUM','AUN','AUO','AUP','AUQ','AUR','AUS','AUT','AUU','AUV','AUW','AUX','AUY','AUZ','AVA','AVB','AVC','AVD','AVE','AVF','AVG','AVH','AVI','AVJ','AVK','AVL','AVM','AVN','AVO','AVP','AVQ','AVR','AVS','AVT','AVU','AVV','AVW','AVX','AVY','AVZ','AWA','AWB','AWC','AWD','AWE','AWF','AWG','AWH','AWI','AWJ','AWK','AWL','AWM','AWN','AWO','AWP','AWQ','AWR','AWS','AWT','AWU','AWV','AWW','AWX','AWY','AWZ','AXA','AXB','AXC','AXD','AXE','AXF','AXG','AXH','AXI','AXJ','AXK','AXL','AXM','AXN','AXO','AXP','AXQ','AXR','AXS','AXT','AXU','AXV','AXW','AXX','AXY','AXZ','AYA','AYB','AYC','AYD','AYE','AYF','AYG','AYH','AYI','AYJ','AYK','AYL','AYM','AYN','AYO','AYP','AYQ','AYR','AYS','AYT','AYU','AYV','AYW','AYX','AYY','AYZ','AZA','AZB','AZC','AZD','AZE','AZF','AZG','AZH','AZI','AZJ','AZK','AZL','AZM','AZN','AZO','AZP','AZQ','AZR','AZS','AZT','AZU','AZV','AZW','AZX','AZY','AZZ','BAA','BAB','BAC','BAD','BAE','BAF','BAG','BAH','BAI','BAJ','BAK','BAL','BAM','BAN','BAO','BAP','BAQ','BAR','BAS','BAT','BAU','BAV','BAW','BAX','BAY','BAZ','BBA','BBB','BBC','BBD','BBE','BBF','BBG','BBH','BBI','BBJ','BBK','BBL','BBM','BBN','BBO','BBP','BBQ','BBR','BBS','BBT','BBU','BBV','BBW','BBX','BBY','BBZ','BCA','BCB','BCC','BCD','BCE','BCF','BCG','BCH','BCI','BCJ','BCK','BCL','BCM','BCN','BCO','BCP','BCQ','BCR','BCS','BCT','BCU','BCV','BCW','BCX','BCY','BCZ','BDA','BDB','BDC','BDD','BDE','BDF','BDG','BDH','BDI','BDJ','BDK','BDL','BDM','BDN','BDO','BDP','BDQ','BDR','BDS','BDT','BDU','BDV','BDW','BDX','BDY','BDZ','BEA','BEB','BEC','BED','BEE','BEF','BEG','BEH','BEI','BEJ','BEK','BEL','BEM','BEN','BEO','BEP','BEQ','BER','BES','BET','BEU','BEV','BEW','BEX','BEY','BEZ','BFA','BFB','BFC','BFD','BFE','BFF','BFG','BFH','BFI','BFJ','BFK','BFL','BFM','BFN','BFO','BFP','BFQ','BFR','BFS','BFT','BFU','BFV','BFW','BFX','BFY','BFZ','BGA','BGB','BGC','BGD','BGE','BGF','BGG','BGH','BGI','BGJ','BGK','BGL','BGM','BGN','BGO','BGP','BGQ','BGR','BGS','BGT','BGU','BGV','BGW','BGX','BGY','BGZ','BHA','BHB','BHC','BHD','BHE','BHF','BHG','BHH','BHI','BHJ','BHK','BHL','BHM','BHN','BHO','BHP','BHQ','BHR','BHS','BHT','BHU','BHV','BHW','BHX','BHY','BHZ','BIA','BIB','BIC','BID','BIE','BIF','BIG','BIH','BII','BIJ','BIK','BIL','BIM','BIN','BIO','BIP','BIQ','BIR','BIS','BIT','BIU','BIV','BIW','BIX','BIY','BIZ','BJA','BJB','BJC','BJD','BJE','BJF','BJG','BJH','BJI','BJJ','BJK','BJL','BJM','BJN','BJO','BJP','BJQ','BJR','BJS','BJT','BJU','BJV','BJW','BJX','BJY','BJZ','BKA','BKB','BKC','BKD','BKE','BKF','BKG','BKH','BKI','BKJ','BKK','BKL','BKM','BKN','BKO','BKP','BKQ','BKR','BKS','BKT','BKU','BKV','BKW','BKX','BKY','BKZ','BLA','BLB','BLC','BLD','BLE','BLF','BLG','BLH','BLI','BLJ','BLK','BLL','BLM','BLN','BLO','BLP','BLQ','BLR','BLS','BLT','BLU','BLV','BLW','BLX','BLY','BLZ','BMA','BMB','BMC','BMD','BME','BMF','BMG','BMH','BMI','BMJ','BMK','BML','BMM','BMN','BMO','BMP','BMQ','BMR','BMS','BMT','BMU','BMV','BMW','BMX','BMY','BMZ','BNA','BNB','BNC','BND','BNE','BNF','BNG','BNH','BNI','BNJ','BNK','BNL','BNM','BNN','BNO','BNP','BNQ','BNR','BNS','BNT','BNU','BNV','BNW','BNX','BNY','BNZ','BOA','BOB','BOC','BOD','BOE','BOF','BOG','BOH','BOI','BOJ','BOK','BOL','BOM','BON','BOO','BOP','BOQ','BOR','BOS','BOT','BOU','BOV','BOW','BOX','BOY','BOZ','BPA','BPB','BPC','BPD','BPE','BPF','BPG','BPH','BPI','BPJ','BPK','BPL','BPM','BPN','BPO','BPP','BPQ','BPR','BPS','BPT','BPU','BPV','BPW','BPX','BPY','BPZ','BQA','BQB','BQC','BQD','BQE','BQF','BQG','BQH','BQI','BQJ','BQK','BQL','BQM','BQN','BQO','BQP','BQQ','BQR','BQS','BQT','BQU','BQV','BQW','BQX','BQY','BQZ','BRA','BRB'];
			var id_seccion_ine_input = document.getElementById("id_seccion_ine");
			var id_seccion_ine_array = [];
			var id_seccion_ine_array_table = [];
			for (var i = 0; i < id_seccion_ine_input.length; i++) {
				if (id_seccion_ine_input.options[i].selected){
					id_seccion_ine_array.push(id_seccion_ine_input.options[i].value);
					id_seccion_ine_array_table.push("(^" + alphabet[id_seccion_ine_input.options[i].value] + "$)");
				}
			}
			id_seccion_ine = id_seccion_ine_array.join(","); 

			var partido_ganador_id_input = document.getElementById("partido_ganador_id");
			var partido_ganador_id_array = [];
			var partido_ganador_id_array_table = [];
			for (var i = 0; i < partido_ganador_id_input.length; i++) {
				if (partido_ganador_id_input.options[i].selected){
					partido_ganador_id_array.push(partido_ganador_id_input.options[i].value);
					partido_ganador_id_array_table.push("(^" + alphabet[partido_ganador_id_input.options[i].value] + "$)");
				}
			}
			partido_ganador_id = partido_ganador_id_array.join(",");
			console.log(partido_ganador_id_array_table);

			var tipo_seccion_input = document.getElementById("tipo_seccion");
			var tipo_seccion_array = [];
			var tipo_seccion_array_table = [];
			for (var i = 0; i < tipo_seccion_input.length; i++) {
				if (tipo_seccion_input.options[i].selected){
					tipo_seccion_array.push(tipo_seccion_input.options[i].value);
					tipo_seccion_array_table.push("(^" + tipo_seccion_input.options[i].value + "$)");
				}
			}
			tipo_seccion = tipo_seccion_array.join(",");

			if(tipo_seccion==""){
				$('#secciones_reportes-tabla').DataTable().column(11).search(tipo_seccion).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+tipo_seccion+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(11).search(tipo_seccion_array_table.join('|'), true, false).draw();
			}

			if(tipo_seccion != ''){
				if(tipo_seccion=='Urbana'){
					tipo_seccion = 1;
				}else{
					tipo_seccion = 0;
				}
			}

			var id_municipio_input = document.getElementById("id_municipio");
			var id_municipio_array = [];
			var id_municipio_array_table = [];
			for (var i = 0; i < id_municipio_input.length; i++) {
				if (id_municipio_input.options[i].selected){
					id_municipio_array.push(id_municipio_input.options[i].value);
					id_municipio_array_table.push("(^" + alphabet[id_municipio_input.options[i].value] + "$)");
				}
			}
			id_municipio = id_municipio_array.join(","); 

			var semaforo_input = document.getElementById("semaforo");
			var semaforo_array = [];
			var semaforo_array_table = [];
			for (var i = 0; i < semaforo_input.length; i++) {
				if (semaforo_input.options[i].selected){
					semaforo_array.push(semaforo_input.options[i].value);
					semaforo_array_table.push("(^" + semaforo_input.options[i].value + "$)");
				}
			}
			semaforo = semaforo_array.join(",");

			if(semaforo==""){
				$('#secciones_reportes-tabla').DataTable().column(8).search(semaforo).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+semaforo+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(8).search(semaforo_array_table.join('|'), true, false).draw();
			}



			var id_distrito_local = "<?= $id_distrito_local ?>";
			var searchTable = [];
			var data = {
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'partido_ganador_id' : partido_ganador_id,
					'id_distrito_local' : id_distrito_local,
					'id_municipio' : id_municipio,
					'semaforo' : semaforo,
				}
			searchTable.push(data);
			var mapa = [];
			var data = {   
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'partido_ganador_id' : partido_ganador_id,
					'id_distrito_local' : id_distrito_local,
					'id_municipio' : id_municipio,
					'semaforo' : semaforo,
				}
			mapa.push(data);


			if(id_seccion_ine==""){
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_seccion_ine+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(0).search(id_seccion_ine_array_table.join('|'), true, false).draw();
			}

			if(partido_ganador_id==""){
				$('#secciones_reportes-tabla').DataTable().column(1).search(partido_ganador_id).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(1).search("(^"+partido_ganador_id+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(1).search(partido_ganador_id_array_table.join('|'), true, false).draw();
			}

			if(id_municipio==""){
				$('#secciones_reportes-tabla').DataTable().column(2).search(id_municipio).draw();
			}else{
				//$('#secciones_reportes-tabla').DataTable().column(0).search("(^"+id_municipio+"$)",true,false).draw();
				$('#secciones_reportes-tabla').DataTable().column(2).search(id_municipio_array_table.join('|'), true, false).draw();
			}


			/*
			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2021MatrizRentabilidad/table.php",
				data: {searchTable: searchTable,mapa:mapa},
				async: true,
				success: function(data) {
					$("#dataTable").html(data);
				}
			});
			*/

			if(value != 'pagina'){
				document.getElementById("pagina_valor").value = 1
				$.ajax({
					type: "POST",
					url: "seccionesIneReportes2021MatrizRentabilidad/distrito_local/mapa.php",
					data: {searchTable: searchTable,mapa:mapa},
					async: true,
					success: function(data) {
						$("#mapaLoad").html(data);
					}
				});
			}
			var pagina_valor = document.getElementById("pagina_valor").value;
			var searchGrafica = [];
			var data = {
					'pagina' : pagina_valor,
					'tipo_seccion' : tipo_seccion,
					'id_seccion_ine' : id_seccion_ine,
					'partido_ganador_id' : partido_ganador_id,
					'id_municipio' : id_municipio,
					'semaforo' : semaforo,
				}
			searchGrafica.push(data);

			$.ajax({
				type: "POST",
				url: "seccionesIneReportes2021MatrizRentabilidad/distrito_local/graficas.php",
				data: {searchGrafica: searchGrafica/*,dataSecciones:dataSecciones*/},
				async: true,
				success: function(data) {
					$("#graficasLoad").html(data);
				}
			});
		}
	</script>
	<style>
		.select2-container--default.select2-container--focus .select2-selection--multiple {
			box-shadow: 0 0 10px #c5c5f2;
			-webkit-box-shadow: 0 0 10px #c5c5f2;
			-moz-box-shadow: 0 0 10px #c5c5f2;
			border: 1px solid #DDDDDD;
			width: 100%;
		}
		input[type=text] {
			height: 38px;
		}
		.select2-container--default .select2-selection--single {
			background-color: #fff;
			border: 1px solid #aaa;
			border-radius: 4px;
			height: 33px;
		}
		.select2-container--default .select2-selection--single .select2-selection__rendered {
			color: #444;
			line-height: 32px;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow {
			height: 32px;
			position: absolute;
			top: 1px;
			right: 1px;
			width: 20px;
		}
		.bs-actionsbox .btn-group button {
			width: 48%;
			font-size: 12px;
		}
	</style>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Municipio<br></label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_municipio" onchange="searchTable();">
			<?php
			echo municipios('',$id_estado);
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Secciones<br></label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="id_seccion_ine" onchange="searchTable();">
			<?php
			echo secciones_ine('','',$id_distrito_local,'','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Partidos Mayoría</label><br>
		<select class="selectpicker" multiple data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="partido_ganador_id" onchange="searchTable();">
			<option value="0">No Data</option>
			<?php
			echo partidos_2021('','1','sin_coalicion','SIN');
			?>
		</select><br>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Semáforo</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="semaforo" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="verde">Verde</option>
			<option value="amarillo">Amarillo</option>
			<option value="rojo">Rojo</option>
			<option value="gris">Gris</option>
		</select>
	</div>
	<div class="sucForm">
		<label class="labelForm" id="labeltemaname">Tipo Sección</label><br>
		<select class="selectpicker" data-live-search="true" data-size="5" data-actions-box="true" title="Seleccione" id="tipo_seccion" onchange="searchTable();">
			<option value="">Seleccione</option>
			<option value="Urbana">Urbana</option>
			<option value="Rural">Rural</option>
		</select>
	</div>

	<script type="text/javascript">
		$(".myselect").select2();
		$('select').selectpicker({
			deselectAllText: '<span class="glyphicon glyphicon-remove-sign"></span>', 
			selectAllText: '<span class="glyphicon glyphicon-ok-sign"></span>',
			liveSearchNormalize : true,
			multipleSeparator: ' | ',
			noneResultsText: 'No Encontrado {0}',
		});
	</script>