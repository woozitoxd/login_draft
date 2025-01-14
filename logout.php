<?php
session_start();
session_destroy(); // Cierra la sesion, usando la funcion para destruirla
header('Location: index.php'); // Redirige a la pag de inicio 
exit(); //salgo
?>