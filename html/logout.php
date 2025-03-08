<?php
session_start();

// Evitar caché para prevenir volver atrás
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Destruir completamente la sesión
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

// Redirigir al login con JavaScript para invalidar historial del navegador
echo "<script>
    window.location.replace('login.php');
</script>";
exit;
?>