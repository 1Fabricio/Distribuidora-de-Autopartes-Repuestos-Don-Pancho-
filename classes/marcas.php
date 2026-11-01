<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/conexion.php';

class Marca
{
    private PDO $bd;

    public function __construct()
    {
        $this->bd = (new ConexionBD())->conectar();
    }

    public function todas(): array
    {
        return $this->bd->query("SELECT id, nombre FROM marcas ORDER BY id")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }
}