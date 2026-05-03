<?php
// config/TenantManager.php

class TenantManager {
    private static $currentTenant = null;

    /**
     * Detecta la institución basándose en el subdominio
     */
    public static function resolve($db) {
        // Si ya se resolvió, no volver a consultar
        if (self::$currentTenant) return self::$currentTenant;

        // 1. Obtener el host (ej: colegioA.localhost)
        $host = $_SERVER['HTTP_HOST'];
        $parts = explode('.', $host);
        $subdomain = $parts[0];

        // Si es localhost directo, usamos 'localhost' como subdominio por defecto para pruebas
        if ($subdomain === 'localhost' || $subdomain === '127.0.0.1') {
            $subdomain = 'localhost'; 
        }

        // 2. Buscar en la base de datos
        try {
            $stmt = $db->prepare("SELECT * FROM tbl_institucion WHERE subdominio = :sub");
            $stmt->execute([':sub' => $subdomain]);
            $tenant = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($tenant) {
                self::$currentTenant = $tenant;
            } else {
                // Si no encuentra la institución, detiene la carga para evitar errores de datos mezclados
                die("Error: Institución no encontrada para el subdominio '$subdomain'. <br>Por favor, configura tu archivo hosts o crea la institución en la base de datos.");
            }
        } catch (Exception $e) {
            die("Error de conexión al detectar institución: " . $e->getMessage());
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