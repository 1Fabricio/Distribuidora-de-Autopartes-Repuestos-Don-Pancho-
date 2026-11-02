<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../classes/sucursal.php';

if (!function_exists('e')) {
    function e($valor): string
    {
        return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
    }
}

$modelo = new Sucursal();
$sucursales = $modelo->todas();
$u = usuarioActual();

$filtro = isset($_GET['sucursal']) ? (int) $_GET['sucursal'] : null;
if ($filtro !== null && $filtro <= 0) {
    $filtro = null;
}

$historial = [];
if ($u !== null) {
    $soloMios = in_array($u['rol'], ['admin', 'bodeguero'], true) ? null : (int) $u['id'];
    $historial = $modelo->historial($soloMios, $filtro);
}

$etiquetaFlete = [
    'retiro' => 'Retiro en mostrador',
    'moto'   => 'Moto express',
    'camion' => 'Camioncito',
];

$paginaActiva = 'sucursales';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursales | Autopartes &amp; Repuestos "Don Pancho"</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require __DIR__ . '/../include/navbar.php'; ?>

    <main class="container my-5">
        <div class="text-center mb-5">
            <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold bg-warning text-dark">Estamos cerca de ti</span>
            <h1 class="h2 fw-bold mt-2">Nuestras Sucursales</h1>
            <p class="small text-secondary">Visítanos en cualquiera de nuestras sedes en Honduras</p>
        </div>

        <div class="row g-4 mb-5">
            <?php foreach ($sucursales as $s): ?>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="card h-100 shadow border-0 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-shop fs-2 me-3 text-warning"></i>
                                <h2 class="h5 fw-bold mb-0"><?= e($s['nombre']) ?></h2>
                            </div>
                            <p class="mb-2"><i class="bi bi-geo-alt-fill me-2 text-warning"></i><?= e($s['ciudad']) ?></p>
                            <p class="mb-3"><i class="bi bi-pin-map-fill me-2 text-warning"></i><?= e($s['zona']) ?></p>
                            <?php if ($u !== null): ?>
                                <a href="sucursales.php?sucursal=<?= (int) $s['id'] ?>#historial"
                                    class="btn btn-sm btn-outline-dark rounded-pill">
                                    <i class="bi bi-receipt"></i> Ver compras
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="card shadow border-0 rounded-4 p-4" id="historial">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <h2 class="h4 fw-bold mb-0"><i class="bi bi-clipboard-data me-2"></i>Historial de compras</h2>
                <?php if ($filtro !== null): ?>
                    <a href="sucursales.php#historial" class="btn btn-sm btn-dark rounded-pill px-3">
                        <i class="bi bi-x-circle"></i> Quitar filtro
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($u === null): ?>
                <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                    <span class="badge text-bg-warning me-3">Sesión requerida</span>
                    <div>Inicia sesión para consultar tu historial de compras.
                        <a href="login.php" class="alert-link">Iniciar sesión</a>
                    </div>
                </div>
            <?php elseif (empty($historial)): ?>
                <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                    <span class="badge text-bg-warning me-3">Sin compras</span>
                    <div>Aún no hay compras registradas<?= $filtro !== null ? ' para esta sucursal' : '' ?>.</div>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Factura</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Sucursal</th>
                                <th>Detalle</th>
                                <th>Entrega</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($historial as $p): ?>
                                <tr>
                                    <td class="fw-bold"><?= $p['factura'] !== null ? 'F-' . str_pad((string) $p['factura'], 6, '0', STR_PAD_LEFT) : '—' ?></td>
                                    <td><?= e(date('d/m/Y H:i', strtotime($p['creado_en']))) ?></td>
                                    <td><?= e($p['nombre_cliente'] ?? '—') ?></td>
                                    <td><?= e($p['sucursal'] ?? 'Sin sucursal') ?></td>
                                    <td><?= e($p['detalle'] ?? '') ?></td>
                                    <td><span class="badge rounded-pill bg-warning text-dark"><?= e($etiquetaFlete[$p['flete_modalidad']] ?? $p['flete_modalidad']) ?></span></td>
                                    <td class="text-end fw-bold">L. <?= number_format((float) $p['total'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="py-5 mt-auto border-top bg-dark text-light">
        <div class="container">
            <div class="row g-4">

                <div class="col-12 col-md-4">
                    <h5><i class="bi bi-gear-wide-connected text-warning"></i> Autopartes &amp; Repuestos "Don Pancho"</h5>
                    <p class="small text-secondary">
                        Distribuidora de piezas originales y de reemplazo para vehículos japoneses, coreanos y americanos en Honduras.
                    </p>
                </div>

                <div class="col-12 col-md-4">
                    <h6>Sucursales</h6>
                    <ul class="list-unstyled small">
                        <?php foreach ($sucursales as $s): ?>
                            <li><i class="bi bi-geo-alt-fill text-warning"></i> <?= e($s['ciudad']) ?>: <?= e($s['zona']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="col-12 col-md-4">
                    <h6>Enlaces</h6>
                    <ul class="list-unstyled small">
                        <li><a class="link-light link-underline-opacity-0" href="catalogo.php">Catálogo</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="kitTune.php">Kit Tune-Up</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="sucursales.php">Sucursales</a></li>
                        <?php if ($u === null): ?>
                            <li><a class="link-light link-underline-opacity-0" href="login.php">Iniciar Sesión</a></li>
                        <?php else: ?>
                            <li><a class="link-light link-underline-opacity-0" href="logout.php">Cerrar Sesión</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary mt-4">
            <div class="text-center small text-secondary">
                &copy; 2026 Autopartes &amp; Repuestos "Don Pancho" — Honduras.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>