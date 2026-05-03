<?php
session_start();
require '../config/db_global.php'; // ← Usa conexión global, NO tenant

// Si ya es superadmin, redirigir
if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
    header("Location: dashboard.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    $db = (new DatabaseGlobal())->getConnection();
    
    // Buscar usuario superadmin (sin filtro de institución)
    $stmt = $db->prepare("SELECT * FROM tbl_usuario WHERE usuario = ? AND rol = 'superadmin'");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['rol'] = 'superadmin';
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Credenciales incorrectas o no tienes permisos de Super Admin.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #2c3e50; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-box { background: white; padding: 40px; border-radius: 10px; width: 400px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    </style>
</head>
<body>
    <div class="login-box">
        <h3 class="text-center mb-4">🛡️ Acceso Restringido<br><small class="text-muted">Super Admin</small></h3>
        <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar al Sistema</button>
            <a href="../login.php" class="d-block text-center mt-3 text-muted">Volver al Login de Colegios</a>
        </form>
    </div>
</body>
</html>