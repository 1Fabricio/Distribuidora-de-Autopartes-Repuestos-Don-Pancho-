<?php
declare(strict_types=1);

class ConexionBD
{
    private string $host    = "localhost";
    private string $db      = "don_pancho"; 
    private string $user    = "root";       
    private string $pass    = "";            
    private string $charset = "utf8mb4";

    private static ?PDO $pdo = null;

    public function conectar(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        // Cadena DSN
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";

        $opciones = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            self::$pdo = new PDO($dsn, $this->user, $this->pass, $opciones);
            return self::$pdo;

        } catch (PDOException $e) {
            error_log("Fallo crítico en conexión BD: " . $e->getMessage());
            throw new PDOException("No se pudo conectar a la base de datos.", (int)$e->getCode());
        }
    }
}