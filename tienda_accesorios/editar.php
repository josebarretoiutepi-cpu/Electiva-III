<?php
session_start();
if(!isset($_SESSION['usuario'])) header("Location: login.php");

include 'conexion.php';

$id = $_GET['id'] ?? null;
$error = '';
$row = null;

if ($id && is_numeric($id)) {
    $stmt_select = $conn->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $row = $result->fetch_assoc();
    $stmt_select->close();

    if (!$row) {
        header("Location: catalogo.php"); 
        exit();
    }
} else {
    header("Location: catalogo.php"); 
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock']; 

    $stmt_update = $conn->prepare("UPDATE productos SET nombre=?, precio=?, stock=? WHERE id=?");
    
    $stmt_update->bind_param("sdii", $nombre, $precio, $stock, $id);
    
    if ($stmt_update->execute()) {
        header("Location: catalogo.php");
        exit();
    } else {
        $error = "Error al actualizar: " . $stmt_update->error;
    }
    $stmt_update->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Accesorio: <?= htmlspecialchars($row['nombre']) ?></h4>
                </div>
                <div class="card-body">
                    <?php if(!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($row['nombre']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio ($):</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="<?= htmlspecialchars($row['precio']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock:</label>
                            <input type="number" class="form-control" id="stock" name="stock" value="<?= htmlspecialchars($row['stock']) ?>" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Actualizar</button>
                            <a href="catalogo.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>