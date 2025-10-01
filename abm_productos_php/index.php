<?php
session_start();

$error = false;
$config = include 'config.php';

$host = $config['db']['host'];
$dbname = $config['db']['name'];
$user = $config['db']['user'];
$pass = $config['db']['pass'];
$options = $config['db']['options'];

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $conexion = new PDO($dsn, $user, $pass, $options);

    if (isset($_POST['buscar']) && !empty(trim($_POST['buscar']))) {
        $consultaSQL = "SELECT * FROM productos WHERE titulo LIKE :termino ORDER BY id_producto DESC";
        $sentencia = $conexion->prepare($consultaSQL);
        $termino = '%' . trim($_POST['buscar']) . '%';
        $sentencia->bindParam(':termino', $termino);
    } else {
        $consultaSQL = "SELECT * FROM productos ORDER BY id_producto DESC";
        $sentencia = $conexion->prepare($consultaSQL);
    }
    
    $sentencia->execute();
    $productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    $mensaje_error = $error->getMessage();
}

$titulo = isset($_POST['buscar']) && !empty(trim($_POST['buscar'])) 
    ? 'Productos (' . htmlspecialchars($_POST['buscar']) . ')' 
    : 'Lista de Productos';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Productos</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
            <span class="navbar-brand">Sistema de Productos</span>
        </div>
    </nav>

    <?php if (isset($mensaje_error)): ?>
    <div class="container mt-3">
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong> <?= htmlspecialchars($mensaje_error) ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h2 class="titulo-seccion"><?= $titulo ?></h2>
            </div>
            <a href="crear.php" class="btn boton">Agregar Producto</a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="post" class="d-flex gap-2">
                    <input type="text" name="buscar" class="form-control flex-grow-1" 
                        placeholder="Buscar producto por título..." 
                        value="<?= isset($_POST['buscar']) ? htmlspecialchars($_POST['buscar']) : '' ?>">
                    <button type="submit" class="btn btn-buscar">Buscar</button>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-limpiar">Limpiar</a>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="cabecera-tabla">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($productos && count($productos) > 0): ?>
                                <?php foreach ($productos as $producto): ?>
                                <tr>
                                    <td><?= htmlspecialchars($producto["id_producto"]) ?></td>
                                    <td><?= htmlspecialchars($producto["titulo"]) ?></td>
                                    <td class="precio">$<?= number_format($producto['precio'], 2, ',', '.') ?></td>
                                    <td class="<?= $producto['stock'] < 10 ? 'stock-bajo' : '' ?>">
                                        <?= $producto['stock'] ?> unid.
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($producto['categoria']) ?></span>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php if ($producto['publicado']): ?> <span class="badge bg-success">Activo</span>
                                        <?php else: ?> <span class="badge bg-warning">Pausado</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <div>
                                            <a href="ver.php?id=<?= $producto["id_producto"] ?>" class="btn btn-sm btn-info btn-accion">Ver</a>
                                            <a href="editar.php?id=<?= $producto["id_producto"] ?>" class="btn btn-sm btn-warning btn-accion">Editar</a>
                                            <a href="borrar.php?id=<?= $producto["id_producto"] ?>" class="btn btn-sm btn-danger btn-accion" 
                                            onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <em style="color: gray;">No hay productos registrados</em>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        background-color: #FFFFFF;
    }

    .boton {
        background-color: #C97B63;
        color: #FFFFFF;
        border: 2px solid #C97B63;
        padding: 8px 20px;
        border-radius: 6px !important;
        font-weight: 500;
    }

    .boton:hover {
        background-color: #D08970;
        border-color: #D08970;
        color: #FFFFFF;
    }

    .btn-buscar {
        background-color: #908A77;
        color: #FFFFFF;
        border: 2px solid #908A77;
    }

    .btn-buscar:hover {
        background-color: #7A7463;
        border-color: #7A7463;
        color: #FFFFFF;
    }

    .btn-limpiar {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }

    .btn-limpiar:hover {
        background-color: #5a6268;
        border-color: #545b62;
        color: white;
    }

    .table {
        table-layout: auto !important;
        width: 100%;
    }

    .table .cabecera-tabla th {
        background-color: #908A77 !important; 
        color: #FFFFFF !important;
        text-align: left;
        padding: 1rem;
    }

    .titulo-seccion {
        color: #908A77;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        text-align: center;

    }

    .precio { 
        font-weight: bold; 
        color: #C97B63; 
    }
    
    .stock-bajo { 
        color: #dc3545; 
        font-weight: bold; 
    }

    .btn-accion {
        margin: 2px 6px;
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 6px !important;
    }

    .btn-info {
        background-color: #908A77 !important;
        border-color: #908A77 !important;
        color: white !important;
    }

    .btn-info:hover {
        background-color: #7A7463 !important;
        border-color: #7A7463 !important;
        color: white !important;
    }

    .btn-warning {
        background-color: #C97B63 !important;
        border-color: #C97B63 !important;
        color: white !important;
    }

    .btn-warning:hover {
        background-color: #B86A52 !important;
        border-color: #B86A52 !important;
        color: white !important;
    }

    .table td {
    width: auto;
    white-space: nowrap;
    }

    .table td:nth-child(2) {
        width: auto;
        max-width: none;
        white-space: normal; 
        word-wrap: break-word;
    }

    .table td:nth-child(6),
    .table td:nth-child(7) {
        text-align: right;
    }

</style>
</body>
</html>