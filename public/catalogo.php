<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../include/conexion.php';
require_once __DIR__ . '/../include/auth.php'; 
require_once __DIR__ . '/../classes/producto.php';

$db = new ConexionBD();
$pdo = $db->conectar();

$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : '';

try {
    if ($categoriaSeleccionada) {
        
        $stmt = $pdo->prepare("SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id WHERE p.categoria_id = :cat");
        $stmt->execute([':cat' => $categoriaSeleccionada]);
    } else {
        $stmt = $pdo->query("SELECT p.*, c.nombre AS categoria_nombre FROM productos p INNER JOIN categorias c ON p.categoria_id = c.id");
    }
    $repuestos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $categoriasStmt = $pdo->query("SELECT * FROM categorias");
    $categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $repuestos = [];
    $categorias = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autopartes &amp; Repuestos "Don Pancho" | Honduras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <?php require __DIR__ . '/../include/navbar.php'; ?>
    
    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <h1 class="fw-bold">Catálogo Interactivo de Repuestos</h1>
            <p class="text-muted">Piezas originales y de reemplazo para vehículos japoneses, coreanos y americanos</p>
        </div>

        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <a href="catalogo.php" class="btn btn-outline-dark m-1 <?= empty($categoriaSeleccionada) ? 'active' : '' ?>">Todas las Categorías</a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="catalogo.php?categoria=<?= $cat['id'] ?>" class="btn btn-outline-dark m-1 <?= $categoriaSeleccionada == $cat['id'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php if (!empty($repuestos)): ?>
                <?php foreach ($repuestos as $rep): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            
                            <img src="../uploads/<?= htmlspecialchars($rep['fotografia']) ?>" class="card-img-top p-3" alt="<?= htmlspecialchars($rep['nombre']) ?>" style="height: 220px; object-fit: contain;">
                            <div class="card-body d-flex flex-column">
                                
                                <span class="badge bg-secondary mb-2 align-self-start"><?= htmlspecialchars($rep['categoria_nombre']) ?></span>
                                <h5 class="card-title"><?= htmlspecialchars($rep['nombre']) ?></h5>
                                <p class="card-text text-muted small"><?= htmlspecialchars($rep['descripcion'] ?? '') ?></p>
                                <div class="mt-auto">
                                    <p class="fs-5 fw-bold text-success mb-2">L. <?= number_format($rep['precio'], 2) ?></p>
                                    <p class="text-secondary small">Stock disponible: <strong><?= $rep['stock'] ?></strong> unidades</p>
                                    <a href="carrito.php?id=<?= $rep['id'] ?>" class="btn btn-primary w-100">Agregar al Carrito</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <div class="alert alert-warning" role="alert">
                        No hay repuestos disponibles en esta categoría actualmente.
                    </div>
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
                        <li><i class="bi bi-geo-alt-fill text-warning"></i> Tegucigalpa: Toncontín</li>
                        <li><i class="bi bi-geo-alt-fill text-warning"></i> Tegucigalpa: Suyapa</li>
                        <li><i class="bi bi-geo-alt-fill text-warning"></i> San Pedro Sula: Medina</li>
                        <li><i class="bi bi-geo-alt-fill text-warning"></i> La Ceiba: CA-13</li>
                    </ul>
                </div>

                <div class="col-12 col-md-4">
                    <h6>Enlaces</h6>
                    <ul class="list-unstyled small">
                        <li><a class="link-light link-underline-opacity-0" href="catalogo.php">Catálogo</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="kitTuneup.php">Kit Tune-Up</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="sucursales.php">Sucursales</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="login.php">Iniciar Sesión</a></li>
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