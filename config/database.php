<?php
// config/database.php

// ✅ FIX: Use __DIR__ to point to the current directory (config/)
require_once __DIR__ . '/TenantManager.php';

class Database {
    private $host = "127.0.0.1";
    private $db_name = "educacion_plus";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Create connection
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Set attributes
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("set names utf8mb4");
            
            // ✅ Initialize Multi-Tenant Manager automatically
            TenantManager::resolve($this->conn);
            
        } catch(PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
        return $this->conn;
    }
    
    // ✅ Helper method to apply tenant filter
    public function tenantQuery($sql) {
        $tenantId = TenantManager::getId();
        if (!$tenantId) return $sql; // Fallback if not multi-tenant
        
        // Add filter based on table structure
        if (stripos($sql, 'WHERE') !== false) {
            return $sql . " AND id_institucion = $tenantId";
        } else {
            return $sql . " WHERE id_institucion = $tenantId";
        }
    }
}
?>