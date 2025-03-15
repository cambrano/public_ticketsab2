<?php
///// checar security en funtions
function encrypt_ab_check($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja '.$codigo_plataforma;

   $string .= '_'.rand();
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}



function encrypt_ab_checkSin($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja ' . $codigo_plataforma;
   $result = '';
   for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key)), 1);
      $char = chr(ord($char) + ord($keychar));
      $result .= $char;
   }
   return base64_encode($result);
}

function decrypt_ab_checkSin($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja ' . $codigo_plataforma;
   $result = '';
   $string = base64_decode($string);
   for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key)), 1);
      $char = chr(ord($char) - ord($keychar));
      $result .= $char;
   }
   return $result;
}


function encrypt_ab_checkSnRnd($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja ' . $codigo_plataforma;
   $result = '';
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key)), 1); // Modificamos esta línea
      $char = chr(ord($char)+ord($keychar));
      $result.=$char;
   }
   return base64_encode($result);
}


function decrypt_ab_check($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja '.$codigo_plataforma;
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

function decrypt_ab_checkFinal($string) {
   include dirname(__DIR__)."/keySistema/key.php";
   $key = 'puto el que lo lea y si lo lograste callme please jajaja '.$codigo_plataforma;
   $result = '';
   $string = base64_decode($string);
   for($i=0; $i<strlen($string); $i++) {
      $char = substr($string, $i, 1);
      $keychar = substr($key, ($i % strlen($key))-1, 1);
      $char = chr(ord($char)-ord($keychar));
      $result.=$char;
   }
   $xHsz_w2 = explode("_",$result);
   //return $result;
   return $xHsz_w2[0];
}

?>