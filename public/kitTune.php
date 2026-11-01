<?php
session_start();
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../include/conexion.php';

$conexion = new ConexionBD();
$pdo = $conexion->conectar();

function traerOpciones($pdo, $tipo)
{
    $consulta = $pdo->prepare("SELECT id, nombre, costo FROM kit_opciones WHERE tipo = ?");
    $consulta->execute([$tipo]);
    return $consulta->fetchAll();
}

$vehiculos    = traerOpciones($pdo, 'vehiculo');
$marcas       = traerOpciones($pdo, 'marca_aceite');
$viscosidades = traerOpciones($pdo, 'viscosidad');
$filtros      = traerOpciones($pdo, 'filtro');
$extras       = traerOpciones($pdo, 'extra');


$parametro = $_GET['vehiculo'] ?? 'sedan';

if ($parametro == 'suv') {
    $vehiculoElegido = 'SUV';
} elseif ($parametro == 'pickup') {
    $vehiculoElegido = 'Pick-Up 4x4';
} else {
    $vehiculoElegido = 'Sedán';
}

// Mensajes que deja agregar_kit.php (se muestran una sola vez)
$mensaje = '';
$mensajeError = '';
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}
if (isset($_SESSION['mensaje_error'])) {
    $mensajeError = $_SESSION['mensaje_error'];
    unset($_SESSION['mensaje_error']);
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Arma tu Kit Tune-Up | Autopartes &amp; Repuestos "Don Pancho"</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </head>
    <body class="d-flex flex-column min-vh-100">
        <?php require __DIR__ . '/../include/navbar.php'; ?>

        <header class="py-5 bg-dark text-white">
            <div class="container">
                <div class="row align-items-center g-4">

                    <div class="col-12 col-lg-7">
                        <p class="text-uppercase fw-bold mb-0">Arma tu</p>
                        <h1 class="display-4 fw-bold text-warning">Kit Tune-Up <i class="bi bi-wrench-adjustable"></i></h1>
                        <p class="lead mb-0">Elige tus opciones y mira el total al instante.</p>
                    </div>

                    <div class="col-12 col-lg-5">
                        <div class="border border-secondary rounded p-3">
                            <p class="mb-2"><i class="bi bi-speedometer2 text-warning me-2"></i> Más rendimiento</p>
                            <p class="mb-2"><i class="bi bi-shield-check text-warning me-2"></i> Mayor duración</p>
                            <p class="mb-0"><i class="bi bi-gear-fill text-warning me-2"></i> Protege tu motor</p>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main class="container my-5">

        <!-- Ahora el formulario se envía de verdad a agregar_kit.php por POST -->
        <form id="formKit" method="POST" action="agregar_kit.php" class="row g-4">

                <div class="col-12 col-lg-8">

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><span class="badge bg-warning text-dark me-2">1</span>Tipo de vehículo</h5>
                        <div class="row g-3">
                            <?php foreach ($vehiculos as $v) {
                                $icono = "bi-car-front-fill";
                                if ($v['nombre'] == "SUV") {
                                    $icono = "bi-truck-front-fill";
                                }
                                if ($v['nombre'] == "Pick-Up 4x4") {
                                    $icono = "bi-truck";
                                }
                            ?>
                                <div class="col-12 col-md-4">
                                    <!-- value = id: esto es lo que viaja al servidor.
                                         data-precio solo sirve para mostrar el resumen en pantalla. -->
                                    <input type="radio" class="btn-check" name="vehiculo" id="vehiculo_<?= $v['id'] ?>"
                                           value="<?= $v['id'] ?>"
                                           data-nombre="<?= $v['nombre'] ?>" data-precio="<?= $v['costo'] ?>"
                                           <?php if ($v['nombre'] == $vehiculoElegido) { echo 'checked'; } ?>>
                                    <label class="btn btn-outline-warning text-dark w-100 h-100 d-flex flex-column align-items-center p-3" for="vehiculo_<?= $v['id'] ?>">
                                        <i class="bi <?= $icono ?> fs-1"></i>
                                        <span class="fw-bold fs-5"><?= $v['nombre'] ?></span>
                                        <small>Servicio base: L. <?= number_format((float)$v['costo'], 2) ?></small>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><span class="badge bg-warning text-dark me-2">2</span>Aceite de motor</h5>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="marcaAceite" class="form-label">Marca</label>
                                <select id="marcaAceite" name="marca" class="form-select">
                                    <?php foreach ($marcas as $m) { ?>
                                        <option value="<?= $m['id'] ?>" data-nombre="<?= $m['nombre'] ?>" data-precio="<?= $m['costo'] ?>">
                                            <?= $m['nombre'] ?> (+ L. <?= number_format((float)$m['costo'], 2) ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="viscosidad" class="form-label">Viscosidad</label>
                                <select id="viscosidad" name="viscosidad" class="form-select">
                                    <?php foreach ($viscosidades as $vis) { ?>
                                        <option value="<?= $vis['id'] ?>" data-nombre="<?= $vis['nombre'] ?>"><?= $vis['nombre'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3"><span class="badge bg-warning text-dark me-2">3</span>Filtros</h5>
                        <div class="row g-3">
                            <?php foreach ($filtros as $f) { ?>
                                <div class="col-12 col-md-6">
                                    <div class="form-check border rounded p-3 ps-5">
                                        <!-- name con [] para que PHP reciba una lista de ids -->
                                        <input class="form-check-input opcion-extra" type="checkbox" id="filtro_<?= $f['id'] ?>"
                                               name="filtros[]" value="<?= $f['id'] ?>"
                                               data-nombre="<?= $f['nombre'] ?>" data-precio="<?= $f['costo'] ?>"
                                               <?php if ($f['nombre'] == 'Filtro de aceite' || $f['nombre'] == 'Filtro de aire') { echo 'checked'; } ?>>
                                        <label class="form-check-label w-100 d-flex justify-content-between" for="filtro_<?= $f['id'] ?>">
                                            <span><?= $f['nombre'] ?></span>
                                            <span class="fw-bold">+ L. <?= number_format((float)$f['costo'], 2) ?></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1"><span class="badge bg-warning text-dark me-2">4</span>Repuestos adicionales</h5>
                        <p class="small text-secondary">Cada uno tiene su costo extra.</p>
                        <div class="row g-3">
                            <?php foreach ($extras as $e) { ?>
                                <div class="col-12 col-md-6">
                                    <div class="form-check border rounded p-3 ps-5">
                                        <input class="form-check-input opcion-extra" type="checkbox"
                                               id="extra_<?= $e['id'] ?>"
                                               name="extras[]" value="<?= $e['id'] ?>"
                                               data-nombre="<?= $e['nombre'] ?>"
                                               data-precio="<?= $e['costo'] ?>">
                                        <label class="form-check-label w-100 d-flex justify-content-between" for="extra_<?= $e['id'] ?>">
                                            <span><?= $e['nombre'] ?></span>
                                            <span class="fw-bold">+ L. <?= number_format((float)$e['costo'], 2) ?></span>
                                        </label>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                </div>

                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-warning position-sticky" style="top: 100px;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-warning"></i> Resumen de tu kit</h5>

                            <ul id="listaResumen" class="list-group list-group-flush small mb-3"></ul>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-3">
                                <span class="fs-5 fw-bold">Total</span>
                                <span id="total" class="fs-3 fw-bold text-warning">L. 0.00</span>
                            </div>

                        <!-- Ahora es un botón submit: envía el formulario -->
                        <button type="submit" class="btn btn-warning w-100 fw-bold">
                            <i class="bi bi-cart-plus"></i> Agregar kit al carrito
                        </button>

                        <?php if ($mensaje != '') { ?>
                            <p class="small fw-bold text-success mt-3 mb-0"><?= $mensaje ?></p>
                        <?php } ?>
                        <?php if ($mensajeError != '') { ?>
                            <p class="small fw-bold text-danger mt-3 mb-0"><?= $mensajeError ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>

            </form>
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
                            <li><a class="link-light link-underline-opacity-0" href="kitTune.php">Kit Tune-Up</a></li>
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
        <script>
            function aLempiras(numero) {
                return 'L. ' + numero.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

        // Esto es SOLO para mostrar el resumen en pantalla.
        // El precio real lo calcula PHP en agregar_kit.php.
        function armarKit() {
            var lista = [];

                var vehiculo = document.querySelector('input[name="vehiculo"]:checked');
                lista.push({ nombre: 'Servicio base - ' + vehiculo.dataset.nombre, precio: Number(vehiculo.dataset.precio) });

            var marca = document.getElementById('marcaAceite');
            var viscosidad = document.getElementById('viscosidad');
            var marcaElegida = marca.options[marca.selectedIndex];
            var viscosidadElegida = viscosidad.options[viscosidad.selectedIndex];
            lista.push({ nombre: 'Aceite ' + marcaElegida.dataset.nombre + ' ' + viscosidadElegida.dataset.nombre, precio: Number(marcaElegida.dataset.precio) });

                var marcados = document.querySelectorAll('.opcion-extra:checked');
                for (var i = 0; i < marcados.length; i++) {
                    lista.push({ nombre: marcados[i].dataset.nombre, precio: Number(marcados[i].dataset.precio) });
                }

                var total = 0;
                for (var j = 0; j < lista.length; j++) {
                    total = total + lista[j].precio;
                }

                return { items: lista, total: total };
            }

            function mostrarResumen() {
                var kit = armarKit();
                var html = '';

                for (var i = 0; i < kit.items.length; i++) {
                    html += '<li class="list-group-item d-flex justify-content-between px-0">'
                        + '<span>' + kit.items[i].nombre + '</span>'
                        + '<span>' + aLempiras(kit.items[i].precio) + '</span>'
                        + '</li>';
                }

                document.getElementById('listaResumen').innerHTML = html;
                document.getElementById('total').textContent = aLempiras(kit.total);
            }

        document.getElementById('formKit').addEventListener('change', mostrarResumen);
        mostrarResumen();
    </script>
</body>
</html>