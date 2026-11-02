<?php
declare(strict_types=1);
require_once __DIR__ . '/../../include/auth.php';
requerirRol(['admin', 'bodeguero'], '../login.php');

require_once __DIR__ . '/../../include/conexion.php';

$db = new ConexionBD();
$pdo = $db->conectar();

$mensaje = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'crear') {
        $nombre = trim($_POST['nombre'] ?? '');
        $categoria_id = filter_var($_POST['categoria_id'] ?? 0, FILTER_VALIDATE_INT);
        $precio = filter_var($_POST['precio'] ?? 0, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT);
        $nombreFoto = 'default.jpg';

        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
            $archivo = $_FILES['fotografia'];

            if ($archivo['error'] !== UPLOAD_ERR_OK) {
                $error = "Error al recibir el archivo. Código: " . $archivo['error'];
            } else {
                $maxBytes = 2 * 1024 * 1024;
                if ($archivo['size'] > $maxBytes) {
                    $error = "Error: El archivo excede el tamaño máximo permitido de 2 MB.";
                } else {
                    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

                    if (!in_array($extension, $extensionesPermitidas, true)) {
                        $error = "Error de Seguridad: Solo se aceptan imágenes JPG, PNG o WEBP.";
                    } else {
                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mimeReal = $finfo->file($archivo['tmp_name']);
                        $mimesValidos = ['image/jpeg', 'image/png', 'image/webp'];

                        if (!in_array($mimeReal, $mimesValidos, true)) {
                            $error = "Alerta de Seguridad: El archivo no es una imagen auténtica.";
                        } else {
                            $nuevoNombre = uniqid('prod_', true) . '.' . $extension;
                            $carpetaDestino = __DIR__ . '/../../uploads/';

                            if (!is_dir($carpetaDestino)) {
                                mkdir($carpetaDestino, 0755, true);
                            }

                            $rutaFinal = $carpetaDestino . $nuevoNombre;

                            if (move_uploaded_file($archivo['tmp_name'], $rutaFinal)) {
                                $nombreFoto = $nuevoNombre;
                            } else {
                                $error = "Error al mover la fotografía al directorio final de subidas.";
                            }
                        }
                    }
                }
            }
        }

        if (empty($error)) {
            try {
                $sql = "INSERT INTO productos (nombre, categoria_id, precio, stock, fotografia) VALUES (:nom, :cat, :prec, :stk, :foto)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom'  => $nombre,
                    ':cat'  => $categoria_id,
                    ':prec' => $precio,
                    ':stk'  => $stock,
                    ':foto' => $nombreFoto
                ]);
                $mensaje = "¡Repuesto registrado exitosamente en el inventario!";
            } catch (PDOException $e) {
                $error = "Error en base de datos: " . $e->getMessage();
            }
        }
    }

    if ($accion === 'actualizar') {
        $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        $precio = filter_var($_POST['precio'] ?? 0, FILTER_VALIDATE_FLOAT);
        $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT);

        if ($id && $precio !== false && $stock !== false) {
            try {
                $sql = "UPDATE productos SET precio = :prec, stock = :stk WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':prec' => $precio,
                    ':stk'  => $stock,
                    ':id'   => $id
                ]);
                $mensaje = "¡Inventario actualizado correctamente!";
            } catch (PDOException $e) {
                $error = "Error al actualizar: " . $e->getMessage();
            }
        } else {
            $error = "Datos inválidos para la actualización.";
        }
    }

    if ($accion === 'eliminar') {
        $id = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
        if ($id) {
            try {
                $stmtFoto = $pdo->prepare("SELECT fotografia FROM productos WHERE id = :id");
                $stmtFoto->execute([':id' => $id]);
                $prodTemp = $stmtFoto->fetch(PDO::FETCH_ASSOC);

                $sql = "DELETE FROM productos WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':id' => $id]);

                if ($prodTemp && $prodTemp['fotografia'] !== 'default.jpg') {
                    $rutaArchivo = __DIR__ . '/../../uploads/' . $prodTemp['fotografia'];
                    if (file_exists($rutaArchivo)) {
                        unlink($rutaArchivo);
                    }
                }

                $mensaje = "¡Producto eliminado del inventario con éxito!";
            } catch (PDOException $e) {
                $error = "Error al eliminar el registro: " . $e->getMessage();
            }
        }
    }
}


try {
    $sqlListar = "SELECT p.id, p.nombre, c.nombre AS categoria, p.precio, p.stock, p.fotografia, p.creado_en 
                  FROM productos p 
                  LEFT JOIN categorias c ON p.categoria_id = c.id 
                  ORDER BY p.id DESC";
    $stmtListar = $pdo->query($sqlListar);
    $productos = $stmtListar->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos = [];
    $error = "Error al cargar el inventario: " . $e->getMessage();
}


try {
    $stmtCat = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre ASC");
    $listaCategorias = $stmtCat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $listaCategorias = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Inventario de Repuestos | Don Pancho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-gear-wide-connected text-warning me-2"></i>Don Pancho <span class="badge bg-warning text-dark">Admin Bodega</span>
            </a>
            <div class="d-flex">
                <a href="../catalogo.php" class="btn btn-outline-light btn-sm"><i class="bi bi-shop"></i> Ver Sitio Web</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2 class="fw-bold text-dark"><i class="bi bi-boxes"></i> Gestión de Inventario</h2>
                <p class="text-muted mb-0">Control de stock, precios y registro de autopartes mediante CRUD protegido.</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <button type="button" class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrear">
                    <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Repuesto
                </button>
            </div>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($mensaje) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark text-uppercase fs-7">
                            <tr>
                                <th class="ps-4">Foto</th>
                                <th>Nombre del Repuesto</th>
                                <th>Categoría</th>
                                <th>Precio (L.)</th>
                                <th>Stock</th>
                                <th>Registro</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($productos)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">No hay repuestos registrados en el inventario.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($productos as $p): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <img src="../../uploads/<?= htmlspecialchars($p['fotografia']) ?>" alt="Foto" class="rounded" style="width: 45px; height: 45px; object-fit: cover;">
                                        </td>
                                        <td class="fw-bold"><?= htmlspecialchars($p['nombre']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($p['categoria'] ?? 'Sin categoría') ?></span></td>
                                        <td>L. <?= number_format((float)$p['precio'], 2) ?></td>
                                        <td>
                                            <span class="badge <?= $p['stock'] > 5 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $p['stock'] ?> un.
                                            </span>
                                        </td>
                                        <td class="small text-muted"><?= htmlspecialchars($p['creado_en']) ?></td>
                                        <td class="text-end pe-4">
                                            <!-- Botones de acción estándar del CRUD -->
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $p['id'] ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar<?= $p['id'] ?>">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditar<?= $p['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="inventario.php" method="POST">
                                                    <input type="hidden" name="accion" value="actualizar">
                                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">Editar Repuesto: <?= htmlspecialchars($p['nombre']) ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label">Precio (L.)</label>
                                                            <input type="number" step="0.01" class="form-control" name="precio" value="<?= $p['precio'] ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Stock</label>
                                                            <input type="number" class="form-control" name="stock" value="<?= $p['stock'] ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Eliminar -->
                                    <div class="modal fade" id="modalEliminar<?= $p['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="inventario.php" method="POST">
                                                    <input type="hidden" name="accion" value="eliminar">
                                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold text-danger">Eliminar Repuesto</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <p>¿Estás seguro de que deseas eliminar el repuesto <strong><?= htmlspecialchars($p['nombre']) ?></strong>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear Nuevo Repuesto -->
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="inventario.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="accion" value="crear">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle text-warning"></i> Registrar Nuevo Repuesto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label fw-bold">Nombre del Repuesto</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria_id" class="form-label fw-bold">Categoría</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccione una categoría...</option>
                                <?php foreach ($listaCategorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label fw-bold">Precio (L.)</label>
                            <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label fw-bold">Stock Inicial</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="fotografia" class="form-label fw-bold">Fotografía del Repuesto</label>
                            <input type="file" class="form-control" id="fotografia" name="fotografia" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-warning fw-bold">Guardar Repuesto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>