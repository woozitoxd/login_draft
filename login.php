<?php
require_once('class_user.php'); //incluyo la clase perfil porque existen metodos que utilizaré (consultar)
session_start(); // Inicia la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se enviaron datos y que no estén vacíos
    if (isset($_POST["loginCorreo"]) && isset($_POST["loginPassword"]) && !empty($_POST["loginCorreo"]) && !empty($_POST["loginPassword"])) {
        $correo = $_POST["loginCorreo"];
        $password = $_POST["loginPassword"];
        
        // Consulta para verificar si el usuario y la contraseña coinciden
        $usuario = new Usuario("", "", "", "");
        $resultado = $usuario->consultar($correo, $password); // Almaceno en resultado lo devuelto por la función consultar
        var_dump($resultado); // Esto te ayudará a ver qué contiene $resultado

        if ($resultado != null) { // Si obtuve algo en el valor devuelto
            $_SESSION['id'] = $resultado['id_usuario']; // Acceder correctamente al id_usuario
            $_SESSION['email'] = $correo;
            $_SESSION['nombre_usuario'] = $resultado['nombre_usuario']; // Acceder correctamente al nombre_usuario
        
            // Retornar una respuesta JSON indicando éxito
            echo 'Inicio de sesión exitoso';
            header('Location: index.php'); // Redirige a la pag de inicio 
        } else if($resultado === 0){
            echo 'Usted tiene su cuenta bloqueada, contacte a soporte';
        } else if ($resultado === null){
            // Retornar una respuesta JSON indicando error
            echo '¡El usuario o la contraseña son incorrectos!';
        }
        
    } else {
        // Retornar una respuesta JSON indicando error por falta de datos
        echo 'Por favor, completa todos los campos.';
    }
}
?>
