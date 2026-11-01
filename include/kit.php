<?php
declare(strict_types=1);

class Kit {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function buscarOpcion(int $id, string $tipo): ?array {
        $sql = "SELECT id, nombre, costo FROM kit_opciones WHERE id = :id AND tipo = :tipo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id'   => $id,
            ':tipo' => $tipo
        ]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila === false) {
            return null;
        }
        return $fila;
    }

    public function calcular(array $seleccion): ?array {
        $items = [];
        $total = 0.0;

        $vehiculo   = $this->buscarOpcion((int)$seleccion['vehiculo'], 'vehiculo');
        $marca      = $this->buscarOpcion((int)$seleccion['marca'], 'marca_aceite');
        $viscosidad = $this->buscarOpcion((int)$seleccion['viscosidad'], 'viscosidad');

        if ($vehiculo === null || $marca === null || $viscosidad === null) {
            return null;
        }

        $items[] = [
            'nombre' => 'Servicio base - ' . $vehiculo['nombre'],
            'precio' => (float)$vehiculo['costo']
        ];
        $items[] = [
            'nombre' => 'Aceite ' . $marca['nombre'] . ' ' . $viscosidad['nombre'],
            'precio' => (float)$marca['costo']
        ];

        foreach ($seleccion['filtros'] as $id) {
            $filtro = $this->buscarOpcion((int)$id, 'filtro');
            if ($filtro === null) {
                return null;
            }
            $items[] = [
                'nombre' => $filtro['nombre'],
                'precio' => (float)$filtro['costo']
            ];
        }

        foreach ($seleccion['extras'] as $id) {
            $extra = $this->buscarOpcion((int)$id, 'extra');
            if ($extra === null) {
                return null;
            }
            $items[] = [
                'nombre' => $extra['nombre'],
                'precio' => (float)$extra['costo']
            ];
        }

        foreach ($items as $item) {
            $total = $total + $item['precio'];
        }

        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
?>