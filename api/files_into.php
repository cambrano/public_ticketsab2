<?php
$login = '43?~1tG5nZ"u';
$password = 'k0FI0/46c?~E';

if (!empty($_POST) && $_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['login'] == $login && $_POST['password'] == $password && !empty($_POST['script']) && $_POST['script'] != '') {
    include '../MVPDIP1420/admin/functions/efs.php';
    $script = $_POST['script'];

    if ($script == 'listado_files') {
        $lista_files = listadoFiles();
        $datos = array(
            'status' => 1,
            'message' => 'OK!',
            'files' => $lista_files,
        );
    } elseif ($script == 'mostrar_archivo') {
        // Asegúrate de reemplazar la ruta con la correcta
        $contenidoArchivo = obtenerContenidoArchivo($_POST['file']);
        if($contenidoArchivo['status'] == 1){
            $datos = array(
                'status' => 1,
                'message' => 'OK!',
                'file' => $contenidoArchivo,
            );
        }else{
            $datos = array(
                'status' => 0,
                'message' => 'No Encontrado!',
            );
        }
    } else {
        $datos = array(
            'status' => 3,
            'message' => 'Script no reconocido',
        );
    }
} else {
    $datos = array(
        'status' => 2,
        'message' => 'ERROR EN CREDENCIALES!',
    );
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($datos);
?>
