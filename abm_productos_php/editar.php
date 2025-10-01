<?php
session_start();

$config = include 'config.php';

$host = $config['db']['host'];
$dbname = $config['db']['name'];
$user = $config['db']['user'];
$pass = $config['db']['pass'];
$options = $config['db']['options'];

$mensaje_resultado = '';
$tipo_alerta = '';
$mostrar_resultado = false;
$producto = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $mensaje_resultado = 'ID de producto no válido';
    $tipo_alerta = 'danger';
    $mostrar_resultado = true;
} else {
    $id = $_GET['id'];
    
    try {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $conexion = new PDO($dsn, $user, $pass, $options);
        
        $consultaSQL = "SELECT * FROM productos WHERE id_producto = :id";
        $sentencia = $conexion->prepare($consultaSQL);
        $sentencia->bindParam(':id', $id, PDO::PARAM_INT);
        $sentencia->execute();
        
        $producto = $sentencia->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            $mensaje_resultado = 'El producto no fue encontrado';
            $tipo_alerta = 'danger';
            $mostrar_resultado = true;
        }
        
    } catch(PDOException $excepcion) {
        $mensaje_resultado = 'Error de conexión: ' . $excepcion->getMessage();
        $tipo_alerta = 'danger';
        $mostrar_resultado = true;
    }
}

if (isset($_POST['actualizar_producto']) && $producto) {
    try {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $conexion = new PDO($dsn, $user, $pass, $options);
        
        $datos_producto = array(
            'id_producto' => $id,
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'precio' => $_POST['precio'],
            'stock' => $_POST['stock'],
            'categoria' => $_POST['categoria'],
            'publicado' => isset($_POST['publicado']) ? 1 : 0
        );
        
        $sql_actualizar = "UPDATE productos SET titulo = :titulo, descripcion = :descripcion, precio = :precio, stock = :stock, categoria = :categoria, publicado = :publicado WHERE id_producto = :id_producto";
        
        $consulta_preparada = $conexion->prepare($sql_actualizar);
        $consulta_preparada->execute($datos_producto);
        
        $mensaje_resultado = 'El producto ID #' . $id . ' "' . $_POST['titulo'] . '" se ha actualizado exitosamente';        
        $tipo_alerta = 'success';
        $mostrar_resultado = true;        
        $producto = array_merge($producto, $datos_producto);
        
    } catch(PDOException $excepcion) {
        $mensaje_resultado = 'Error al actualizar el producto: ' . $excepcion->getMessage();
        $tipo_alerta = 'danger';
        $mostrar_resultado = true;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edición de Producto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>   
    <?php if ($mostrar_resultado): ?>
    <div class="alerta <?= $tipo_alerta ?>" id="alerta">
        <div class="alerta-cabecera">
            <div class="alerta-titulo">
                <?php if ($tipo_alerta === 'success'): ?> <span>✅</span> Éxito
                <?php else: ?> <span>❌</span> Error
                <?php endif; ?>
            </div>
            <button class="alerta-cerrar" onclick="esconderAlerta()">&times;</button>
        </div>
        <div class="alerta-cuerpo">
            <?= $mensaje_resultado ?>
            <?php if ($tipo_alerta === 'success'): ?>
                <div class="alerta-accion">
                    <a href="index.php">Ver listado de productos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($tipo_alerta === 'danger' && !$producto): ?>
    <div class="container-fluid">
        <div class="contenedor-formulario">
            <div class="tarjeta-producto">
                <div class="cabecera-tarjeta">
                    <h3>Error de Acceso</h3>
                    <small>No se pudo cargar el producto</small>
                </div>
                <div class="cuerpo-tarjeta text-center">
                    <p><?= $mensaje_resultado ?></p>
                    <a href="index.php" class="boton-regresar">Regresar al Listado</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <div class="container-fluid">
        <div class="contenedor-formulario">
            <div class="tarjeta-producto">
                <div class="cabecera-tarjeta">
                    <h3>Gestión de Inventario</h3>
                    <small>Edición de Producto Existente</small>
                </div>
                
                <?php if ($producto): ?>
                <div class="cuerpo-tarjeta">
                    <form method="post" action="">
                        <div class="seccion-datos">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="titulo-seccion m-0">Detalles del Producto</h6>
                                <?php if ($producto): ?>
                                <span style="background-color: #C97B63; color: white; padding: 4px 10px; border-radius: 4px; font-size: 0.85rem; font-weight: bold;">
                                    ID: #<?= $producto['id_producto'] ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ingrese el nombre del producto" value="<?= htmlspecialchars($producto['titulo']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción Detallada *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Proporcione una descripción completa del producto..." required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3">
                                    <label for="precio" class="form-label">Precio de Venta ($) *</label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" placeholder="0.00" value="<?= number_format($producto['precio'], 2, '.', '') ?>" required>
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label for="stock" class="form-label">Cantidad en Stock *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0" placeholder="0" value="<?= $producto['stock'] ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría del Producto *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Seleccione una categoría...</option>
                                    <?php
                                    $categorias = [ 'Hogar, Muebles y Jardín', 'Hogar, Patio y Jardín', 'Joyas y Relojes', 'Souvenirs, Cotillón y Fiestas', 'Electrónicos', 'Ropa y Accesorios', 'Deportes y Fitness', 'Libros y Revistas' ];
                                    
                                    foreach ($categorias as $cat): ?>
                                        <option value="<?= htmlspecialchars($cat) ?>" 
                                                <?= $producto['categoria'] === $cat ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="publicado" name="publicado" <?= $producto['publicado'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="publicado"> Activar producto </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center botones-container">
                            <button type="submit" name="actualizar_producto" class="boton-registrar">Actualizar Producto</button>
                            <a href="index.php" class="boton-regresar">Regresar al Inicio</a>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
        <?php if ($mostrar_resultado): ?>
        setTimeout(function() {
            document.getElementById('alerta').classList.add('show');
        }, 100);
        <?php if ($tipo_alerta === 'success'): ?>
        setTimeout(function() {
            esconderAlerta();
        }, 5000);
        <?php endif; ?>
        <?php endif; ?>

        function esconderAlerta() {
            var alerta = document.getElementById('alerta');
            if (alerta) {
                alerta.classList.remove('show');
                setTimeout(function() {
                    alerta.remove();
                }, 400);
            }
        }
    </script>
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

    .alerta {
        position: fixed;
        top: 20px;
        right: -400px;
        width: 350px;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 1000;
        transition: right 0.4s ease-in-out;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .alerta.show {
        right: 20px;
    }

    .alerta.success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-left: 4px solid #C97B63;
        color: #155724;
    }

    .alerta.danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        border-left: 4px solid #dc3545;
        color: #721c24;
    }

    .alerta-cabecera {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .alerta-titulo {
        font-weight: bold;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alerta-cerrar{
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }

    .alerta-cerrar:hover {
        background-color: rgba(0,0,0,0.1);
    }

    .alerta-cuerpo {
        font-size: 14px;
        line-height: 1.4;
    }

    .alerta-accion {
        margin-top: 10px;
    }

    .alerta-accion a {
        display: inline-block;
        padding: 6px 12px;
        background-color: #C97B63;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 12px;
        transition: background-color 0.2s;
    }

    .alerta-accion a:hover {
        background-color: #D08970;
        color: white;
    }

    .contenedor-formulario {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 10px 20px;
        width: 100%;
    }

    .tarjeta-producto {
        max-width: 700px;
        width: 100%;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        margin: 0 auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .cabecera-tarjeta {
        background-color: #908A77;
        color: #FFFFFF;
        text-align: center;
        padding: 1.5rem;
    }

    .cabecera-tarjeta h3 {
        margin: 0;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .cabecera-tarjeta small {
        display: block;
        margin-top: 0.5rem;
        opacity: 0.9;
    }

    .cuerpo-tarjeta {
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

    .boton-registrar {
        background-color: #C97B63;
        color: #FFFFFF;
        border: 2px solid #C97B63;
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 500;
        margin-top: 10px;
        min-width: 160px;
    }

    .boton-registrar:hover {
        background-color: #D08970;
        border-color: #D08970;
        color: #FFFFFF;
    }

    .boton-regresar {
        background-color: #908A77;
        color: #FFFFFF;
        border: 2px solid #908A77;
        padding: 10px 25px;
        border-radius: 6px;
        font-weight: 500;
        margin-top: 10px;
        min-width: 160px;
        text-decoration: none;
        text-align: center;
        display: inline-block;
    }

    .boton-regresar:hover {
        background-color: #7A7463;
        border-color: #7A7463;
        color: #FFFFFF;
        text-decoration: none;
    }

    .form-control, .form-select {
        width: 100%;
        max-width: 100%;
    }

    .form-control:focus {
        border-color: #C97B63;
        box-shadow: 0 0 0 0.2rem rgba(201, 123, 99, 0.25);
    }

    .form-select:focus {
        border-color: #C97B63;
        box-shadow: 0 0 0 0.2rem rgba(201, 123, 99, 0.25);
    }

    .botones-container {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
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

    .col-12, .col-sm-6 {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
</style>
</html>