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
                        <a href="index.php" class="nav-link px-3 active">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a href="catalogo.php" class="nav-link px-3">Catálogo</a>
                    </li>
                    <li class="nav-item">
                        <a href="kitTune.php" class="nav-link px-3">Kit Tune-Up</a>
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

    <header class="py-5 bg-dark text-white" id="inicio">
        <div class="container py-lg-4">
            <div class="row align-items-center g-5">

                <div class="col-12 col-lg-7">
                    <span class="badge rounded-pill px-3 py-2 mb-3 bg-secondary text-white">
                        <i class="bi bi-geo-alt-fill"></i> Tegucigalpa, San Pedro Sula y La Ceiba
                    </span>
                    <h1 class="display-4 fw-bold mb-3">
                        Expertos en Repuestos <span class="text-warning">Genuinos y Compatibles</span>
                    </h1>
                    <p class="lead mb-4">
                        Piezas originales y de reemplazo para vehículos japoneses, coreanos y americanos.
                        Arma tu kit de mantenimiento, paga con factura fiscal SAR y recibe tu pedido en moto o camioncito.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-lg rounded-pill px-4 fw-bold btn-warning" href="catalogo.php">
                            <i class="bi bi-grid-3x3-gap-fill"></i> Ver Catálogo
                        </a>
                        <a class="btn btn-lg rounded-pill px-4 btn-outline-light" href="kitTune.php">
                            <i class="bi bi-wrench-adjustable"></i> Arma tu Kit Tune-Up
                        </a>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="card shadow-lg rounded-4 overflow-hidden border-0">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-1">Cotiza tu mantenimiento</h5>
                            <p class="card-text small text-secondary">¿Qué tipo de vehículo tienes?</p>
                            <div class="list-group">
                                <a href="kitTune.php?vehiculo=sedan" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                    <i class="bi bi-car-front fs-4"></i> Sedán
                                </a>
                                <a href="kitTune.php?vehiculo=suv" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                    <i class="bi bi-truck-front fs-4"></i> SUV
                                </a>
                                <a href="kitTune.php?vehiculo=pickup" class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                    <i class="bi bi-truck fs-4"></i> Pick-Up 4x4
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <section id="ventajas" class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="row g-4 text-center">

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-light">
                        <div class="card-body">
                            <i class="bi bi-shop fs-1 mb-2 d-block text-primary"></i>
                            <h3 class="fw-bold mb-1">4 Sucursales</h3>
                            <p class="text-muted small mb-0">Toncontín, Suyapa, Medina y CA-13</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-light">
                        <div class="card-body">
                            <i class="bi bi-car-front-fill fs-1 mb-2 d-block text-success"></i>
                            <h3 class="fw-bold mb-1">6 Marcas Aliadas</h3>
                            <p class="text-muted small mb-0">Repuestos para vehículos japoneses, coreanos y americanos</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-light">
                        <div class="card-body">
                            <i class="bi bi-receipt fs-1 mb-2 d-block text-warning"></i>
                            <h3 class="fw-bold mb-1">Factura SAR</h3>
                            <p class="text-muted small mb-0">Factura digital correlativa con tu RTN</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-3 bg-light">
                        <div class="card-body">
                            <i class="bi bi-bicycle fs-1 mb-2 d-block text-info"></i>
                            <h3 class="fw-bold mb-1">Envío Flexible</h3>
                            <p class="text-muted small mb-0">Retiro en mostrador, moto express o camioncito</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="destacados" class="py-5 bg-light">
        <div class="container py-lg-4">

            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold bg-warning text-dark">Más Buscados</span>
                <h2 class="display-6 fw-bold mt-2">Repuestos Destacados</h2>
                <p class="text-muted">Los repuestos que más piden talleres y clientes en nuestras sucursales.</p>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5 g-4">
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img class="card-img-top" src="../img/Pastillas-freno.webp" alt="Pastillas de freno cerámicas"
                        style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge rounded-pill bg-primary align-self-start mb-2">Frenos y Suspensión</span>
                            <h6 class="card-title fw-bold">Pastillas de freno cerámicas</h6>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">L. 850.00</span>
                                <a href="catalogo.php" class="btn btn-sm rounded-pill btn-outline-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img class="card-img-top" src="../img/Filtro-aceite.webp" alt="Filtro de aceite"
                        style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge rounded-pill bg-primary align-self-start mb-2">Motor y Transmisión</span>
                            <h6 class="card-title fw-bold">Filtro de aceite</h6>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">L. 180.00</span>
                                <a href="catalogo.php" class="btn btn-sm rounded-pill btn-outline-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img class="card-img-top" src="../img/bujillas.jpg" alt="Bujias"
                        style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge rounded-pill bg-primary align-self-start mb-2">Sistema Eléctrico</span>
                            <h6 class="card-title fw-bold">Bujías de iridio (juego)</h6>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">L. 1,200.00</span>
                                <a href="catalogo.php" class="btn btn-sm rounded-pill btn-outline-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img class="card-img-top" src="../img/aceite.webp" alt="Aceite Castrol"
                        style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge rounded-pill bg-primary align-self-start mb-2">Fluidos y Lubricantes</span>
                            <h6 class="card-title fw-bold">Aceite sintético 5W-30</h6>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">L. 650.00</span>
                                <a href="catalogo.php" class="btn btn-sm rounded-pill btn-outline-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img class="card-img-top" src="../img/refrigerante-castrol.jpg" alt="Refrigerante"
                        style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-3">
                            <span class="badge rounded-pill bg-primary align-self-start mb-2">Fluidos y Lubricantes</span>
                            <h6 class="card-title fw-bold">Refrigerante concentrado</h6>
                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-primary">L. 320.00</span>
                                <a href="catalogo.php" class="btn btn-sm rounded-pill btn-outline-primary">Ver</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="text-center mt-5">
                <a href="catalogo.php" class="btn btn-lg rounded-pill px-4 fw-bold btn-dark">Ver Todo el Catálogo</a>
            </div>

        </div>
    </section>

    <section id="marcas" class="py-5 bg-white border-top border-bottom">
        <div class="container py-lg-4">

            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-2 text-uppercase fw-bold bg-dark">Compatibilidad</span>
                <h2 class="display-6 fw-bold mt-2">Marcas Vehiculares Aliadas</h2>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-6 g-3 text-center">
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Toyota</div></div>
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Nissan</div></div>
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Honda</div></div>
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Hyundai</div></div>
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Isuzu</div></div>
                <div class="col"><div class="card border-0 shadow-sm rounded-4 py-3 bg-light fw-bold fs-5">Ford</div></div>
            </div>

        </div>
    </section>

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
</body>
</html>