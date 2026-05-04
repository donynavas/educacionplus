<?php
// config/TenantManager.php

class TenantManager {
    private static $currentTenant = null;

    /**
     * Detecta la institución basándose en el subdominio
     */
    public static function resolve($db) {
    if (self::$currentTenant) return self::$currentTenant;

    $host = $_SERVER['HTTP_HOST'];
    $parts = explode('.', $host);
    $subdomain = $parts[0];

    // Si es localhost o IP directa, permitir acceso sin institución
    if ($subdomain === 'localhost' || $subdomain === '127.0.0.1') {
        // Solo requerir institución si NO es página de bienvenida o login
        $currentPage = basename($_SERVER['PHP_SELF']);
        if (!in_array($currentPage, ['welcome.php', 'login.php', 'index.php'])) {
            // Redirigir a welcome si intenta acceder a otra página sin subdominio
            header("Location: welcome.php");
            exit;
        }
        self::$currentTenant = null;
        return null;
    }

    try {
        $stmt = $db->prepare("SELECT * FROM tbl_institucion WHERE subdominio = :sub");
        $stmt->execute([':sub' => $subdomain]);
        $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tenant) {
            die("Error: Institución no encontrada para el subdominio '$subdomain'.");
        }
        
        self::$currentTenant = $tenant;
    } catch (Exception $e) {
        self::$currentTenant = null;
    }

    return self::$currentTenant;
}
    /**
     * Retorna el ID de la institución actual
     */
    public static function getId() {
        return self::$currentTenant['id'] ?? null;
    }
    
    /**
     * Retorna el nombre de la institución
     */
    public static function getName() {
        return self::$currentTenant['nombre_ce'] ?? 'Educación Plus';
    }
}
?>