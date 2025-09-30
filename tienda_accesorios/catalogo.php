<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
include 'conexion.php';

if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM productos WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: catalogo.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock  = $_POST['stock'];
    
    $stmt = $conn->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
    $stmt->bind_param("sdi", $nombre, $precio, $stock); // s=string, d=double, i=integer
    $stmt->execute();
    $stmt->close();
    
    header("Location: catalogo.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - Tienda Accesorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f4f7f6; }
        .container { max-width: 90%; margin-top: 30px; }
    </style>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-light bg-white rounded shadow-sm p-3 mb-4">
        <span class="navbar-brand mb-0 h1 text-primary"> Gestión de Accesorios</span>
        <div class="d-flex align-items-center">
            <span class="me-3">Bienvenido, <strong><?= htmlspecialchars($_SESSION["usuario"]); ?></strong></span>
            <a href="logout.php" class="btn btn-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
        </div>
    </nav>
    
    <h2 class="text-center mb-4 text-secondary">Catálogo de Productos</h2>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-plus-lg"></i> Agregar Nuevo Accesorio</h5>
        </div>
        <div class="card-body">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="precio" step="0.01" placeholder="Precio" required>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" name="stock" placeholder="Stock" required>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" name="agregar" class="btn btn-success"><i class="bi bi-save"></i> Agregar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm bg-white rounded">
            <thead class="table-dark">
                <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th class="text-center">Acción</th></tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM productos");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td>$<?= number_format($row['precio'], 2) ?></td>
                    <td><span class="badge bg-<?= $row['stock'] > 10 ? 'success' : ($row['stock'] > 5 ? 'warning' : 'danger') ?>"><?= $row['stock'] ?></span></td>
                    <td class="text-center">
                        <a class="btn btn-warning btn-sm me-2" href="editar.php?id=<?= $row['id'] ?>" title="Editar"><i class="bi bi-pencil-square"></i></a>
                        <a class="btn btn-danger btn-sm" href="catalogo.php?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar \'<?= htmlspecialchars($row['nombre']) ?>\'?');" title="Eliminar"><i class="bi bi-trash-fill"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>