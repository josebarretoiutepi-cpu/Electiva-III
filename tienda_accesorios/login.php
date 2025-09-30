<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identificador = $_POST['identificador'];
    $clave   = md5($_POST['clave']); 

    $stmt = $conn->prepare("SELECT usuario FROM usuarios WHERE (usuario = ? OR email = ?) AND clave = ?");
    $stmt->bind_param("sss", $identificador, $identificador, $clave);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $row['usuario'];
        header("Location: catalogo.php");
        exit();
    } else {
        $error = "Usuario/Correo o contraseña incorrectos.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Tienda Accesorios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #a8c0ff 0%, #3f2b96 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .login-box { 
            width: 100%; 
            max-width: 400px; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); 
        }
    </style>
</head>
<body>
<div class="login-box bg-white">
    <h2 class="text-center mb-4 text-primary"> Acceso a Tienda</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="identificador" class="form-label">Usuario o Correo</label>
            <input type="text" class="form-control" id="identificador" name="identificador" placeholder="Usuario o Email" required>
        </div>
        <div class="mb-3">
            <label for="clave" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingrese su contraseña" required>
        </div>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger text-center" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary w-100 mt-3">Ingresar</button>
        <p class="text-center mt-3">
            ¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a>
        </p>
    </form>
</div>
</body>
</html>