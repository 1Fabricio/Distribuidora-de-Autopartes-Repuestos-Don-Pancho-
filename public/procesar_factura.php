<?php

session_start();

require_once __DIR__ . '/../include/conexion.php';

$db = new ConexionBD();
$pdo = $db->conectar();

if(empty($_SESSION['carrito'])){
    header("Location: carrito.php");
    exit;
}

$nombre_cliente = trim($_POST['nombre_cliente']);
$rtn = trim($_POST['rtn']);
$telefono = trim($_POST['telefono']);
$sucursal_id = (int)$_POST['sucursal_id'];
$flete_modalidad = $_POST['flete_modalidad'];

if(!preg_match('/^[0-9]{14}$/', $rtn)){
    die("RTN inválido.");
}

if(!preg_match('/^[0-9]{8}$/', $telefono)){
    die("Teléfono inválido.");
}

$subtotal = 0;
$cantidadRepuestos = 0;

foreach($_SESSION['carrito'] as $item){

    $subtotal += (
        $item['precio']
        * $item['cantidad']
    );

    $cantidadRepuestos += $item['cantidad'];
}

$descuento = 0;
if($cantidadRepuestos >= 10){

    $descuento = $subtotal * 0.10;

}

$baseImponible = $subtotal - $descuento;
$isv = $baseImponible * 0.15;
switch($flete_modalidad){

    case 'moto':
        $flete = 50.00;
        break;
    case 'camion':
        $flete = 180.00;
        break;
    default:
        $flete = 0.00;
}

$total = $baseImponible + $isv + $flete;
$stmt = $pdo->prepare("
    INSERT INTO pedidos
    (
        usuario_id,
        sucursal_id,
        flete_modalidad,
        subtotal,
        descuento,
        isv,
        flete,
        total
    )
    VALUES
    (
        NULL,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )
");

$stmt->execute([
    $sucursal_id,
    $flete_modalidad,
    $subtotal,
    $descuento,
    $isv,
    $flete,
    $total
]);

$pedido_id = $pdo->lastInsertId();
$stmtDetalle = $pdo->prepare("
    INSERT INTO pedido_detalle
    (
        pedido_id,
        producto_id,
        descripcion,
        cantidad,
        precio_unitario
    )
    VALUES
    (
        ?,?,?,?,?
    )
");

foreach($_SESSION['carrito'] as $item){

    $stmtDetalle->execute([
        $pedido_id,
        $item['id'],
        $item['nombre'],
        $item['cantidad'],
        $item['precio']
    ]);

}

$stmtFactura = $pdo->prepare("
    INSERT INTO facturas
    (
        pedido_id,
        nombre_cliente,
        rtn,
        telefono
    )
    VALUES
    (
        ?,?,?,?
    )
");

$stmtFactura->execute([
    $pedido_id,
    $nombre_cliente,
    $rtn,
    $telefono
]);

$factura_id = $pdo->lastInsertId();
$numeroFactura =
"FACT-" .
str_pad(
    $factura_id,
    5,
    "0",
    STR_PAD_LEFT
);

unset($_SESSION['carrito']);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura SAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-body">

            <h2 class="text-center mb-4"> Factura SAR </h2>
                <p><strong>Número de Factura:</strong> 
                <?= $numeroFactura ?>
            </p>

            <p><strong>Cliente:</strong>
                <?= htmlspecialchars($nombre_cliente) ?>
            </p>

            <p><strong>RTN:</strong>
                <?= htmlspecialchars($rtn) ?>
            </p>

            <p><strong>Teléfono:</strong>
                <?= htmlspecialchars($telefono) ?>
            </p>

            <hr><p><strong>Subtotal:</strong>
             L. <?= number_format($subtotal,2) ?>
            </p>

            <p><strong>Descuento:</strong>
                L. <?= number_format($descuento,2) ?>
            </p>

            <p><strong>ISV (15%):</strong>
                L. <?= number_format($isv,2) ?>
            </p>

            <p><strong>Flete:</strong>
                L. <?= number_format($flete,2) ?>
            </p>

            <h3 class="text-success">Total: L. <?= number_format($total,2) ?></h3>

            <a href="catalogo.php"
               class="btn btn-primary mt-3">

                Volver al Catálogo

            </a>
        </div>
    </div>
</div>
</body>
</html>