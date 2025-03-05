<?php
session_start();

// Si no hay sesión activa, redirigir al login de inmediato
if (!isset($_SESSION['logged'])) {
    header("Location: login.php");
    exit;
}

// Evitar que el navegador almacene caché (previene volver atrás tras logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Destruir la sesión si está activa
session_unset();
session_destroy();

// Eliminar la cookie de sesión de forma segura
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], $params["domain"], 
        $params["secure"], $params["httponly"]
    );
}

// Redirigir al usuario al login con mensaje opcional
header("Location: login.php?logout=success");
exit;
?>

