<?php

session_start();
session_destroy();

require_once 'vendor/autoload.php';
require_once 'class_user.php';

session_start();

// Configuración de Google
$clientID = "80176499534-bkl6mb2rdhggkhft2gqk6270tufffrpu.apps.googleusercontent.com";
$clientSecret = "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";
$redirectUri = "http://localhost/login_draft/login_draft/google_login.php";

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Procesar el código de autorización
if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);

        // Obtener datos del usuario desde Google
        $oauth2 = new Google_Service_Oauth2($client);
        $userInfo = $oauth2->userinfo->get(); // Aquí se obtienen los datos del usuario

        // Verificar si se obtuvo información válida
        if (!empty($userInfo)) {
            $_SESSION['id_usuario'] = $userInfo->id ?? null;
            $_SESSION['nombre_usuario'] = $userInfo->name ?? "Usuario";
            $_SESSION['email'] = $userInfo->email ?? null;

            // Datos obtenidos de Google
            $googleId = $userInfo->id;  // ID único del usuario de Google
            $googleEmail = $userInfo->email; // Email asociado a Google
            $nombreUsuario = $userInfo->name; // Nombre del usuario

            // Instancia de la clase Usuario
            $usuario = new Usuario();

            // Registrar usuario
            $usuario->registrarUsuario($googleId, $googleEmail, $nombreUsuario);

            // Redirigir al índice después del login exitoso
            //header('Location: index.php');
            //exit;
        } else {
            echo "No se pudo obtener la información del usuario.";
        }
    } catch (Exception $e) {
        echo "Error durante la autenticación: " . $e->getMessage();
    }
}

// Mostrar mensaje de bienvenida
if (!isset($_SESSION['nombre_usuario'])) {
    $authUrl = $client->createAuthUrl();
    echo '<a href="' . $authUrl . '">Iniciar sesión con Google</a>';
} else {
    $nombreUsuario = $_SESSION['nombre_usuario'] ?? "Usuario";
    print_r($_SESSION);
    echo "$googleId $googleEmail $nombreUsuario";
    echo '¡Hola, ' . htmlspecialchars($nombreUsuario) . '! Has iniciado sesión con Google.';
}

?>


