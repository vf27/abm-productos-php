<?php
session_start();

$config = include 'config.php';
$resultado = [
    'error' => false,
    'mensaje' => ''
];

if (!isset($_GET['id'])) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'El producto no existe';
}
$host    = $config['db']['host'];
$dbname  = $config['db']['name'];
$user    = $config['db']['user'];
$pass    = $config['db']['pass'];
$options = $config['db']['options'];

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $conexion = new PDO($dsn, $user, $pass, $options);
    
    $id = $_GET['id'];
    $consultaSQL = "SELECT * FROM productos WHERE id_producto = " . $id;

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute();
    $producto = $sentencia->fetch(PDO::FETCH_ASSOC);

    if (!$producto) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'No se ha encontrado el producto';
    }

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
    <title>Ver Producto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container contenedor-formulario">
        <div class="tarjeta-formulario">
            <?php if ($resultado['error']): ?>
            <div class="alert-custom" role="alert">
                <strong>Error:</strong> <?= $resultado['mensaje'] ?>
                <br><a href="index.php" class="btn btn-volver mt-2">Volver a la lista</a>
            </div>
            <?php else: ?>
            
            <div class="cabecera-formulario">
                <h3>Sistema de Productos</h3>
                <small>Detalles del Producto</small>
                <?php if (isset($producto) && $producto): ?>
                <?php endif; ?>
            </div>
            
            <?php if (isset($producto) && $producto): ?>
            <div class="cuerpo-formulario">
                <div class="seccion">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="titulo-seccion ">Información del Producto</h6>
                    <span class="producto-titulo">ID: #<?= $producto['id_producto'] ?></span>
                </div>
                
                <div class="campo-detalle">
                    <label>Título del Producto</label>
                    <div class="campo-valor">
                        <?= htmlspecialchars($producto['titulo']) ?>
                    </div>
                </div>

                    <div class="campo-detalle">
                        <label>Descripción</label>
                        <div class="campo-valor-texto">
                            <?= htmlspecialchars($producto['descripcion']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="campo-detalle">
                                <label>Precio</label>
                                <div>
                                    <span class="precio-valor">$<?= number_format($producto['precio'], 2, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="campo-detalle">
                                <label>Stock</label>
                                <div>
                                    <span class="stock-valor <?= $producto['stock'] < 10 ? 'stock-bajo' : 'stock-normal' ?>">
                                        <?= $producto['stock'] ?> unidades
                                        <?= $producto['stock'] < 10 ? ' (Stock bajo)' : '' ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="campo-detalle">
                                <label>Categoría</label>
                                <div>
                                    <span class="badge-categoria"><?= $producto['categoria'] ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="campo-detalle">
                                <label>Estado de Publicación</label>
                                <div >
                                    <?php if ($producto['publicado']): ?>
                                        <span class="badge-activo">Activo</span>
                                    <?php else: ?>
                                        <span class="badge-pausado">Pausado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center acciones-botones">
                    <a href="index.php" class="btn-volver">Volver</a>
                    <a href="editar.php?id=<?= $producto['id_producto'] ?>" class="btn-editar">Editar</a>
                    <a href="borrar.php?id=<?= $producto['id_producto'] ?>" class="btn-eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background-color: #e3e1d5; 
        color: black;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .contenedor-formulario {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 10px 20px;
        width: 100%;
    }

    .tarjeta-formulario {
        max-width: 700px;
        width: 100%;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        margin: 0 auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .cabecera-formulario {
        background-color: #908A77;
        color: #FFFFFF;
        text-align: center;
        padding: 1.5rem;
    }

    .cabecera-formulario h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .cabecera-formulario small {
        display: block;
        margin-top: 0.5rem;
        opacity: 0.9;
    }

    .producto-titulo {
        display: inline-block;
        padding: 0.3rem 0.7rem;
        background-color: #C97B63;
        color: #fff;
        font-size: 1rem; 
        font-weight: bold;
        border-radius: 6px;
    }

    .cuerpo-formulario {
        background-color: #FFFFFF;
        padding: 5px 1.5rem 1.5rem 1.5rem;
    }

    .titulo-seccion {
        color: #908A77;
        font-size: 20px;
        margin-bottom: 1rem;
        margin-top: 0.5rem;
        border-bottom: 2px solid #e3e1d5;
        padding-bottom: 0.5rem;
    }

    .campo-detalle {
        margin-bottom: 1rem;
    }

    .campo-detalle label {
        font-weight: bold;
        color: #908A77;
        display: block;
        margin-bottom: 0.5rem;
    }

    .campo-valor {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 0.75rem;
        min-height: 42px;
        display: flex;
        align-items: center;
    }

    .campo-valor-texto {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 0.75rem;
        min-height: 80px;
        line-height: 1.6;
    }

    .precio-valor {
        font-size: 1.5rem;
        font-weight: bold;
        color: #C97B63;
    }

    .stock-valor {
        font-weight: bold;
    }

    .stock-bajo {
        color: #dc3545;
    }

    .stock-normal {
        color: #28a745;
    }

    .badge-activo {
        background-color: #28a745;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .badge-pausado {
        background-color: #ffc107;
        color: black;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .badge-categoria {
        background-color: #908A77;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .btn-editar,
    .btn-volver,
    .btn-eliminar {
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        min-width: 100px;
        text-align: center;
        display: inline-block;
    }

    .btn-editar {
        background-color: #C97B63;
        color: #FFFFFF;
        border: 2px solid #C97B63;
    }

    .btn-editar:hover {
        background-color: #D08970;
        border-color: #D08970;
        color: #FFFFFF;
        text-decoration: none;
    }

    .btn-volver {
        background-color: #908A77;
        color: #FFFFFF;
        border: 2px solid #908A77;
    }

    .btn-volver:hover {
        background-color: #7A7463;
        border-color: #7A7463;
        color: #FFFFFF;
        text-decoration: none;
    }

    .btn-eliminar {
        background-color: #dc3545;
        color: #FFFFFF;
        border: 2px solid #dc3545;
    }

    .btn-eliminar:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: #FFFFFF;
        text-decoration: none;
    }

    .alert-custom {
        background-color: #f8d7da;
        border: 1px solid #dc3545;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        color: #721c24;
    }

    .acciones-botones {
    margin-top: 1.5rem; 
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    gap: 10px;
}

    .container {
        max-width: 100%;
        overflow-x: hidden;
    }

    .row {
        margin-left: 0;
        margin-right: 0;
    }

    .col-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
</style>
</html>