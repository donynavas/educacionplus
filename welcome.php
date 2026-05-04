<?php
session_start();

// Si ya está logueado, redirigir según rol
if (isset($_SESSION['rol'])) {
    switch ($_SESSION['rol']) {
        case 'superadmin':
            header("Location: superadmin/dashboard.php");
            break;
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
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educación Plus - Plataforma Educativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Navbar superior */
        .top-navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary) !important;
            font-size: 1.5rem;
        }
        
        .navbar-brand i {
            color: var(--secondary);
            margin-right: 10px;
        }
        
        .btn-login {
            background: var(--secondary);
            color: white;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-login:hover {
            background: var(--primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            color: white;
        }
        
        /* Hero Section */
        .hero-section {
            padding-top: 120px;
            padding-bottom: 80px;
            color: white;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: transform 0.3s;
            height: 100%;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--secondary), var(--primary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }
        
        .feature-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .feature-text {
            color: #666;
            line-height: 1.6;
        }
        
        /* Stats Section */
        .stats-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 60px 0;
            margin: 40px 0;
            border-radius: 20px;
        }
        
        .stat-item {
            text-align: center;
            color: white;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Footer */
        .footer {
            background: rgba(0, 0, 0, 0.3);
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-top: 60px;
        }
        
        @media (max-width: 768px) {
            .hero-title { font-size: 2rem; }
            .hero-subtitle { font-size: 1rem; }
        }
    </style>
</head>
<body>
    <!-- Navbar Superior -->
    <nav class="top-navbar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="#" class="navbar-brand">
                        <i class="fas fa-graduation-cap"></i>
                        Educación Plus
                    </a>
                </div>
                <div>
                    <a href="login.php" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="hero-title">
                        Bienvenido a Educación Plus
                    </h1>
                    <p class="hero-subtitle">
                        La plataforma educativa integral que transforma la manera de enseñar y aprender. 
                        Gestión académica, seguimiento estudiantil y herramientas digitales en un solo lugar.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                    
                        <a href="#caracteristicas" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">
                            <i class="fas fa-info-circle me-2"></i>
                            Conocer Más
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <div class="container">
        <div class="stats-section">
            <div class="row">
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Instituciones</span>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4 mb-md-0">
                    <div class="stat-item">
                        <span class="stat-number">10k+</span>
                        <span class="stat-label">Estudiantes</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Docentes</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">98%</span>
                        <span class="stat-label">Satisfacción</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="caracteristicas" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-white mb-3">Características Principales</h2>
                <p class="text-white-50">Todo lo que necesitas para gestionar tu institución educativa</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="feature-title">Gestión de Estudiantes</h4>
                        <p class="feature-text">
                            Control completo de matrículas, historial académico, asistencia y seguimiento individualizado.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h4 class="feature-title">Aula Virtual</h4>
                        <p class="feature-text">
                            Videollamadas, pizarra interactiva, chat en tiempo real y asignación de actividades.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h4 class="feature-title">Calificaciones</h4>
                        <p class="feature-text">
                            Sistema de evaluación flexible, reportes automáticos y boletines personalizados.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="feature-title">Bienestar Estudiantil</h4>
                        <p class="feature-text">
                            Seguimiento psicológico, alertas tempranas y reportes de comportamiento.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4 class="feature-title">Reportes y Estadísticas</h4>
                        <p class="feature-text">
                            Análisis de rendimiento, gráficos interactivos y exportación de datos.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="feature-title">Multi-Tenant Seguro</h4>
                        <p class="feature-text">
                            Cada institución con sus datos aislados y protegidos con encriptación.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-2">&copy; <?php echo date('Y'); ?> Educación Plus. Todos los derechos reservados.</p>
            <small class="text-white-50">Plataforma Educativa Multi-Tenant</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>