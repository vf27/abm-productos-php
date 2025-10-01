
<?php
$configuracion = require 'config.php';

$host = $configuracion['db']['host'];
$usuario = $configuracion['db']['user'];
$password = $configuracion['db']['pass'];
$opciones = $configuracion['db']['options'];

try {
    $conexion = new PDO("mysql:host={$host}", $usuario, $password, $opciones);
    
    $archivoSQL = file_get_contents("productos_mercadolibre.sql");
    $conexion->exec($archivoSQL);
    
    echo "Base de datos <b>productos_mercadolibre</b> y tabla <b>productos</b> creadas correctamente.";
    echo "<br><br><a href='index.php' class='btn btn-success'>Volver al inicio</a>";
    
} catch (PDOException $error) {
    if (strpos($error->getMessage(), "exists") !== false) {
        echo "La base de datos ya existe";
        echo "<br><br><a href='index.php' class='btn btn-primary'>Ir a la aplicación</a>";
    } else {
        echo "Error: al crear la base de datos" . $error->getMessage();
    }
}
?>