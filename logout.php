<?php
session_start();
session_destroy(); // Cierra la sesion, usando la funcion para destruirla
header('Location: index_login.php'); // Redirige a la pag de inicio 
exit(); //salgo
?>