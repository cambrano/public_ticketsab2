<?php
	date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
	setlocale(LC_ALL,"es_ES");
	$length=6; 
	$mk_id=time();
	$gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
	$gen_id=$gen_id.$mk_id; 

	$length=6; 
	$mk_id=time();
	$gen_id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
	$gen_id=$gen_id.$mk_id;

	$length=5; 
	$mk_id=time();
	$tran_id1 = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	$tran_id2 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
	$tran_id3 = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	$tran_cod=$tran_id1.$tran_id2.$tran_id3.$mk_id;

	$length=6; 
	$mk_id=time();
	$gen_id = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
	$gen_id1=$gen_id.$mk_id; 

	$length=4; 
	$mk_id=time()*2*36*12/3;
	$gen_id = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz"), 0, $length); 
	$gen_id2=$gen_id.$mk_id; 

	$length=6; 
	$mk_id=time();
	$gen_id = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
	$gen_id1M=$gen_id.$mk_id; 


	$length=6; 
	$mk_id=time()*2*36*12/3;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789"), 0, $length); 

	$length=6; 
	$mk_id=time()*2*36*12/3;
	$gen_id3M = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 

	$length = 4;
	$gen_id4 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, $length).$mk_id; 
	$length = 3;
	$gen_id5 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, $length); 
	$length = 5;
	$gen_idSinNumero = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"), 0, $length); 

	$cod6=$gen_id3; 
	$cod6M=$gen_id3M; 
	$cod16=$gen_id1;
	$cod16M=$gen_id1M;
	$cod32=$gen_id1.$gen_id3;

	//$mk_id=time()*2*36*12*2;
	$genid_array['cod6']=$cod6;
	$genid_array['cod6M']=$cod6M;
	$genid_array['cod16']=$cod16;
	$genid_array['cod16M']=$cod16M;
	$genid_array['cod32']=$cod32;

