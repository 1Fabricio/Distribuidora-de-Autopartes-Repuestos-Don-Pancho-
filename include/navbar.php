<?php
$paginaActiva = $paginaActiva ?? '';
$base         = $base ?? '';
$u            = usuarioActual();

$activo = fn(string $p): string => $paginaActiva === $p ? ' active' : '';
?>
<nav class="navbar navbar-expand-lg sticky-top shadow-sm py-3 bg-dark" data-bs-theme="dark">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center fw-bold fs-4" href="<?= $base ?>index.php">
            <i class="bi bi-gear-wide-connected text-warning me-2"></i>
            <span>Don Pancho <span class="badge bg-warning text-dark fs-6">Honduras</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuNavegacion" aria-controls="menuNavegacion" aria-expanded="false" aria-label="Abrir navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuNavegacion">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a href="<?= $base ?>index.php" class="nav-link px-3<?= $activo('inicio') ?>">Inicio</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>catalogo.php" class="nav-link px-3<?= $activo('catalogo') ?>">Catálogo</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>kitTune.php" class="nav-link px-3<?= $activo('kit') ?>">Kit Tune-Up</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>sucursales.php" class="nav-link px-3<?= $activo('sucursales') ?>">Sucursales</a>
                </li>
                <li class="nav-item">
                    <a href="<?= $base ?>carrito.php" class="nav-link px-3<?= $activo('carrito') ?>"><i class="bi bi-cart3"></i> Carrito</a>
                </li>

                <?php if ($u === null): ?>
                    <li class="nav-item ms-lg-2">
                        <a href="<?= $base ?>login.php" class="btn btn-warning rounded-pill px-4 fw-bold">Iniciar Sesión</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item dropdown ms-lg-2">
                        <a class="nav-link dropdown-toggle px-3 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span><?= htmlspecialchars($u['nombre']) ?></span>
                            <span class="badge bg-warning text-dark"><?= etiquetaRol($u['rol']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <?php if (in_array($u['rol'], ['admin', 'bodeguero'], true)): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= $base ?>admin/inventario.php">
                                        <i class="bi bi-box-seam"></i> Panel de inventario
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item text-danger fw-bold" href="<?= $base ?>logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</nav>