<!DOCTYPE html>
<html>
<head>
    <title>Contenido de post.json</title>
</head>
<body>
    <?php
    // Ruta del archivo JSON
    $jsonFilePath = 'login.json';
    
    // Leer el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFilePath);
    
    // Decodificar el JSON
    $data = json_decode($jsonData, true);
    
    if ($data !== null) {
        echo "<pre>";
        echo json_encode($data, JSON_PRETTY_PRINT);
        echo "</pre>";
    } else {
        echo "Error al leer o decodificar el JSON.";
    }
    ?>
    <hr>
    <?php
    // Ruta del archivo JSON
    $jsonFilePath = 'post_token.json';
    
    // Leer el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFilePath);
    
    // Decodificar el JSON
    $data = json_decode($jsonData, true);
    
    if ($data !== null) {
        echo "<pre>";
        echo json_encode($data, JSON_PRETTY_PRINT);
        echo "</pre>";
    } else {
        echo "Error al leer o decodificar el JSON.";
    }
    ?>
    <hr>
    <?php
    // Ruta del archivo JSON
    $jsonFilePath = 'usuario.json';
    
    // Leer el contenido del archivo JSON
    $jsonData = file_get_contents($jsonFilePath);
    
    // Decodificar el JSON
    $data = json_decode($jsonData, true);
    
    if ($data !== null) {
        echo "<pre>";
        echo json_encode($data, JSON_PRETTY_PRINT);
        echo "</pre>";
    } else {
        echo "Error al leer o decodificar el JSON.";
    }
    ?>
</body>
</html>
