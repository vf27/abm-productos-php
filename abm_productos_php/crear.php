<?php
session_start();

$mensaje_resultado = '';
$tipo_alerta = '';
$mostrar_resultado = false;

if (isset($_POST['enviar_producto'])) {
    $config = include 'config.php';
    
    $host = $config['db']['host'];
    $dbname = $config['db']['name'];
    $user = $config['db']['user'];
    $pass = $config['db']['pass'];
    $options = $config['db']['options'];

    try {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $conexion = new PDO($dsn, $user, $pass, $options);

        $datos_producto = array(
            "titulo" => $_POST['titulo'],
            "descripcion" => $_POST['descripcion'],
            "precio" => $_POST['precio'],
            "stock" => $_POST['stock'],
            "categoria" => $_POST['categoria'],
            "publicado" => isset($_POST['publicado']) ? 1 : 0
        );

        $sql_insertar = "INSERT INTO productos (titulo, descripcion, precio, stock, categoria, publicado) VALUES (:" . implode(", :", array_keys($datos_producto)) . ")";
        $consulta_preparada = $conexion->prepare($sql_insertar);
        $consulta_preparada->execute($datos_producto);

        $mensaje_resultado = 'El producto "' . $_POST['titulo'] . '" se ha registrado exitosamente';
        $tipo_alerta = 'success';
        $mostrar_resultado = true;

    } catch(PDOException $excepcion) {
        $mensaje_resultado = 'Error al registrar el producto: ' . $excepcion->getMessage();
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
    <title>Registro de Producto</title>
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
        <div class="alerta-body">
            <?= $mensaje_resultado ?>
            <?php if ($tipo_alerta === 'success'): ?>
                <div class="alerta-accion">
                    <a href="index.php">Ver listado de productos</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="contenedor-formulario">
            <div class="tarjeta-producto">
                <div class="cabecera-tarjeta">
                    <h3>Gestión de Inventario</h3>
                    <small>Registro de Nuevo Producto</small>
                </div>
                
                <div class="cuerpo-tarjeta">
                    <form method="post" action="">
                        <div class="seccion-datos">
                            <h6 class="titulo-seccion">Detalles del Producto</h6>
                            
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ingrese el nombre del producto" required>
                            </div>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción Detallada *</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Proporcione una descripción completa del producto..." required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3">
                                    <label for="precio" class="form-label">Precio de Venta ($) *</label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                                <div class="col-12 col-sm-6 mb-3">
                                    <label for="stock" class="form-label">Cantidad en Stock *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" min="0" placeholder="0" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoría del Producto *</label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="">Seleccione una categoría...</option>
                                    <option value="Hogar, Muebles y Jardín">Hogar, Muebles y Jardín</option>
                                    <option value="Hogar, Patio y Jardín">Hogar, Patio y Jardín</option>
                                    <option value="Joyas y Relojes">Joyas y Relojes</option>
                                    <option value="Souvenirs, Cotillón y Fiestas">Souvenirs, Cotillón y Fiestas</option>
                                    <option value="Electrónicos">Electrónicos</option>
                                    <option value="Ropa y Accesorios">Ropa y Accesorios</option>
                                    <option value="Deportes y Fitness">Deportes y Fitness</option>
                                    <option value="Libros y Revistas">Libros y Revistas</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="publicado" name="publicado" checked>
                                    <label class="form-check-label" for="publicado"> Activar producto </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center botones-container">
                            <button type="submit" name="enviar_producto" class="boton-registrar">Registrar Producto</button>
                            <a href="index.php" class="boton-regresar">Regresar al Inicio</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    .alerta.error {
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