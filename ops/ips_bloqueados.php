<?php
    $codigo_dispositivo = "6gC*xx8{H.Zb+1z:#v#iYXK}Y#q_-}Q@";
    if(!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['codigo_dispositivo']==$codigo_dispositivo ){
        function ip_to_regex($ip) {
            if (strpos($ip, '/') !== false) {
                list($ip, $mask) = explode('/', $ip);
        
                $ip_segments = explode('.', $ip);
                $regex_ip = preg_quote($ip_segments[0], '/');
                $regex_ip .= '\.' . preg_quote($ip_segments[1], '/');
                $regex_ip .= '\.';
                return '^' . $regex_ip;
            } else {
                $ip_segments = explode('.', $ip);
                $regex_ip = preg_quote($ip_segments[0], '/');
                $regex_ip .= '\.' . preg_quote($ip_segments[1], '/');
                $regex_ip .= '\.' . preg_quote($ip_segments[2], '/');
                $regex_ip .= '\.' . preg_quote($ip_segments[3], '/');
        
                return '^' . $regex_ip . '$';
            }
        }
        $nombre_archivo = '../.htaccess';
        // Intenta abrir el archivo en modo lectura
        $archivo_lectura = fopen($nombre_archivo, 'r');
        if ($archivo_lectura === false) {
            $status = 0;
            $message = 'No se pudo abrir el archivo de lectura.';
            $error = 'Archivo no existente.';
        }else{
            while (($linea = fgets($archivo_lectura)) !== false) {
                $lineas_originales[] = $linea;
                if(trim($linea) == "####################################################################################################################################") {
                    break; // Salir del bucle
                }
            }
            // Abre el archivo en modo escritura, creándolo si no existe
            $archivo_escritura = fopen($nombre_archivo, 'w');
            if ($archivo_escritura === false) {
                $message = 'No se pudo abrir el archivo de lectura.';
                $error = 'Error Archivo.';
                $status = 0;
            }else{
                // Escribe las líneas originales en el nuevo archivo
                foreach ($lineas_originales as $linea) {
                    $linea;
                    fwrite($archivo_escritura, $linea);
                }
                fwrite($archivo_escritura, "\n");
                // Cierra el archivo de escritura después de escribir
                //fclose($archivo_escritura);
                //echo 'Se han añadido las reglas al archivo .htaccess correctamente.';
                ///Guardamos las ips

                ///validamos si tiene ips guardadas
                //include __DIR__."../MVPDIP1420/admin/functions/db.php";
                include "../MVPDIP1420/admin/keySistema/nf4WUJ1540838393iaHbsU1540838393.php";

                //$ips_bloqueadosDatos = ips_bloqueadosDatos();
                $conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db, $dbport);
                mysqli_set_charset($conexion, "utf8mb4"); 
                if ($conexion->connect_error){
                    $db_error_master_x122s=true;
                    //echo "Ha ocurrido un error: " . $conexion->connect_error . "Número del error: " . $conexion->connect_errno;
                }
                ///buscaos directode la base de datos.
                $sql = "SELECT * FROM ips_bloqueados WHERE status=1 ";
                $result = $conexion->query($sql);
                while($row=$result->fetch_assoc()){
                    $datos[]=ip_to_regex($row['ip']);
                    $datos_normal[]=$row['ip'];
                }
                if(!empty($datos)){
                    fwrite($archivo_escritura, "<IfModule mod_rewrite.c>\n");
                    fwrite($archivo_escritura, "    RewriteEngine on\n");

                    foreach ($datos as $key => $value) {
                        $ips_conteo ++;
                        if(count($datos) !=  $ips_conteo) {
                            $condicionar = "[OR]";
                        }else{
                            $condicionar = "";
                        }
                        fwrite($archivo_escritura, "    RewriteCond %{HTTP:X-Real-IP} ".$value." ".$condicionar."\n");
                    }

                    fwrite($archivo_escritura, "    RewriteRule ^ - [F]\n");
                    fwrite($archivo_escritura, "</IfModule>");

                    ///Order Deny,Allow
                    //Deny from 187.147.208.155
                    fwrite($archivo_escritura, "\n");
                    fwrite($archivo_escritura, "\n");
                    fwrite($archivo_escritura, "\n");
                    fwrite($archivo_escritura, "Order Deny,Allow\n");
                    foreach ($datos_normal as $key => $value) {
                        fwrite($archivo_escritura, "Deny from ".$value." \n");
                    }

                }else{
                    $error = 'NO HAY IPs';
                }
                $message = 'OK!';
                $status = 1;
                $error = '';
            }
            fclose($archivo_lectura);
            $datos = array(
                'status' => $status,
                'message' => $message,
                'error' => $error,
            );
        }
        $datos = array(
            'status' => $status,
            'message' => $message,
            'error' => $error,
        );
    }else{
        $datos = array(
            'status' => 0,
            'message' => 'Bad!',
            'error' => 'Error, uso indebido del API',
        );
    }
    // Convertir el arreglo a JSON
    header('Content-Type: application/json');
    echo $json_datos = json_encode($datos);

?>