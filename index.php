
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login y Registro</title>

</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <?php session_start(); ?>
            <?php if (isset($_SESSION['email'])): ?>
                <!-- Si la sesión está iniciada -->
                <span class="navbar-text">Sesión iniciada como: "<?php echo $_SESSION['nombre_usuario']; ?>"   </span>
                <form action="logout.php" method="POST" style="display:inline;">
                    <button type="submit" class="button">Cerrar sesión</button>
                </form>
            <?php else: ?>
                <!-- Si no hay sesión activa -->
                <span class="navbar-text">No has iniciado sesión</span>
            <?php endif; ?>
        </div>
    </nav>



    <div class="login-wrap">
        <div class="login-html">
            <input id="tab-1" type="radio" name="tab" class="sign-in" checked>
            <label for="tab-1" class="tab">Iniciar Sesión</label>
            <input id="tab-2" type="radio" name="tab" class="sign-up">
            <label for="tab-2" class="tab">Registrarse</label>

            <div class="login-form">
                <!-- Formulario de Login -->
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
                        <img src="https://lh3.googleusercontent.com/COxitqgJr1sJnIDe8-jiKhxDx1FrYbtRHKJ9z_hELisAlapwE9LUPh6fcXIfb5vwpbMl4xl9H9TRFPc5NOO8Sb3VSgIBrfRYvW6cUA" alt="">
                        Iniciar sesión con Google
                    </a>

                    <div>
                        <hr>    
                        <div class="foot-lnk">
                            <a href="#forgot">¿Olvidaste tu contraseña?</a>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Registro -->
                <div class="sign-up-htm">
                    <form method="POST" action="register.php">
                        <input type="hidden" name="action" value="register">
                        <div class="group">
                            <label for="userName" class="label">Nombre de Usuario</label>
                            <input id="userName" name="userName" type="text" class="input" required>
                        </div>
                        <div class="group">
                            <label for="registerCorreo" class="label">Correo Electrónico</label>
                            <input id="registerCorreo" name="registerCorreo" type="email" class="input" required>
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
                        <div class="hr"></div>
                        <div class="foot-lnk">
                            <label for="tab-1">¿Ya tienes una cuenta?</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
