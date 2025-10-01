<?php
session_start();

$config = include 'config.php';
$resultado = [
    'error' => false,
    'mensaje' => ''
];

try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
    $id = $_GET['id'];
    $consultaSQL = "DELETE FROM productos WHERE id_producto = " . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();

    header('Location: index.php');
    exit();

} catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error al Eliminar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #e3e1d5; 
            color: black;
        }

        .navbar {
            background-color: #908A77;
            padding: 1rem 0;
        }

        .navbar-brand {
            color: #FFFFFF !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .contenedor-error {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }

        .tarjeta-error {
            max-width: 600px;
            width: 100%;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background-color: #FFFFFF;
        }

        .cabecera-error {
            background-color: #dc3545;
            color: #FFFFFF;
            text-align: center;
            padding: 1.5rem;
        }

        .cabecera-error h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .cabecera-error small {
            display: block;
            margin-top: 0.5rem;
            opacity: 0.9;
        }

        .cuerpo-error {
            padding: 2rem;
        }

        .mensaje-error {
            background-color: #f8d7da;
            border: 1px solid #dc3545;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #721c24;
        }

        .btn-volver {
            background-color: #908A77;
            color: #FFFFFF;
            border: 2px solid #908A77;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .btn-volver:hover {
            background-color: #7A7463;
            border-color: #7A7463;
            color: #FFFFFF;
            text-decoration: none;
        }

        .icono-error {
            font-size: 4rem;
            color: #dc3545;
            text-align: center;
            margin-bottom: 1rem;
        }

        .texto-centrado {
            text-align: center;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-brand">Sistema de Productos</span>
        </div>
    </nav>

    <div class="container contenedor-error">
        <div class="tarjeta-error">
            <div class="cabecera-error">
                <h4>Error al Eliminar Producto</h4>
                <small>Se ha producido un problema durante la operación</small>
            </div>
            
            <div class="cuerpo-error">
                <div class="icono-error texto-centrado">
                    ⚠
                </div>
                
                <div class="mensaje-error">
                    <strong>Detalles del error:</strong><br>
                    <?= $resultado['mensaje'] ?>
                </div>

                <div class="texto-centrado">
                    <p>Lo sentimos, no se pudo completar la eliminación del producto. Por favor, inténtalo nuevamente o contacta al administrador del sistema si el problema persiste.</p>
                    
                    <a href="index.php" class="btn-volver">Volver a la Lista</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>