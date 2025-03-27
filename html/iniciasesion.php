<?php
session_start();
session_regenerate_id(true);
require 'db.php';

$cedula = filter_var($_POST['cedula'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$clave1 = trim($_POST['clave1'] ?? '');

if (empty($cedula) || empty($clave1)) {
    echo "<script>alert('Debe ingresar usuario y contraseña.'); window.history.back();</script>";
    exit;
}

if (!isset($_SESSION['intentos'])) {
    $_SESSION['intentos'] = 0;
}

if ($_SESSION['intentos'] >= 5) {
    echo "<script>alert('Demasiados intentos. Intente más tarde.'); window.location.href='login.php';</script>";
    exit;
}

$stmt = $pdo->prepare("SELECT cedula_usuario, clave_usuario FROM usuarios WHERE cedula_usuario = :cedula");
$stmt->bindParam(':cedula', $cedula, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $usuario = $stmt->fetch();

    if (password_verify($clave1, $usuario['clave_usuario'])) {
        session_regenerate_id(true);
        $_SESSION['usrcedula'] = $usuario['cedula_usuario'];
        $_SESSION['logged'] = true;
        $_SESSION['intentos'] = 0;

        echo "<script>window.location.href = 'index.php';</script>";
        exit;
    }
}

$_SESSION['intentos']++;
echo "<script>alert('Usuario y/o contraseña incorrecta.'); window.history.back();</script>";
exit;