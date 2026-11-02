<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/conexion.php';

class Sucursal
{
    private PDO $bd;

    public function __construct()
    {
        $this->bd = (new ConexionBD())->conectar();
    }

    public function todas(): array
    {
        return $this->bd->query("SELECT id, nombre, ciudad, zona FROM sucursales ORDER BY id")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function historial(?int $usuarioId = null, ?int $sucursalId = null): array
    {
        $sql = "SELECT p.id, p.creado_en, p.flete_modalidad, p.total,
                       s.nombre AS sucursal,
                       f.id AS factura, f.nombre_cliente,
                       GROUP_CONCAT(CONCAT(d.cantidad, ' x ', d.descripcion) SEPARATOR ', ') AS detalle
                FROM pedidos p
                LEFT JOIN sucursales s ON s.id = p.sucursal_id
                LEFT JOIN facturas f ON f.pedido_id = p.id
                LEFT JOIN pedido_detalle d ON d.pedido_id = p.id
                WHERE 1 = 1";
        $params = [];

        if ($usuarioId !== null) {
            $sql .= " AND p.usuario_id = :usuario";
            $params[':usuario'] = $usuarioId;
        }
        if ($sucursalId !== null) {
            $sql .= " AND p.sucursal_id = :sucursal";
            $params[':sucursal'] = $sucursalId;
        }

        $sql .= " GROUP BY p.id, p.creado_en, p.flete_modalidad, p.total, s.nombre, f.id, f.nombre_cliente
                  ORDER BY p.creado_en DESC";

        $stmt = $this->bd->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}