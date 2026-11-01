<?php
declare(strict_types=1);
require_once __DIR__.'/../include/conexion.php';
$conexcion=new ConexionBD();
$bd = $conexcion->conectar();
    if($bd instanceof PDO){
        echo "<div>
        <strong>Conexcion establecida </div>";
    }
?>