<?php
declare(strict_types=1);
require_once __DIR__ . '/../include/auth.php';

cerrarSesion();
header('Location: login.php');
exit;