<?php
session_start();

$erroresLogin = $_SESSION['errores_login'] ?? [];
unset($_SESSION['errores_login']);


// Verificamos si hay errores de registro guardados en la sesión
$erroresRegistro = $_SESSION['errores_registro'] ?? [];
$registroActivo = !empty($erroresRegistro); // Si hay errores, activamos el tab de registro
unset($_SESSION['errores_registro']);

// También guardamos temporalmente los datos ingresados
$datosFormulario = $_SESSION['datos_registro'] ?? [];
unset($_SESSION['datos_registro']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login y Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        main {
            flex-grow: 1;
            height: 100vh;
            width: 100%;
            background-image: url('../test_natura/fondos/movil.jpeg') !important;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        @media (min-width: 570px) {
            main {
                background-image: url('../test_natura/fondos/portrait.jpeg') !important;
            }
        }
    </style>
</head>
<body>
<main>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <?php if (isset($_SESSION['email'])): ?>
                <span class="navbar-text">Sesión iniciada como: "<?php echo $_SESSION['nombre_usuario']; ?>"</span>
                <form action="logout.php" method="POST" style="display:inline;">
                    <button type="submit" class="button">Cerrar sesión</button>
                </form>
            <?php else: ?>
                <span class="navbar-text">No has iniciado sesión</span>
            <?php endif; ?>
        </div>
    </nav>

    <div class="login_outer">
        <div class="login-wrap">
            <div class="login-html">
                <input id="tab-1" type="radio" name="tab" class="sign-in" <?= $registroActivo ? '' : 'checked' ?>>
                <label for="tab-1" class="tab">Iniciar Sesión</label>
                <input id="tab-2" type="radio" name="tab" class="sign-up" <?= $registroActivo ? 'checked' : '' ?>>
                <label for="tab-2" class="tab">Registrarse</label>

                <div class="login-form">
                    <!-- Login -->
                    
                    <div class="sign-in-htm">
                        <form method="POST" action="login.php">
                            <input type="hidden" name="action" value="login">
                            <div class="group">
                                <label for="loginCorreo" class="label">Correo</label>
                                <input id="loginCorreo" name="loginCorreo" type="email" class="input" required>
                            </div>
                            <div class="group">
                                <label for="loginPassword" class="label">Contraseña</label>
                                <input id="loginPassword" name="loginPassword" type="password" class="input" data-type="password" required>
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Iniciar Sesión">
                            </div>
                        </form>
                        
                        <a href="google_login.php" class="styleGoogle">
                            <img src="https://lh3.googleusercontent.com/COxitqgJr1sJnIDe8-jiKhxDx1FrYbtRHKJ9z_hELisAlapwE9LUPh6fcXIfb5vwpbMl4xl9H9TRFPc5NOO8Sb3VSgIBrfRYvW6cUA" alt="Iniciar sesión con Google">
                            Iniciar sesión con Google
                        </a>
                        
                        <?php if (!empty($erroresLogin)): ?>
                            <div class="error-box">
                                <ul>
                                    <?php foreach ($erroresLogin as $error): ?>
                                        <li style="color:red;"><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div>
                            <hr>
                            <div class="foot-lnk">
                                <a href="#forgot">¿Olvidaste tu contraseña?</a>
                            </div>
                        </div>
                    </div>

                    <!-- Registro -->
                    <div class="sign-up-htm">
                        <form method="POST" action="register.php">
                            <input type="hidden" name="action" value="register">
                            <div class="group">
                                <label for="userName" class="label">Nombre de Usuario</label>
                                <input id="userName" name="userName" type="text" class="input" required value="<?= htmlspecialchars($datosFormulario['userName'] ?? '') ?>">
                            </div>
                            <div class="group">
                                <label for="registerCorreo" class="label">Correo Electrónico</label>
                                <input id="registerCorreo" name="registerCorreo" type="email" class="input" required value="<?= htmlspecialchars($datosFormulario['registerCorreo'] ?? '') ?>">
                            </div>
                            <div class="group">
                                <label for="registerPSW" class="label">Contraseña</label>
                                <input id="registerPSW" name="registerPSW" type="password" class="input" data-type="password" required>
                            </div>
                            <div class="group">
                                <label for="confirmarContraseña" class="label">Confirmar Contraseña</label>
                                <input id="confirmarContraseña" name="confirmarContraseña" type="password" class="input" data-type="password" required>
                            </div>
                            <div class="group">
                                <input type="submit" class="button" value="Registrarse">
                            </div>

                            <?php if (!empty($erroresRegistro)): ?>
                                <div class="error-box">
                                    <ul>
                                        <?php foreach ($erroresRegistro as $error): ?>
                                            <li style="color:red;"><?= htmlspecialchars($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="hr"></div>
                            <div class="foot-lnk">
                                <label for="tab-1">¿Ya tienes una cuenta?</label>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
</body>
</html>
