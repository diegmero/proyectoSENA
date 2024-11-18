<?php 
session_start();
if(!isset($_SESSION['usuario'])){
    header('Location: login.php');
}

try{
    $conexion = new PDO('mysql:host=localhost;dbname=centromedico','root','');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener las especialidades de la base de datos
    $stmtEspecialidades = $conexion->query("SELECT * FROM especialidades ORDER BY espNombre");
    $especialidades = $stmtEspecialidades->fetchAll(PDO::FETCH_ASSOC);

}catch(PDOException $e){
    echo "ERROR: " . $e->getMessage();
    die();
}

if($_SERVER['REQUEST_METHOD']=='POST'){
    $usuario = filter_var(strtolower($_POST['usuario']),FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $roll = $_POST['roll'];
    $errores ='';

    // Campos adicionales para médicos
    $especialidad = isset($_POST['especialidad']) ? $_POST['especialidad'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';
    $correo = isset($_POST['correo']) ? filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL) : '';

    if(empty($usuario) or empty($password)){
        $errores.= '<li>Por favor rellena todos los datos correctamente</li>';
    }
    else{
        $statement = $conexion->prepare(
            'SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1');
        $statement->execute(array(':usuario'=>$usuario));
        $resultado = $statement->fetch();

        if($resultado != false){
            $errores .='<li>El nombre de usuario ya existe</li>';
        }

        $password = hash('sha512',$password);
        $password2 = hash('sha512',$password2);
        if($password2 != $password){
            $errores .= '<li>Las contraseñas no son iguales</li>';
        }

        if($roll == 'medico' && (empty($especialidad) || empty($telefono) || empty($correo))){
            $errores .= '<li>Por favor, completa todos los campos para el registro de médico</li>';
        }
    }

    if($errores==''){
        $conexion->beginTransaction();
        try {
            // Insertar en la tabla usuarios
            $statement = $conexion->prepare(
                'INSERT INTO usuarios (usuario, pass, nombres, apellidos, Roll) 
                VALUES (:usuario, :pass, :nombres, :apellidos, :roll)');
            $statement->execute(array(
                ':usuario' => $usuario,
                ':pass'=> $password2,
                ':nombres'=> $nombres,
                ':apellidos'=> $apellidos,
                ':roll'=> $roll
            ));

            $userId = $conexion->lastInsertId();

            // Si el rol es médico, insertar en la tabla medicos
            if($roll == 'medico'){
                $statementMedico = $conexion->prepare(
                    'INSERT INTO medicos (idMedico, medidentificacion, mednombres, medapellidos, medEspecialidad, medtelefono, medcorreo) 
                    VALUES (:id, :identificacion, :nombres, :apellidos, :especialidad, :telefono, :correo)');
                $statementMedico->execute(array(
                    ':id' => $userId,
                    ':identificacion' => $usuario, // Usando el nombre de usuario como identificación
                    ':nombres' => $nombres,
                    ':apellidos' => $apellidos,
                    ':especialidad' => $especialidad,
                    ':telefono' => $telefono,
                    ':correo' => $correo
                ));
            }

            $conexion->commit();
            header('Location: usuarios.php');
        } catch(Exception $e) {
            $conexion->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }
}

require 'vista/registro_vista.php';
?>