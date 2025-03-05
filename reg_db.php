<?php

if(isset($_POST['formulario'][0]['xcode1'])){
    $dejarenblanco = $_POST['formulario'][0]['xcode1'];
}
if(isset($_POST['formulario'][0]['xcode2'])){
    $nocambiar = $_POST['formulario'][0]['xcode2'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $dejarenblanco == '' && $nocambiar == 'https://' && !empty($_POST) && is_array($_POST['formulario'])) {
    $form_post = $_POST['formulario'][0];
    $nombre = $form_post["nombre"];
    $telefono = $form_post["telefono"];
    $correo_electronico = $form_post["correo_electronico"];
    $descripcion = $form_post["descripcion"];
    date_default_timezone_set('America/Mazatlan');//!cambio de zona horaria
    setlocale(LC_ALL, "es_ES");
    $fechaH = date('Y-m-d H:i:s');

    // Datos a enviar en el cuerpo de la solicitud
    $data = array(
        'key' => "z:e>xByJ^v4`82m|Zk'1%/O",
        'nombre' => $nombre,
        'telefono' => $telefono,
        'correo_electronico' => $correo_electronico,
        'descripcion' => $descripcion,
        'fechaR' => $fechaH,
        'server' => $_SERVER,
    );

    $fields_string = http_build_query($data);
    // URL de la API de destino
    $api_url = 'https://ideasab.com/apiRegistroRadar/plataformasRadarABPM.php'; // Reemplaza con la URL real de la API

    // Inicializar la solicitud cURL
    $ch = curl_init($api_url);

    // Configurar la solicitud cURL para enviar datos POST
    curl_setopt($ch, CURLOPT_POST, 1); // Utiliza CURLOPT_POST en lugar de CURLOPTform_post
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); // Utiliza CURLOPT_POSTFIELDS en lugar de CURLOPTform_postFIELDS
    // Configurar otras opciones según sea necesario
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Configura la opción para recibir la respuesta como una cadena
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Realiza la solicitud
    $response = curl_exec($ch);

    // Cierra la sesión cURL
    curl_close($ch);

    // Decodifica la respuesta JSON
    $responseData = json_decode($response, true);

    if($responseData['status']==1){
        $data_api = array(
            'status' => 1,
            'mensaje' => 'ok!'
        );
    }else{
        $data_api = array(
            'status' => 0,
            'mensaje' => 'error, server :('
        );
    }

    // Configura las cabeceras para indicar que la respuesta es JSON
    header('Content-Type: application/json');
    // Imprime la respuesta JSON de error
    echo json_encode($data_api);


}

?>
