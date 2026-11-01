<?php
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
<body>
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm py-3 bg-dark" data-bs-theme="dark">
        <div class="container">

            <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="index.php">
                <i class="bi bi-gear-wide-connected text-warning me-2"></i>
                <span>Don Pancho <span class="badge bg-warning text-dark fs-6">Honduras</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavegacion" aria-controls="menuNavegacion" aria-expanded="false" aria-label="Abrir navegación">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuNavegacion">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link px-3">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="catalogo.php" class="nav-link px-3">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a href="kitTune.php" class="nav-link px-3 active">Kit Tune-Up</a>
                    </li>
                    <li class="nav-item">
                        <a href="sucursales.php" class="nav-link px-3">Sucursales</a>
                    </li>
                    <li class="nav-item">
                        <a href="carrito.php" class="nav-link px-3"><i class="bi bi-cart3"></i> Carrito</a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a href="login.php" class="btn btn-warning rounded-pill px-4 fw-bold">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <main>

        <h1>Arma tu Kit Tune-Up</h1>
        <p>Elige tus opciones y mira el total al instante.</p>

        <form id="formKit">

            <h2>1. Tipo de vehículo</h2>
            <?php foreach ($vehiculos as $v) { ?>
                <p>
                    <input type="radio" name="vehiculo" id="vehiculo_<?= $v['id'] ?>" data-nombre="<?= $v['nombre'] ?>" data-precio="<?= $v['costo'] ?>"
                           <?php if ($v['nombre'] == $vehiculoElegido) { echo 'checked'; } ?>>
                    <label for="vehiculo_<?= $v['id'] ?>"><?= $v['nombre'] ?> - Servicio base: L. <?= number_format($v['costo'], 2) ?></label>
                </p>
            <?php } ?>

            <h2>2. Aceite de motor</h2>
            <p>
                <label for="marcaAceite">Marca</label>
                <select id="marcaAceite">
                    <?php foreach ($marcas as $m) { ?>
                        <option value="<?= $m['nombre'] ?>" data-precio="<?= $m['costo'] ?>">
                            <?= $m['nombre'] ?> (+ L. <?= number_format($m['costo'], 2) ?>)
                        </option>
                    <?php } ?>
                </select>
            </p>
            <p>
                <label for="viscosidad">Viscosidad</label>
                <select id="viscosidad">
                    <?php foreach ($viscosidades as $vis) { ?>
                        <option value="<?= $vis['nombre'] ?>"><?= $vis['nombre'] ?></option>
                    <?php } ?>
                </select>
            </p>

            <h2>3. Filtros</h2>
            <?php foreach ($filtros as $f) { ?>
                <p>
                    <input class="opcion-extra" type="checkbox"
                           id="filtro_<?= $f['id'] ?>"
                           data-nombre="<?= $f['nombre'] ?>"
                           data-precio="<?= $f['costo'] ?>"
                           <?php if ($f['nombre'] == 'Filtro de aceite' || $f['nombre'] == 'Filtro de aire') { echo 'checked'; } ?>>
                    <label for="filtro_<?= $f['id'] ?>">
                        <?= $f['nombre'] ?> + L. <?= number_format($f['costo'], 2) ?>
                    </label>
                </p>
            <?php } ?>

            <h2>4. Repuestos adicionales</h2>
            <p>Cada uno tiene su costo extra.</p>
            <?php foreach ($extras as $e) { ?>
                <p>
                    <input class="opcion-extra" type="checkbox"
                           id="extra_<?= $e['id'] ?>"
                           data-nombre="<?= $e['nombre'] ?>"
                           data-precio="<?= $e['costo'] ?>">
                    <label for="extra_<?= $e['id'] ?>">
                        <?= $e['nombre'] ?> + L. <?= number_format($e['costo'], 2) ?>
                    </label>
                </p>
            <?php } ?>

            <h2>Resumen de tu kit</h2>
            <ul id="listaResumen"></ul>

            <h3>Total: <span id="total">L. 0.00</span></h3>

            <button type="button" id="btnAgregar">Agregar kit al carrito</button>
            <p id="mensaje"></p>

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

        function armarKit() {
            var lista = [];

            var vehiculo = document.querySelector('input[name="vehiculo"]:checked');
            lista.push({ nombre: 'Servicio base - ' + vehiculo.dataset.nombre, precio: Number(vehiculo.dataset.precio) });

            var marca = document.getElementById('marcaAceite');
            var viscosidad = document.getElementById('viscosidad');
            var marcaElegida = marca.options[marca.selectedIndex];
            lista.push({ nombre: 'Aceite ' + marca.value + ' ' + viscosidad.value, precio: Number(marcaElegida.dataset.precio) });

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
                html += '<li>' + kit.items[i].nombre + ': ' + aLempiras(kit.items[i].precio) + '</li>';
            }

            document.getElementById('listaResumen').innerHTML = html;
            document.getElementById('total').textContent = aLempiras(kit.total);
        }

        document.getElementById('formKit').addEventListener('change', mostrarResumen);
        mostrarResumen();

        document.getElementById('btnAgregar').addEventListener('click', function () {
            var kit = armarKit();
            var carrito = JSON.parse(localStorage.getItem('carritoKits') || '[]');
            carrito.push(kit);
            localStorage.setItem('carritoKits', JSON.stringify(carrito));

            document.getElementById('mensaje').textContent = 'Kit agregado al carrito por ' + aLempiras(kit.total);
        });
    </script>
</body>
</html>