<?php
session_start();
require_once('conexion_bbdd.php');
require_once('class_user.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? null;
    $errores = [];

    if ($action === "register") {
        $nombreUsuario = trim($_POST["userName"] ?? '');
        $correo = trim($_POST["registerCorreo"] ?? '');
        $password = trim($_POST["registerPSW"] ?? '');
        $confirmarPassword = trim($_POST["confirmarContraseña"] ?? '');

        if (empty($nombreUsuario)) $errores[] = "El nombre de usuario no puede estar vacío.";
        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) $errores[] = "Debe ingresar un correo válido.";
        if (empty($password) || strlen($password) < 8) $errores[] = "La contraseña debe tener al menos 8 caracteres.";
        if ($password !== $confirmarPassword) $errores[] = "Las contraseñas no coinciden.";

        $queryCorreo = "SELECT COUNT(*) FROM usuarios WHERE email = :correo";
        $stmCorreo = $conn->prepare($queryCorreo);
        $stmCorreo->bindParam(':correo', $correo);
        $stmCorreo->execute();
        if ($stmCorreo->fetchColumn() > 0) {
            $errores[] = "El correo electrónico ya está registrado.";
        }

        if (empty($errores)) {
            $usuario = new Usuario();
            $result = $usuario->registrar($correo, $password, $nombreUsuario);

            if ($result['status'] == 'success') {
                header("Location: index_login.php");
                exit;
            } else {
                $_SESSION['errores_registro'] = [$result['message']];
                header("Location: index_login.php");
                exit;
            }
        } else {
            $_SESSION['errores_registro'] = $errores;
            header("Location: index_login.php");
            exit;
        }
    }
}
?>