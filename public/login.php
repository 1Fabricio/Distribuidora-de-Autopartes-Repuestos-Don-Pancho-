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
                        <a href="kitTuneup.php" class="nav-link px-3">Kit Tune-Up</a>
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
    
<main class="py-5">
        <div class="container py-lg-4">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-5">
 
                    <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5 bg-white">
 
                        <div class="text-center mb-4">
                            <i class="bi bi-person-lock fs-1 text-warning"></i>
                            <h1 class="h3 fw-bold mt-2">Bienvenido a Don Pancho</h1>
                            <p class="text-muted small mb-0">Entra con tu cuenta o crea una nueva</p>
                        </div>
 
                        <!-- Aquí van los mensajes generales (aviso de acceso denegado / registro exitoso).
                             Ejemplos del diseño:
 
                        <div class="alert alert-warning small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> Debes iniciar sesión con un usuario autorizado para entrar al panel.
                        </div>
 
                        <div class="alert alert-success small" role="alert">
                            <i class="bi bi-check-circle-fill"></i> Cuenta creada correctamente. Ya puedes iniciar sesión.
                        </div>
                        -->
 
                        <!-- Pestañas: ya tengo cuenta / soy nuevo -->
                        <ul class="nav nav-pills nav-fill mb-4" id="tabsAcceso" role="tablist"
                            style="--bs-nav-pills-link-active-bg: #ffc107; --bs-nav-pills-link-active-color: #212529;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold active"
                                        id="tab-login" data-bs-toggle="pill" data-bs-target="#panel-login"
                                        type="button" role="tab" aria-controls="panel-login"
                                        aria-selected="true">
                                    Ya tengo cuenta
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold"
                                        id="tab-registro" data-bs-toggle="pill" data-bs-target="#panel-registro"
                                        type="button" role="tab" aria-controls="panel-registro"
                                        aria-selected="false">
                                    Soy nuevo
                                </button>
                            </li>
                        </ul>
 
                        <div class="tab-content">
 
                            <!-- ===== INICIAR SESIÓN ===== -->
                            <div class="tab-pane fade show active"
                                 id="panel-login" role="tabpanel" aria-labelledby="tab-login" tabindex="0">
 
                                <!-- Aquí va el error del login. Ejemplo del diseño:
 
                                <div class="alert alert-danger small" role="alert">
                                    <i class="bi bi-x-circle-fill"></i> Usuario o contraseña incorrectos.
                                </div>
                                -->
 
                                <form action="login.php" method="POST" novalidate>
                                    <input type="hidden" name="accion" value="login">
                                    <input type="hidden" name="token" value="">
 
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="login-usuario" name="usuario"
                                               placeholder="Usuario" autocomplete="username"
                                               value="" required>
                                        <label for="login-usuario">Usuario</label>
                                    </div>
 
                                    <div class="form-floating mb-4">
                                        <input class="form-control" type="password" id="login-clave" name="clave"
                                               placeholder="Contraseña" autocomplete="current-password" required>
                                        <label for="login-clave">Contraseña</label>
                                    </div>
 
                                    <button class="btn btn-lg rounded-pill fw-bold w-100 shadow btn-warning" type="submit">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                                    </button>
                                </form>
 
                                <p class="text-center small text-muted mt-4 mb-0">
                                    ¿No tienes cuenta?
                                    <a href="#" class="fw-bold link-dark" data-ir-tab="tab-registro">Regístrate aquí</a>
                                </p>
                            </div>
 
                            <!-- ===== REGISTRO (solo clientes) ===== -->
                            <div class="tab-pane fade"
                                 id="panel-registro" role="tabpanel" aria-labelledby="tab-registro" tabindex="0">
 
                                <!-- Aquí va el error del registro. Ejemplo del diseño:
 
                                <div class="alert alert-danger small" role="alert">
                                    <i class="bi bi-x-circle-fill"></i> Ese nombre de usuario ya está en uso. Elige otro.
                                </div>
                                -->
 
                                <form action="login.php" method="POST" novalidate>
                                    <input type="hidden" name="accion" value="registro">
                                    <input type="hidden" name="token" value="">
 
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="reg-nombre" name="nombre"
                                               placeholder="Nombre completo" maxlength="100" autocomplete="name"
                                               value="" required>
                                        <label for="reg-nombre">Nombre completo</label>
                                    </div>
 
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="reg-usuario" name="usuario"
                                               placeholder="Usuario" maxlength="30" autocomplete="username"
                                               value="" required>
                                        <label for="reg-usuario">Usuario (4 a 30 caracteres)</label>
                                    </div>
 
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="reg-clave" name="clave"
                                               placeholder="Contraseña" minlength="8" autocomplete="new-password" required>
                                        <label for="reg-clave">Contraseña (mínimo 8 caracteres)</label>
                                    </div>
 
                                    <div class="form-floating mb-4">
                                        <input class="form-control" type="password" id="reg-clave2" name="clave2"
                                               placeholder="Repite la contraseña" minlength="8" autocomplete="new-password" required>
                                        <label for="reg-clave2">Repite la contraseña</label>
                                    </div>
 
                                    <button class="btn btn-lg rounded-pill fw-bold w-100 shadow btn-dark" type="submit">
                                        <i class="bi bi-person-plus-fill"></i> Crear cuenta
                                    </button>
                                </form>
 
                                <p class="text-center small text-muted mt-4 mb-0">
                                    ¿Ya tienes cuenta?
                                    <a href="#" class="fw-bold link-dark" data-ir-tab="tab-login">Inicia sesión</a>
                                </p>
                            </div>
 
                        </div>
                    </div>
 
                </div>
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

            <hr class="border-secondary mt-4">
            <div class="text-center small text-secondary">
                &copy; 2026 Autopartes &amp; Repuestos "Don Pancho" — Honduras.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>