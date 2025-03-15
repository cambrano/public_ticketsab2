<?php
if($_GET['cot']!="sol88"){
    echo ":(";
    //die;
}
date_default_timezone_set('America/Cancun');//!cambio de zona horaria
setlocale(LC_ALL,"es_ES");
include "../MVPDIP1420/admin/functions/db.php";
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
    $fecha_inicial =  '2024-04-22';
    $fecha_final = date('Y-m-d');
    $dias_trabajo = array();

    $fecha_actual = $fecha_inicial;

    while ($fecha_actual <= $fecha_final) {
        $dias_trabajo[] = $fecha_actual;
        $fecha_actual = date('Y-m-d', strtotime($fecha_actual . ' +1 day'));
    }

    print_r($b);

    $dias_trabajo1 = array(
        '2024-04-30',
        '2024-05-01',
        '2024-05-02',
        '2024-05-03',
        '2024-05-04',
        '2024-05-05'
    );


    $sql = "SELECT * FROM usuarios WHERE tabla = 'empleados' AND id>3 ; ";
    $resultado = $conexion->query($sql);
    while($row=$resultado->fetch_assoc()){
        $data[] = $row;
    }
    $data[] = array(
        'id' => 1,
        'id_empleado' => 15,
        'usuario' => 'soporte'
    );
    echo "<table border=1>";
    echo "<tr>";
    echo "<td>Usuario</td>";
    $dia=1;
    foreach ($dias_trabajo as $key => $value) {
        echo "<td>";
        echo "Día - ".$dia;
        echo "<br>";
        echo $value;
        echo "</td>";
        $dia ++;
    }
    echo "<td>totales</td>";
    echo "</tr>";
    
    foreach ($data as $key => $value) {
        echo "<tr>";
        echo "<td>".$value['usuario']."</td>";
        //dias
        $id_usuario = $value['id'];
        $totales = 0;
        foreach ($dias_trabajo as $keyT => $valueT) {
            $inicial = $valueT." 00:00:00";
            $final = $valueT." 23:59:59";
            //! log usuarios
            $sqlLog = "SELECT COUNT(*) total 
                        FROM log_usuarios lu
                        WHERE 
                            lu.tabla='secciones_ine_ciudadanos' AND 
                            lu.operacion ='Insert' AND
                            lu.fechaR BETWEEN '{$inicial}' AND '{$final}' AND 
                            lu.id_usuario = '{$id_usuario}' AND 
                            EXISTS ( SELECT sic.id FROM secciones_ine_ciudadanos sic WHERE sic.id = lu.id_columna )
                        ;";
            $resultadoLog = $conexion->query($sqlLog);
            $rowLog=$resultadoLog->fetch_assoc();
            if($rowLog['total']==0){
                $background = "style='color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;'";
            }else{
                $background = "style='color: #004085;background-color: #cce5ff;border-color: #b8daff;'";
            }
            echo "<td class='totales' {$background} >".$rowLog['total']."</td>";
            $totales = $rowLog['total'] + $totales;

            $totales_fecha[$valueT] = $totales_fecha[$valueT] + $rowLog['total'];

        }

        if($totales==0){
            $background = "style='color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;'";
        }else{
            $background = "style='color: #004085;background-color: #cce5ff;border-color: #b8daff;'";
        }
        echo "<td class='totales' {$background} >".$totales."</td>";
        $tota_totales = $totales + $tota_totales;

        echo "</tr>";
    }
    echo "<tr>";
    echo "<td class='totales'>totales</td>";
    foreach ($totales_fecha as $key => $value) {
        echo "<td class='totales'>".$value."</td>";
    }
    echo "<td class='totales'>".$tota_totales."</td>";
    echo "</tr>";

    echo "</table>";