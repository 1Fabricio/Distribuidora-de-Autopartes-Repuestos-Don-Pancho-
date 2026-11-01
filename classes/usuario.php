<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/conexion.php';

class Usuario
{
    private PDO $bd;

    public function __construct()
    {
        $this->bd = (new ConexionBD())->conectar();
    }

    public function autenticar(string $usuario, string $clave): ?array
    {
        $stmt = $this->bd->prepare(
            "SELECT id, nombre, usuario, password_hash, rol
               FROM usuarios
              WHERE usuario = :usuario AND activo = 1
              LIMIT 1"
        );
        $stmt->execute([':usuario' => $usuario]);
        $fila = $stmt->fetch();

        if (!$fila || !password_verify($clave, $fila['password_hash'])) {
            return null;
        }

        return [
            'id'      => (int) $fila['id'],
            'nombre'  => $fila['nombre'],
            'usuario' => $fila['usuario'],
            'rol'     => $fila['rol'],
        ];
    }

    public function existeUsuario(string $usuario): bool
    {
        $stmt = $this->bd->prepare("SELECT 1 FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->execute([':usuario' => $usuario]);
        return (bool) $stmt->fetchColumn();
    }

    public function registrarCliente(string $nombre, string $usuario, string $clave): bool
    {
        $hash = password_hash($clave, PASSWORD_DEFAULT);

        try {
            $stmt = $this->bd->prepare(
                "INSERT INTO usuarios (nombre, usuario, password_hash, rol)
                 VALUES (:nombre, :usuario, :hash, 'cliente')"
            );
            return $stmt->execute([
                ':nombre'  => $nombre,
                ':usuario' => $usuario,
                ':hash'    => $hash,
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }
}