<?php
session_start();
include 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();
    
    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    $query = "SELECT id, password, rol, estado FROM tbl_usuario WHERE usuario = :usuario";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password']) && $row['estado'] == 1) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['nombre'] = $row['primer_nombre'] ?? 'Usuario';
            
            // Registrar log de acceso
            $logQuery = "INSERT INTO tbl_logs_actividad (id_usuario, accion, ip_address) 
                        VALUES (:id, 'Login Exitoso', :ip)";
            $logStmt = $db->prepare($logQuery);
            $logStmt->bindParam(':id', $row['id']);
            $logStmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
            $logStmt->execute();
            
            header("Location: index.php");
            exit;
        } else {
            $error = "Contraseña incorrecta o usuario inactivo.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Educación Plus</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --dark-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --sidebar-width: 250px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            z-index: -1;
            animation: gradientShift 15s ease infinite;
            background-size: 400% 400%;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(255,255,255,0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }
        
        /* Particles Effect */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: particleFloat 15s infinite linear;
        }
        
        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }
        
        /* Main Container */
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }
        
        /* Two Column Layout */
        .login-card {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: white;
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: cardSlideIn 0.6s ease-out;
        }
        
        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Left Column - Branding */
        .login-brand {
            flex: 1;
            background: var(--primary-gradient);
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 4s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .brand-logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            animation: logoBounce 2s ease infinite;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        
        @keyframes logoBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .brand-logo i {
            font-size: 3rem;
            color: #667eea;
        }
        
        .brand-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        
        .brand-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }
        
        .brand-features {
            list-style: none;
            text-align: left;
            width: 100%;
            max-width: 300px;
            position: relative;
            z-index: 1;
        }
        
        .brand-features li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            animation: featureSlideIn 0.5s ease forwards;
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .brand-features li:nth-child(1) { animation-delay: 0.2s; }
        .brand-features li:nth-child(2) { animation-delay: 0.4s; }
        .brand-features li:nth-child(3) { animation-delay: 0.6s; }
        .brand-features li:nth-child(4) { animation-delay: 0.8s; }
        
        @keyframes featureSlideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .brand-features i {
            color: #4ade80;
            font-size: 1.1rem;
        }
        
        /* Right Column - Form */
        .login-form {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .form-header {
            margin-bottom: 35px;
        }
        
        .form-header h3 {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 8px;
        }
        
        .form-header p {
            color: #666;
            font-size: 0.95rem;
        }
        
        /* Form Elements */
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
            font-size: 0.9rem;
        }
        
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .form-control:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .form-control.is-invalid {
            border-color: #e74c3c;
            animation: shake 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            transition: color 0.3s ease;
            z-index: 2;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
        
        .input-icon .form-control:focus + i,
        .input-icon .form-control:focus ~ i {
            color: #667eea;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
            z-index: 2;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e0e0e0;
            cursor: pointer;
        }
        
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        
        .form-check-label {
            font-size: 0.9rem;
            color: #666;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #764ba2;
            text-decoration: none;
        }
        
        /* Submit Button */
        .btn-login {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 14px 20px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-login .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        
        .btn-login.loading .spinner {
            display: inline-block;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Alert Messages */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: alertSlideIn 0.3s ease;
        }
        
        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-custom i {
            font-size: 1.2rem;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
        }
        
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
        }
        
        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #999;
            font-size: 0.9rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }
        
        .divider span {
            padding: 0 15px;
        }
        
        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }
        
        .login-footer p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .social-btn {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            border: 2px solid #e0e0e0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        
        .social-btn:hover {
            border-color: #667eea;
            color: #667eea;
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
        }
        
        .copyright {
            margin-top: 20px;
            font-size: 0.8rem;
            color: #999;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-card {
                max-width: 500px;
            }
            
            .login-brand {
                display: none;
            }
            
            .login-form {
                padding: 40px 30px;
            }
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 10px;
            }
            
            .login-card {
                max-width: 100%;
                border-radius: 20px;
            }
            
            .login-form {
                padding: 30px 25px;
            }
            
            .form-header h3 {
                font-size: 1.5rem;
            }
            
            .btn-login {
                padding: 12px 20px;
            }
        }
        
        /* Floating Elements */
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: floatShape 20s infinite linear;
        }
        
        .floating-shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-shape:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation-delay: -5s;
        }
        
        .floating-shape:nth-child(3) {
            width: 40px;
            height: 40px;
            bottom: 20%;
            left: 20%;
            animation-delay: -10s;
        }
        
        @keyframes floatShape {
            0% {
                transform: translateY(0) rotate(0deg) scale(1);
            }
            50% {
                transform: translateY(-30px) rotate(180deg) scale(1.1);
            }
            100% {
                transform: translateY(0) rotate(360deg) scale(1);
            }
        }
        
        /* Input Animation */
        .form-group.animate-in {
            animation: inputSlideIn 0.4s ease forwards;
            opacity: 0;
            transform: translateX(-20px);
        }
        
        .form-group.animate-in:nth-child(1) { animation-delay: 0.1s; }
        .form-group.animate-in:nth-child(2) { animation-delay: 0.2s; }
        .form-group.animate-in:nth-child(3) { animation-delay: 0.3s; }
        
        @keyframes inputSlideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Focus Ring Effect */
        .focus-ring {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 12px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s ease;
            box-shadow: 0 0 0 2px #667eea;
        }
        
        .form-control:focus ~ .focus-ring {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation"></div>
    
    <!-- Particles -->
    <div class="particles" id="particles"></div>
    
    <!-- Main Container -->
    <div class="login-container">
        <div class="login-card">
            <!-- Left Column: Branding -->
            <div class="login-brand">
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                <div class="floating-shape"></div>
                
                <div class="brand-logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                
                <h1 class="brand-title">Educación Plus</h1>
                <p class="brand-subtitle">Plataforma Integral de Gestión Educativa</p>
                
                <ul class="brand-features">
                    <li><i class="fas fa-check-circle"></i> Gestión de Calificaciones</li>
                    <li><i class="fas fa-check-circle"></i> Control de Asistencia</li>
                    <li><i class="fas fa-check-circle"></i> Exámenes en Línea</li>
                    <li><i class="fas fa-check-circle"></i> Comunicación con Padres</li>
                </ul>
            </div>
            
            <!-- Right Column: Login Form -->
            <div class="login-form">
                <div class="form-header">
                    <h3>¡Bienvenido de nuevo! 👋</h3>
                    <p>Ingresa tus credenciales para acceder al sistema</p>
                </div>
                
                <?php if(isset($error)): ?>
                <div class="alert-custom alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
                <?php endif; ?>
                
                <form method="POST" id="loginForm" novalidate>
                    <!-- Username Field -->
                    <div class="form-group animate-in">
                        <label for="usuario">Usuario o Email</label>
                        <div class="input-icon">
                            <input type="text" 
                                   name="usuario" 
                                   id="usuario" 
                                   class="form-control" 
                                   placeholder="Ingresa tu usuario"
                                   value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
                                   required
                                   autocomplete="username">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="invalid-feedback">Por favor ingresa tu usuario</div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-group animate-in">
                        <label for="password">Contraseña</label>
                        <div class="input-icon">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control" 
                                   placeholder="Ingresa tu contraseña"
                                   required
                                   autocomplete="current-password">
                            <i class="fas fa-lock"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Por favor ingresa tu contraseña</div>
                    </div>
                    
                    <!-- Remember & Forgot -->
                    <div class="form-options">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <a href="recuperar_password.php" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login w-100" id="loginBtn">
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </span>
                        <span class="spinner"></span>
                    </button>
                    <!-- Agregar esto en login.php, dentro de .login-footer, antes de </div> -->

<!-- Enlace a Registro -->
<div class="mt-3 pt-3 border-top">
    <p class="text-center mb-2" style="color: #666; font-size: 0.9rem;">
        ¿No tienes cuenta?
    </p>
    <a href="registro.php" class="btn btn-outline-primary w-100" style="border-radius: 12px; font-weight: 500;">
        <i class="fas fa-user-plus"></i> Crear Cuenta Nueva
    </a>
</div>
                </form>
                
                <!-- Divider -->
                <div class="divider">
                    <span>o continúa con</span>
                </div>
                
                <!-- Footer -->
                <div class="login-footer">
                    <p>¿Problemas para acceder?</p>
                    <div class="social-links">
                        <a href="#" class="social-btn" title="Soporte Técnico">
                            <i class="fas fa-headset"></i>
                        </a>
                        <a href="mailto:soporte@educacionplus.com" class="social-btn" title="Enviar Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                        <a href="https://wa.me/50312345678" class="social-btn" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                    <p class="copyright">
                        © <?= date('Y') ?> Educación Plus. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // ===== PARTICLES EFFECT =====
        function createParticles() {
            const container = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                container.appendChild(particle);
            }
        }
        
        // ===== PASSWORD TOGGLE =====
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // ===== FORM VALIDATION =====
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario');
            const password = document.getElementById('password');
            let isValid = true;
            
            // Validate username
            if (!usuario.value.trim()) {
                usuario.classList.add('is-invalid');
                isValid = false;
            } else {
                usuario.classList.remove('is-invalid');
            }
            
            // Validate password
            if (!password.value.trim()) {
                password.classList.add('is-invalid');
                isValid = false;
            } else {
                password.classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Shake animation for first invalid field
                const invalidField = document.querySelector('.is-invalid');
                if (invalidField) {
                    invalidField.focus();
                }
            } else {
                // Show loading state
                e.preventDefault();
                const btn = document.getElementById('loginBtn');
                btn.classList.add('loading');
                btn.disabled = true;
                
                // Submit form after animation
                setTimeout(() => {
                    this.submit();
                }, 1500);
            }
        });
        
        // Remove invalid state on input
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        // ===== ANIMATE INPUTS ON LOAD =====
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            
            // Trigger input animations
            setTimeout(() => {
                document.querySelectorAll('.form-group.animate-in').forEach((el, index) => {
                    setTimeout(() => {
                        el.style.opacity = '1';
                        el.style.transform = 'translateX(0)';
                    }, index * 100);
                });
            }, 300);
            
            // Auto-focus username field
            document.getElementById('usuario').focus();
            
            // Keyboard shortcut: Enter to submit
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && document.activeElement.tagName !== 'BUTTON') {
                    e.preventDefault();
                    document.getElementById('loginForm').requestSubmit();
                }
            });
        });
        
        // ===== RIPPLE EFFECT ON BUTTON =====
        document.querySelector('.btn-login').addEventListener('click', function(e) {
            if (this.classList.contains('loading')) return;
            
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background: rgba(255,255,255,0.3);
                transform: translate(-50%, -50%) scale(0);
                animation: rippleEffect 0.6s ease-out;
                pointer-events: none;
                left: ${x}px;
                top: ${y}px;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
        
        // Add ripple animation keyframes dynamically
        const style = document.createElement('style');
        style.textContent = `
            @keyframes rippleEffect {
                to {
                    transform: translate(-50%, -50%) scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>