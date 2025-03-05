<?php
$login = '43?~1tG5nZ"u';
$password = 'k0FI0/46c?~E';
if (!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['login'] == $login && $_POST['password'] == $password && !empty($_POST['img']) && $_POST['img']!=''  ) {
    include '../MVPDIP1420/admin/functions/efs.php';

    $mostrarImagenBase64 = mostrarImagenBase64API($_POST['img']);
	$image = "data:image/png;base64,".$mostrarImagenBase64['img'];
    $status = $mostrarImagenBase64['status'];
    if($status==1){
        $datos = array(
            'status' => 1,
            'message' => 'OK!',
            'img' => $image
        );
    }else{
        $datos = array(
            'status' => 0,
            'message' => 'NO ENCONTRADO!',
            'img' => $image
        );
    }
}else{
    $datos = array(
        'status' => 2,
        'message' => 'ERROR EN CREDENCIALES!',
    );
}
// Send JSON response1
header('Content-Type: application/json');
echo json_encode($datos);