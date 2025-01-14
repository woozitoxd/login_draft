<?php
// Incluir el archivo de conexión a la base de datos
require_once('conexion_bbdd.php');
require_once('class_user.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $errores = []; // Inicializamos un array para los errores

    if ($action === "register") {
        // Capturamos los datos del registro
        $nombreUsuario = isset($_POST["userName"]) ? trim($_POST["userName"]) : null;
        $correo = isset($_POST["registerCorreo"]) ? trim($_POST["registerCorreo"]) : null;
        $password = isset($_POST["registerPSW"]) ? trim($_POST["registerPSW"]) : null;
        $confirmarPassword = isset($_POST["confirmarContraseña"]) ? trim($_POST["confirmarContraseña"]) : null;

        // Validaciones
        if (empty($nombreUsuario)) {
            $errores[] = "El nombre de usuario no puede estar vacío.";
        }

        if (empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Debe ingresar un correo válido.";
        }

        if (empty($password) || strlen($password) < 8) {
            $errores[] = "La contraseña debe tener al menos 8 caracteres.";
        }

        if ($password !== $confirmarPassword) {
            $errores[] = "Las contraseñas no coinciden.";
        }

        // Verificar si el correo ya existe
        $queryCorreo = "SELECT COUNT(*) FROM usuarios WHERE email = :correo";
        $stmCorreo = $conn->prepare($queryCorreo);
        $stmCorreo->bindParam(':correo', $correo);
        $stmCorreo->execute();
        if ($stmCorreo->fetchColumn() > 0) {
            $errores[] = "El correo electrónico ya está registrado.";
        }

        // Si no hay errores, procedemos a registrar al usuario
        if (empty($errores)) {
            $usuario = new Usuario();
            $result = $usuario->registrar( $correo, $password, $nombreUsuario);

            if ($result['status'] == 'success') {
                // Redirigir a la página index después de un registro exitoso
                header("Location: index.php");
                exit;
            } else {
                // Redirigir con un mensaje de error
                echo("Error:" . urlencode($result['message']));
                exit;
            }
        } else {
            // Si hay errores, redirigir con el mensaje de error
            $erroresStr = implode(", ", $errores);
            header("Location: register.php?error=" . urlencode($erroresStr));
            exit;
        }
    }
}
?>
