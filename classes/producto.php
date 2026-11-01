<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/conexion.php';

class Producto
{
    private PDO $bd;

    public function __construct()
    {
        $this->bd = (new ConexionBD())->conectar();
    }

    public function destacados(int $limite = 5): array
    {
        $sql = $sql = "SELECT p.id, p.nombre, p.precio, p.fotografia, c.nombre AS categoria
                        FROM productos p
                        INNER JOIN categorias c ON c.id = p.categoria_id
                        WHERE p.destacado = 1
                        ORDER BY p.id
                        LIMIT :limite";
        $stmt = $this->bd->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}