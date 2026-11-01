<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . '/../include/conexion.php';
require_once __DIR__ . '/../include/kit.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: kitTune.php");
    exit;
}

$conexion = new ConexionBD();
$pdo = $conexion->conectar();

if ($pdo === null) {
    exit;
}

$filtros = $_POST['filtros'] ?? [];
if (!is_array($filtros)) {
    $filtros = [];
}
$extras = $_POST['extras'] ?? [];
if (!is_array($extras)) {
    $extras = [];
}

$seleccion = [
    'vehiculo'   => (int)($_POST['vehiculo'] ?? 0),
    'marca'      => (int)($_POST['marca'] ?? 0),
    'viscosidad' => (int)($_POST['viscosidad'] ?? 0),
    'filtros'    => $filtros,
    'extras'     => $extras
];

$repo = new Kit($pdo);
$resultado = $repo->calcular($seleccion);

if ($resultado === null) {
    $_SESSION['mensaje_error'] = 'La selección del kit no es válida.';
    header("Location: kitTune.php");
    exit;
}

if (!isset($_SESSION['carrito_kits'])) {
    $_SESSION['carrito_kits'] = [];
}
$_SESSION['carrito_kits'][] = $seleccion;

$_SESSION['mensaje'] = 'Kit agregado al carrito por L. ' . number_format($resultado['total'], 2);
header("Location: kitTune.php");
exit;
?>