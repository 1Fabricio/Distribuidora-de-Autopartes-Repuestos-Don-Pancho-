<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function usuarioActual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function estaLogueado(): bool
{
    return usuarioActual() !== null;
}

function iniciarSesion(array $usuario): void
{
    session_regenerate_id(true);
    $_SESSION['usuario'] = $usuario;
}

function cerrarSesion(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function tieneRol(array $roles): bool
{
    $u = usuarioActual();
    return $u !== null && in_array($u['rol'], $roles, true);
}

function requerirRol(array $roles, string $login = 'login.php'): void
{
    if (!tieneRol($roles)) {
        header('Location: ' . $login . '?acceso=denegado');
        exit;
    }
}

function etiquetaRol(string $rol): string
{
    return match ($rol) {
        'admin'     => 'Admin',
        'bodeguero' => 'Bodega',
        default     => 'Cliente',
    };
}

function destinoPorRol(string $rol): string
{
    return in_array($rol, ['admin', 'bodeguero'], true) ? 'admin/inventario.php' : 'index.php';
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfValido(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}