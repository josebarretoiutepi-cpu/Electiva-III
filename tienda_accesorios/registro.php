<?php
include 'conexion.php'; 

$error = '';
$exito = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario']);
    $email   = trim($_POST['email']); 
    $clave   = $_POST['clave'];
    $clave_confirm = $_POST['clave_confirm'];

    if (strlen($clave) < 6) {
        $error = "La contraseña debe tener **al menos 6 caracteres**.";
    } elseif ($clave !== $clave_confirm) {
        $error = "Las contraseñas no coinciden.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo electrónico es inválido.";
    } elseif (empty($usuario) || empty($email)) {
        $error = "Todos los campos son obligatorios.";
    } else {
    
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE usuario = ? OR email = ?");
        $stmt->bind_param("ss", $usuario, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "El nombre de usuario o el correo electrónico ya están en uso.";
        } else {
        
            $clave_hash = md5($clave);
            
            $stmt_insert = $conn->prepare("INSERT INTO usuarios (usuario, email, clave) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $usuario, $email, $clave_hash);

            if ($stmt_insert->execute()) {
                $exito = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
            } else {
                $error = "Error al registrar el usuario: " . $conn->error;
            }
            $stmt_insert->close();
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tienda Accesorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .registro-container { 
            max-width: 450px; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3); 
        }
    </style>
</head>
<body>
<div class="container">
    <div class="registro-container p-4 bg-white shadow rounded mx-auto">
        <h2 class="text-center mb-4 text-primary">✍️ Crear Nueva Cuenta</h2>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if (!empty($exito)): ?>
            <div class="alert alert-success" role="alert"><?= $exito ?> <a href="login.php" class="alert-link">Ir a Iniciar Sesión</a></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="usuario" class="form-label">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?= isset($usuario) ? htmlspecialchars($usuario) : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="clave" class="form-label">Contraseña (Mínimo 6 caracteres):</label>
                <input type="password" class="form-control" id="clave" name="clave" required>
            </div>
            <div class="mb-3">
                <label for="clave_confirm" class="form-label">Confirmar Contraseña:</label>
                <input type="password" class="form-control" id="clave_confirm" name="clave_confirm" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100 mt-3">Registrarme</button>
            <p class="text-center mt-3"><a href="login.php" class="text-secondary">¿Ya tienes cuenta? Iniciar Sesión</a></p>
        </form>
    </div>
</div>
</body>
</html>