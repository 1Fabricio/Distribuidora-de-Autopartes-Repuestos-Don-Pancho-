<?php
 
session_start();
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../include/conexion.php';
 
$db = new ConexionBD();
$pdo = $db->conectar();
 
require_once __DIR__ . '/../include/kit.php';
$kitRepo = new Kit($pdo);
 
if (!isset($_SESSION['carrito_kits'])) {
    $_SESSION['carrito_kits'] = [];
}
 
if (isset($_GET['eliminar_kit'])) {
    $i = (int) $_GET['eliminar_kit'];
    unset($_SESSION['carrito_kits'][$i]);
    $_SESSION['carrito_kits'] = array_values($_SESSION['carrito_kits']);
    header('Location: carrito.php');
    exit;
}
 
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
 
if(isset($_GET['eliminar'])){
 
    $idEliminar = (int) $_GET['eliminar'];
 
    if(isset($_SESSION['carrito'][$idEliminar])){
 
        unset($_SESSION['carrito'][$idEliminar]);
 
    }
 
}
 
 
if (isset($_GET['id'])) {
 
    $id = (int) $_GET['id'];
 
    $stmt = $pdo->prepare("
        SELECT *
        FROM productos
        WHERE id = ?
    ");
 
    $stmt->execute([$id]);
 
    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if ($producto) {
 
        if (isset($_SESSION['carrito'][$id])) {
 
            $_SESSION['carrito'][$id]['cantidad']++;
 
        } else {
 
            $_SESSION['carrito'][$id] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
 
        }
 
    } 
    
 
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

    <main class="container my-5">
 
    <h2 class="mb-4">
        Carrito de Compras
    </h2>
 
    <?php if(empty($_SESSION['carrito']) && empty($_SESSION['carrito_kits'])): ?>
 
        <div class="alert alert-warning">
            No hay productos en el carrito.
        </div>
 
    <?php else: ?>
 
        <table class="table table-bordered">
 
            <thead>
 
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
 
                <?php
 
                $total = 0;
 
                foreach($_SESSION['carrito'] as $item):
 
                    $subtotal =
                    $item['precio']
                    *
                    $item['cantidad'];
 
                    $total += $subtotal;
 
                ?>
 
                <tr>
                    <td>
                        <?= htmlspecialchars($item['nombre']) ?>
                    </td>
                    <td>
                        L. <?= number_format($item['precio'],2) ?>
                    </td>
                    <td>
                        <?= $item['cantidad'] ?>
                    </td>
                    <td>
                        L. <?= number_format($subtotal,2) ?>
                    </td>
                     <td>
                     <a href="carrito.php?eliminar=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                     </td>
                </tr>
                <?php endforeach; ?>
 
                <?php foreach ($_SESSION['carrito_kits'] as $i => $sel):
                    $r = $kitRepo->calcular($sel);
                    if ($r === null) { continue; }
                    $total += $r['total'];
                ?>
                <tr>
                    <td>
                        <strong><i class="bi bi-wrench-adjustable"></i> Kit Tune-Up #<?= $i + 1 ?></strong>
                        <ul class="small text-muted mb-0">
                            <?php foreach ($r['items'] as $it): ?>
                                <li><?= htmlspecialchars($it['nombre']) ?> (L. <?= number_format($it['precio'], 2) ?>)</li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td>L. <?= number_format($r['total'], 2) ?></td>
                    <td>1</td>
                    <td>L. <?= number_format($r['total'], 2) ?></td>
                    <td><a href="carrito.php?eliminar_kit=<?= $i ?>" class="btn btn-danger btn-sm">Eliminar</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
 
        <div class="text-end">
            <h4>
                Total: L. <?= number_format($total,2) ?>
            </h4>
        </div>
    <?php endif; ?>
</main>
 
 
    
     <div class="text-end mt-4">
 
    <a href="facturacion.php" class="btn btn-success btn-lg" >
        <i class="bi bi-receipt"></i>
        Facturar Compra
    </a> 
                </div>
    
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
                        <li><a class="link-light link-underline-opacity-0" href="kit-tuneup.php">Kit Tune-Up</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="sucursales.php">Sucursales</a></li>
                        <li><a class="link-light link-underline-opacity-0" href="login.php">Iniciar Sesión</a></li>
                    </ul>
                </div>
 
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