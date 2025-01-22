<?php
require_once('conexion_bbdd.php');

class Usuario 
{
    private $conexion;
    
    private $id;
    private $nombre;
    private $correo;
    private $password;
    private $fechaNacimiento;
    public $email;
    public $id_google;
    public function __construct($id=null,$nombre =null, $correo =null, $password =null, $fechaNacimiento =null)
    {
        try {
            $this->conexion = $GLOBALS['conn']; // Conexión global a la base de datos
        } catch (PDOException $e) {
            die("Error en la conexión de base de datos: " . $e->getMessage());
        }
    }

    public function registrar($correo, $password, $nombreUsuario)
    {
        // Validar que el correo o nombre de usuario no existan
        $sqlValidacion = "SELECT COUNT(*) FROM usuarios WHERE email = :email OR nombre_usuario = :nombreUsuario";
        $stmtValidacion = $this->conexion->prepare($sqlValidacion);
        $stmtValidacion->bindParam(':email', $correo, PDO::PARAM_STR);
        $stmtValidacion->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $stmtValidacion->execute();

        if ($stmtValidacion->fetchColumn() > 0) {
            return ['status' => 'error', 'message' => 'El correo o nombre de usuario ya están registrados.'];
        }

        // Registro del usuario
        $sqlUsuario = "INSERT INTO usuarios (email, nombre_usuario, password, fecha_registro, estado) VALUES (:email, :nombreUsuario, :password, NOW(), 1)";
        $stmtUsuario = $this->conexion->prepare($sqlUsuario);

        // Encriptar la contraseña
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmtUsuario->bindParam(':email', $correo, PDO::PARAM_STR);
        $stmtUsuario->bindParam(':nombreUsuario', $nombreUsuario, PDO::PARAM_STR);
        $stmtUsuario->bindParam(':password', $hashedPassword, PDO::PARAM_STR);

        try {
            $this->conexion->beginTransaction();

            if ($stmtUsuario->execute()) {
                $this->conexion->commit();
                return ['status' => 'success', 'message' => 'Usuario registrado con éxito.'];
            } else {
                $this->conexion->rollBack();
                return ['status' => 'error', 'message' => 'Error al registrar el usuario'];
            }
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            return ['status' => 'error', 'message' => 'Error en la transacción: ' . $e->getMessage()];
        }
    }

    public function consultar($correo, $password) {
        try{
            $sql = "
            SELECT 
                id_usuario, 
                nombre_usuario, 
                email, 
                password,
                estado
            FROM 
                usuarios 
            WHERE 
                email = :correo;"; //uso el stored procedure que tengo en mi base de datos para traer los datos
            $stmt = $this->conexion->prepare($sql);  //traigo los datos tales como; nombre, correo, contraseña, id del rol, nombre del rol
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
            if($usuario['estado'] == 0){ //verificamos que el estado no sea 0, que en nuestra logica, significa bloqueado.
                return 0; //retorno 0 porque es la validacion que usaré
            }else if ($usuario && password_verify($password, $usuario['password'])) {
                unset($usuario['password']); // Unset de la contraseña para no almacenarla en la sesión
                return $usuario; // Retornar el usuario con el id_rol y el nombre del rol
            } else {
                return null; // Usuario no encontrado o contraseña incorrecta
            }

        }catch (PDOException $e ){
            return $e->getMessage();
        }
    }
    
    
    public function consultarGoogleAuth($google_id, $email) {
        $sql = "SELECT * FROM usuarios WHERE google_id = :google_id OR google_email = :email";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':google_id' => $google_id, ':email' => $email]);
        return $stmt->fetch(); // Asegúrate de que esta función devuelve lo esperado
    }

    function registrarUsuario($googleId, $nombre, $googleEmail) {
    
        try {
            // Preparar la consulta
            $stmt = $this->conexion->prepare("INSERT INTO usuarios 
                (nombre_usuario, email, google_id, google_email, fecha_registro, estado) 
                VALUES (:google_email, NULL, :google_id,  :nombre_usuario, NOW(), 1)");
    
            // Vincular parámetros
            $stmt->bindParam(':nombre_usuario', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':google_id', $googleId, PDO::PARAM_STR);
            $stmt->bindParam(':google_email', $googleEmail, PDO::PARAM_STR);
    
            // Ejecutar consulta
            if ($stmt->execute()) {
                echo "Usuario registrado correctamente.";
            } else {
                echo "Error al registrar usuario: " . implode(", ", $stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Error de PDO: " . $e->getMessage();
        }
    }
    
    
}


    
?>
