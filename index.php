<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];

// Redirección según rol
switch ($rol) {
    case 'profesor':
        include 'modules/profesor/profesor_dashboard.php';
        break;
    case 'estudiante':
        include 'modules/estudiante/estudiante_dashboard.php';
        break;
    case 'admin':
    case 'director':
        include 'modules/dashboard/admin_dashboard.php';
        break;
    default:
        session_destroy();
        header("Location: login.php");
}
?>