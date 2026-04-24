<?php
session_start();
include 'config/database.php';

// ✅ 1. Guardar datos ANTES de destruir la sesión
$user_id = $_SESSION['user_id'] ?? null;
$rol = $_SESSION['rol'] ?? null;
$nombre_usuario = $_SESSION['nombre_usuario'] ?? 'Desconocido';

// ✅ 2. Registrar log de logout SOLO si el usuario existe en BD
if ($user_id && $rol) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        // Verificar que el usuario aún existe antes de insertar el log
        $check = $db->prepare("SELECT id FROM tbl_usuario WHERE id = :id AND estado = 1");
        $check->execute([':id' => $user_id]);
        
        if ($check->rowCount() > 0) {
            // ✅ Insertar log de actividad de logout
            $query = "INSERT INTO tbl_logs_actividad (id_usuario, accion, descripcion, ip_address, user_agent, fecha) 
                      VALUES (:id_usuario, 'logout', 'Cierre de sesión', :ip, :agent, NOW())";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':id_usuario' => $user_id,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                ':agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255)
            ]);
        }
        // Si el usuario no existe, simplemente no registramos el log (sin error)
        
    } catch (PDOException $e) {
        // ✅ En producción: registrar en log del servidor, no mostrar al usuario
        error_log("Error al registrar logout: " . $e->getMessage());
        // No interrumpimos el logout por un error de logging
    } catch (Exception $e) {
        error_log("Error general en logout: " . $e->getMessage());
    }
}

// ✅ 3. Destruir sesión de forma segura
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// ✅ 4. Redirigir al login
header("Location: login.php?logout=success");
exit;
?>