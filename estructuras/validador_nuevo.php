<?php
if($_GET['cot']!="sol88"){
    echo ":(";
    die;
}
date_default_timezone_set('America/Cancun');//!cambio de zona horaria
setlocale(LC_ALL,"es_ES");

include "../MVPDIP1420/admin/functions/db.php";

date_default_timezone_set('America/Cancun');//!cambio de zona horaria
setlocale(LC_ALL,"es_ES");

?>
<style>
    td{
        padding: 10px;
    }
    .totales{
        text-align:center;
    }
</style>



<?php
    function contarCaracteres($texto) {
        // Usa la función mb_strlen para contar los caracteres de manera segura
        return mb_strlen($texto);
    }
    
    $sql = "SELECT
                sic.id,
                sic.clave,
                sic.folio,
                '' AS columna,
                sic.apellido_paterno,
                sic.apellido_materno,
                sic.nombre,
                sic.calle,
                sic.colonia,
                sic.codigo_postal,
                (SELECT m.municipio FROM municipios m WHERE m.id = sic.id_municipio ) municipio,
                (SELECT e.estado FROM estados e WHERE e.id = sic.id_estado ) entidad,
                sic.clave_elector,
                sic.curp,
                (SELECT s.numero FROM secciones_ine s WHERE s.id = sic.id_seccion_ine ) seccion,
                sic.whatsapp,
                if(sic.sexo='Mujer','M','H') genero,
                (SELECT CONCAT(lg.nombre_usuario,'-',lg.fechaR) FROM log_usuarios lg WHERE lg.id_columna = sic.id AND operacion = 'Insert' ) AS 'insert'
                
                
            FROM secciones_ine_ciudadanos sic WHERE sic.fechaR > '2024-05-15 10:00:00' ";
    $resultado = $conexion->query($sql);
    while($row=$resultado->fetch_assoc()){
        $clave_elector_original = $row['clave_elector'];
        if(contarCaracteres($row['clave_elector']) == 18 ){
            $data[] = $row;
        }elseif (contarCaracteres($row['clave_elector']) == 19) {
            //echo "bien";
            $ultimo_caracter = substr($row['clave_elector'], -1);
            $row['clave_elector'] = substr($row['clave_elector'], 0, -1);
            if($ultimo_caracter == 'Z'){
                $data[] = $row;
            }elseif ($ultimo_caracter == 'z') {
                $data[] = $row;
            }else{
                echo "<table border=1 >";
                echo "<tr>";
                echo "<td>ID</td>";
                echo "<td>".$row['id']."</td>";
                echo "</tr>";
                
                echo "<tr>";
                echo "<td>Clave</td>";
                echo "<td>".$row['clave']."</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Folio</td>";
                echo "<td>".$row['folio']."</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Clave de elector</td>";
                echo "<td>".$clave_elector_original.". Error en la clave de elector tiene ";
                echo contarCaracteres($clave_elector_original)." caracteres";
                echo " - - ";
                if(contarCaracteres($clave_elector_original)>19){
                    echo "El error es que tiene mas de 18 caracteres y no incluiste la letra Z al final";
                }elseif (contarCaracteres($clave_elector_original)<19) {
                    echo "El error es que tiene menos de 18 digitos sin incluir la Z.";
                }else{
                    echo "El error es que tiene mas de 18 caracteres y no incluiste la letra Z al final";
                }

                echo "</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td>Usuario</td>";
                echo "<td>".$row['insert']."</td>";
                echo "</tr>";

                echo "<br>";
            }
        }else{
            
            echo "<table border=1 >";
            echo "<tr>";
            echo "<td>ID</td>";
            echo "<td>".$row['id']."</td>";
            echo "</tr>";
            
            echo "<tr>";
            echo "<td>Clave</td>";
            echo "<td>".$row['clave']."</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Folio</td>";
            echo "<td>".$row['folio']."</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Clave de elector</td>";
            echo "<td>".$clave_elector_original.". Error en la clave de elector tiene ";
            echo contarCaracteres($clave_elector_original)." caracteres";
            echo " - - ";
            if(contarCaracteres($clave_elector_original)>19){
                echo "El error es que tiene mas de 18 digitos sin incluir la Z.";
            }elseif (contarCaracteres($clave_elector_original)<19) {
                echo "El error es que tiene menos de 18 digitos sin incluir la Z.";
            }

            echo "</td>";
            echo "</tr>";

            echo "<tr>";
            echo "<td>Usuario</td>";
            echo "<td>".$row['insert']."</td>";
            echo "</tr>";

            echo "<br>";
            
        }
        
        
    }
    die;
    echo "<table border=1>";
    foreach ($data as $key => $value) {
        echo "<tr>";
        foreach ($value as $keyT => $valueT) {
            echo "<td>".$valueT."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
