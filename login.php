<?php
require_once('class_user.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errores = [];

    if (!empty($_POST["loginCorreo"]) && !empty($_POST["loginPassword"])) {
        $correo = trim($_POST["loginCorreo"]);
        $password = trim($_POST["loginPassword"]);

        $usuario = new Usuario("", "", "", "");
        $resultado = $usuario->consultar($correo, $password);

        if ($resultado !== null && $resultado !== 0) {
            $_SESSION['id'] = $resultado['id_usuario'];
            $_SESSION['email'] = $correo;
            $_SESSION['nombre_usuario'] = $resultado['nombre_usuario'];
            header('Location: ../test_natura/index.php');
            exit;
        } elseif ($resultado === 0) {
            $errores[] = 'Usted tiene su cuenta bloqueada, contacte a soporte.';
        } else {
            $errores[] = '¡El usuario o la contraseña son incorrectos!';
        }

    } else {
        $errores[] = 'Por favor, completa todos los campos.';
    }

    // Si hay errores, los guardo en sesión y redirijo
    if (!empty($errores)) {
        $_SESSION['errores_login'] = $errores;
        header("Location: index_login.php");
        exit;
    }
}
?>
