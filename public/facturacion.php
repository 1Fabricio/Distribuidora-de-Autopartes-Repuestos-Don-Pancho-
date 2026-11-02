<?php
 
session_start();
 
if(empty($_SESSION['carrito']) && empty($_SESSION['carrito_kits'])){
    header("Location: carrito.php");
    exit;
}
 
?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autopartes & Repuestos "Don Pancho" | Honduras</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
 
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
 
<body>
 
<nav class="navbar navbar-expand-lg sticky-top shadow-sm py-3 bg-dark" data-bs-theme="dark">
 
    <div class="container">
 
        <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="index.php">
 
            <i class="bi bi-gear-wide-connected text-warning me-2"></i>
 
            <span>
                Don Pancho
                <span class="badge bg-warning text-dark fs-6">
                    Honduras
                </span>
            </span>
 
        </a>
 
        <button class="navbar-toggler border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuNavegacion">
 
            <span class="navbar-toggler-icon"></span>
 
        </button>
 
        <div class="collapse navbar-collapse" id="menuNavegacion">
 
            <ul class="navbar-nav ms-auto align-items-lg-center">
 
                <li class="nav-item">
                    <a href="index.php" class="nav-link px-3">
                        Inicio
                    </a>
                </li>
 
                <li class="nav-item">
                    <a href="catalogo.php" class="nav-link px-3">
                        Catálogo
                    </a>
                </li>
 
                <li class="nav-item">
                    <a href="kitTuneup.php" class="nav-link px-3">
                        Kit Tune-Up
                    </a>
                </li>
 
                <li class="nav-item">
                    <a href="sucursales.php" class="nav-link px-3">
                        Sucursales
                    </a>
                </li>
 
                <li class="nav-item">
                    <a href="carrito.php" class="nav-link px-3">
                        <i class="bi bi-cart3"></i>
                        Carrito
                    </a>
                </li>
 
                <li class="nav-item ms-lg-2">
                    <a href="login.php"
                       class="btn btn-warning rounded-pill px-4 fw-bold">
                        Iniciar Sesión
                    </a>
                </li>
 
            </ul>
 
        </div>
 
    </div>
 
</nav>
 
<main class="container my-5">
 
    <h2 class="text-center mb-4">
        Facturación SAR
    </h2>
 
    <div class="card shadow">
 
        <div class="card-body">
 
            <form action="procesar_factura.php" method="POST">
 
                <div class="mb-3">
 
                    <label class="form-label">
                        Nombre del Cliente
                    </label>
 
                    <input type="text"
                           name="nombre_cliente"
                           class="form-control"
                           required>
 
                </div>
 
                <div class="mb-3">
 
                    <label class="form-label">
                        RTN
                    </label>
 
                    <input type="text"
                           name="rtn"
                           maxlength="14"
                           class="form-control"
                           required>
 
                </div>
 
                <div class="mb-3">
 
                    <label class="form-label">
                        Teléfono
                    </label>
 
                    <input type="text"
                           name="telefono"
                           maxlength="8"
                           class="form-control"
                           required>
 
                </div>
 
                <div class="mb-3">
 
                    <label class="form-label">
                        Sucursal
                    </label>
 
                    <select name="sucursal_id"
                            class="form-select">
 
                        <option value="1">Toncontín</option>
                        <option value="2">Suyapa</option>
                        <option value="3">Medina</option>
                        <option value="4">CA-13</option>
 
                    </select>
 
                </div>
 
                <div class="mb-3">
 
                    <label class="form-label">
                        Tipo de Entrega
                    </label>
 
                    <select name="flete_modalidad"
                            class="form-select">
 
                        <option value="retiro">
                            Retiro en Mostrador (L.0.00)
                        </option>
 
                        <option value="moto">
                            Moto Express (L.50.00)
                        </option>
 
                        <option value="camion">
                            Camioncito (L.180.00)
                        </option>
 
                    </select>
 
                </div>
 
                <button type="submit"
                        class="btn btn-success w-100">
 
                    <i class="bi bi-receipt"></i>Generar Factura</button>
 
            </form>
 
        </div>
 
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