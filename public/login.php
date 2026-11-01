<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/auth.php';
require_once __DIR__ . '/../classes/usuario.php';

if (estaLogueado() && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . destinoPorRol(usuarioActual()['rol']));
    exit;
}

$errorLogin = '';
$errorRegistro = '';
$tabActiva = 'login';

$valUsuarioLogin = '';
$valNombre = '';
$valUsuarioReg = '';

$aviso = '';
if (($_GET['acceso'] ?? '') === 'denegado') {
    $aviso = 'denegado';
} elseif (($_GET['registro'] ?? '') === 'ok') {
    $aviso = 'registrado';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if (!csrfValido($_POST['token'] ?? null)) {
        if ($accion === 'registro') {
            $tabActiva = 'registro';
            $errorRegistro = 'La sesión del formulario expiró. Intenta de nuevo.';
        } else {
            $errorLogin = 'La sesión del formulario expiró. Intenta de nuevo.';
        }

    } elseif ($accion === 'login') {
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = $_POST['clave'] ?? '';
        $valUsuarioLogin = $usuario;

        if ($usuario === '' || $clave === '') {
            $errorLogin = 'Escribe tu usuario y contraseña.';
        } else {
            $datos = (new Usuario())->autenticar($usuario, $clave);
            if ($datos === null) {
                $errorLogin = 'Usuario o contraseña incorrectos.';
            } else {
                iniciarSesion($datos);
                header('Location: ' . destinoPorRol($datos['rol']));
                exit;
            }
        }

    } elseif ($accion === 'registro') {
        $tabActiva = 'registro';
        $nombre = trim($_POST['nombre'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $clave = $_POST['clave'] ?? '';
        $clave2 = $_POST['clave2'] ?? '';
        $valNombre = $nombre;
        $valUsuarioReg = $usuario;

        $len = fn(string $s): int => mb_strlen($s, 'UTF-8');

        if ($nombre === '' || $usuario === '' || $clave === '' || $clave2 === '') {
            $errorRegistro = 'Completa todos los campos.';
        } elseif ($len($nombre) < 3 || $len($nombre) > 100) {
            $errorRegistro = 'El nombre debe tener entre 3 y 100 caracteres.';
        } elseif (!preg_match('/^[A-Za-z0-9_.]{4,30}$/', $usuario)) {
            $errorRegistro = 'El usuario debe tener de 4 a 30 caracteres: letras, números, punto o guion bajo.';
        } elseif ($len($clave) < 8) {
            $errorRegistro = 'La contraseña debe tener mínimo 8 caracteres.';
        } elseif ($clave !== $clave2) {
            $errorRegistro = 'Las contraseñas no coinciden.';
        } else {
            $modelo = new Usuario();
            if ($modelo->existeUsuario($usuario)) {
                $errorRegistro = 'Ese nombre de usuario ya está en uso. Elige otro.';
            } elseif ($modelo->registrarCliente($nombre, $usuario, $clave)) {
                header('Location: login.php?registro=ok');
                exit;
            } else {
                $errorRegistro = 'Ese nombre de usuario ya está en uso. Elige otro.';
            }
        }
    }
}

$token = csrfToken();
$paginaActiva = '';
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

                        <?php if ($aviso === 'denegado'): ?>
                            <div class="alert alert-warning small" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> Debes iniciar sesión con un usuario autorizado para entrar al panel.
                            </div>
                        <?php elseif ($aviso === 'registrado'): ?>
                            <div class="alert alert-success small" role="alert">
                                <i class="bi bi-check-circle-fill"></i> Cuenta creada correctamente. Ya puedes iniciar sesión.
                            </div>
                        <?php endif; ?>

                        <ul class="nav nav-pills nav-fill mb-4" id="tabsAcceso" role="tablist"
                            style="--bs-nav-pills-link-active-bg: #ffc107; --bs-nav-pills-link-active-color: #212529;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold<?= $tabActiva === 'login' ? ' active' : '' ?>"
                                        id="tab-login" data-bs-toggle="pill" data-bs-target="#panel-login"
                                        type="button" role="tab" aria-controls="panel-login"
                                        aria-selected="<?= $tabActiva === 'login' ? 'true' : 'false' ?>">
                                    Ya tengo cuenta
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold<?= $tabActiva === 'registro' ? ' active' : '' ?>"
                                        id="tab-registro" data-bs-toggle="pill" data-bs-target="#panel-registro"
                                        type="button" role="tab" aria-controls="panel-registro"
                                        aria-selected="<?= $tabActiva === 'registro' ? 'true' : 'false' ?>">
                                    Soy nuevo
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane fade<?= $tabActiva === 'login' ? ' show active' : '' ?>"
                                 id="panel-login" role="tabpanel" aria-labelledby="tab-login" tabindex="0">

                                <?php if ($errorLogin !== ''): ?>
                                    <div class="alert alert-danger small" role="alert">
                                        <i class="bi bi-x-circle-fill"></i> <?= htmlspecialchars($errorLogin) ?>
                                    </div>
                                <?php endif; ?>


                                <form action="login.php" method="POST" novalidate>
                                    <input type="hidden" name="accion" value="login">
                                    <input type="hidden" name="token" value="<?= $token ?>">

                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="login-usuario" name="usuario"
                                            placeholder="Usuario" autocomplete="username" value="<?= htmlspecialchars($valUsuarioLogin) ?>" required>
                                        <label for="login-usuario">Usuario</label>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input class="form-control" type="password" id="login-clave" name="clave"
                                            placeholder="Contraseña" autocomplete="current-password" required>
                                        <label for="login-clave">Contraseña</label>
                                    </div>

                                    <button class="btn btn-lg rounded-pill fw-bold w-100 shadow btn-warning"
                                        type="submit">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                                    </button>
                                </form>

                                <p class="text-center small text-muted mt-4 mb-0">
                                    ¿No tienes cuenta?
                                    <a href="#" class="fw-bold link-dark" data-ir-tab="tab-registro">Regístrate aquí</a>
                                </p>
                            </div>

                            <div class="tab-pane fade<?= $tabActiva === 'registro' ? ' show active' : '' ?>"
                                 id="panel-registro" role="tabpanel" aria-labelledby="tab-registro" tabindex="0">

                                <?php if ($errorRegistro !== ''): ?>
                                    <div class="alert alert-danger small" role="alert">
                                        <i class="bi bi-x-circle-fill"></i> <?= htmlspecialchars($errorRegistro) ?>
                                    </div>
                                <?php endif; ?>

                                <form action="login.php" method="POST" novalidate>
                                    <input type="hidden" name="accion" value="registro">
                                    <input type="hidden" name="token" value="<?= $token ?>">

                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="reg-nombre" name="nombre"
                                            placeholder="Nombre completo" maxlength="100" autocomplete="name" value="<?= htmlspecialchars($valNombre) ?>"
                                            required>
                                        <label for="reg-nombre">Nombre completo</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="text" id="reg-usuario" name="usuario"
                                            placeholder="Usuario" maxlength="30" autocomplete="username" value="<?= htmlspecialchars($valUsuarioReg) ?>"
                                            required>
                                        <label for="reg-usuario">Usuario (4 a 30 caracteres)</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="password" id="reg-clave" name="clave"
                                            placeholder="Contraseña" minlength="8" autocomplete="new-password" required>
                                        <label for="reg-clave">Contraseña (mínimo 8 caracteres)</label>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input class="form-control" type="password" id="reg-clave2" name="clave2"
                                            placeholder="Repite la contraseña" minlength="8" autocomplete="new-password"
                                            required>
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
                    <h5><i class="bi bi-gear-wide-connected text-warning"></i> Autopartes &amp; Repuestos "Don Pancho"
                    </h5>
                    <p class="small text-secondary">
                        Distribuidora de piezas originales y de reemplazo para vehículos japoneses, coreanos y
                        americanos en Honduras.
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