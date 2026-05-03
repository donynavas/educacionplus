<?php
session_start();

// Incluir configuración y gestor de tenants
require_once 'config/database.php';
require_once 'config/TenantManager.php';

// Inicializar conexión y resolver tenant automáticamente
$db = (new Database())->getConnection();
$tenant = TenantManager::resolve($db);
$tenantId = TenantManager::getId();
$tenantName = TenantManager::getName();

$error = '';

// ===== PROCESAR LOGIN =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($usuario) || empty($password)) {
        $error = 'Por favor complete todos los campos.';
    } else {
        try {
            // 🔒 Consulta segura: Filtra por usuario Y por institución
            $stmt = $db->prepare("
                SELECT u.id, u.usuario, u.password, u.rol, u.estado, 
                       CONCAT(p.primer_nombre, ' ', p.primer_apellido) as nombre_completo
                FROM tbl_usuario u
                JOIN tbl_persona p ON u.id_persona = p.id
                WHERE u.usuario = :usuario 
                  AND u.id_institucion = :id_inst 
                  AND u.estado = 1
                LIMIT 1
            ");
            
            $stmt->execute([
                ':usuario' => $usuario,
                ':id_inst' => $tenantId
            ]);
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // ✅ Login exitoso
                session_regenerate_id(true); // Previene fijación de sesión
                
                $_SESSION['user_id']          = $user['id'];
                $_SESSION['usuario']          = $user['usuario'];
                $_SESSION['rol']              = $user['rol'];
                $_SESSION['nombre']           = $user['nombre_completo'];
                $_SESSION['id_institucion']   = $tenantId;
                $_SESSION['tenant_subdominio']= $tenant['subdominio'];
                $_SESSION['last_login']       = date('Y-m-d H:i:s');

                //  Redirección basada en rol
                switch ($user['rol']) {
                    case 'admin':
                    case 'director':
                    case 'orientador':
                        header("Location: modules/admin/gestionar_estudiantes.php");
                        break;
                    case 'profesor':
                        header("Location: modules/profesor/dashboard.php");
                        break;
                    case 'estudiante':
                        header("Location: modules/estudiante/dashboard.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos, o su cuenta está inactiva.';
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error = 'Error de conexión. Intente nuevamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Iniciar Sesión - <?= htmlspecialchars($tenantName) ?></title>
    
    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root { --primary: #2c3e50; --secondary: #3498db; }
        body { 
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .login-card { 
            max-width: 420px; 
            width: 100%; 
            border-radius: 16px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.25); 
            overflow: hidden;
        }
        .login-header { 
            text-align: center; 
            padding: 35px 20px 20px; 
            background: white; 
        }
        .login-header i { 
            font-size: 3.5rem; 
            color: var(--secondary); 
            margin-bottom: 12px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }
        .login-body { 
            padding: 25px 35px 35px; 
            background: white; 
        }
        .form-control:focus { 
            border-color: var(--secondary); 
            box-shadow: 0 0 0 0.25rem rgba(52,152,219,0.25); 
        }
        .btn-primary { 
            background: var(--secondary); 
            border: none; 
            padding: 12px; 
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary:hover { 
            background: #2980b9; 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52,152,219,0.4);
        }
        @media (max-width: 480px) {
            .login-card { margin: 15px; }
            .login-body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-graduation-cap"></i>
            <h4 class="mb-1 fw-bold">Educación Plus</h4>
            <p class="text-muted small mb-0"><?= htmlspecialchars($tenantName) ?></p>
        </div>
        
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger py-2 small text-center mb-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" autocomplete="off" novalidate>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                        <input type="text" name="usuario" class="form-control" placeholder="Ingrese su usuario" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i> Ingresar al Sistema
                </button>
            </form>
            
            <div class="text-center mt-4 pt-3 border-top">
                <small class="text-muted">© <?= date('Y') ?> Educación Plus SaaS • Multi-Tenant</small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>