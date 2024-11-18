<?php 
session_start();

// Mostrar mensaje de éxito si existe
if(isset($_SESSION['mensaje'])) {
    echo "<p class='mensaje-exito'>" . $_SESSION['mensaje'] . "</p>";
    unset($_SESSION['mensaje']);
}

// Verificar si el usuario está logueado
if(!isset($_SESSION['usuario'])){
    header('Location: login.php');
    exit;
}

$mensaje = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Sanitizar y recoger datos del formulario
    $nombre = filter_var(strtolower($_POST['nombres']), FILTER_SANITIZE_STRING);
    $apellidos = filter_var($_POST['apellidos'], FILTER_SANITIZE_STRING);
    $identificacion = filter_var($_POST['identificacion'], FILTER_SANITIZE_STRING);
    $sexo = filter_var($_POST['sexo'], FILTER_SANITIZE_STRING);
    $fecha = filter_var($_POST['fechaNacimiento'], FILTER_SANITIZE_STRING);

    // Validar que todos los campos estén llenos
    if(empty($nombre) || empty($apellidos) || empty($identificacion) || empty($sexo) || empty($fecha)){
        $mensaje .= 'Por favor rellena todos los datos correctamente.<br />';
    } else {    
        try {
            $conexion = new PDO('mysql:host=localhost;dbname=centromedico', 'root', '');
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar si ya existe un paciente con esa identificación
            $statement = $conexion->prepare('SELECT * FROM pacientes WHERE pacIdentificacion = :id LIMIT 1');
            $statement->execute(array(':id' => $identificacion));
            $resultado = $statement->fetch();

            if($resultado){
                $mensaje .= 'Ya existe un paciente con esa identificación.<br />';
            } else {
                // Insertar nuevo paciente
                $statement = $conexion->prepare(
                    'INSERT INTO pacientes (pacIdentificacion, pacNombre, pacApellidos, pacFechaNacimiento, pacSexo) 
                     VALUES (:id, :nombre, :apellidos, :fecha, :sexo)'
                );

                $resultado = $statement->execute(array(
                    ':id' => $identificacion,
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':fecha' => $fecha,
                    ':sexo' => $sexo
                ));

                if($resultado){
                    $_SESSION['mensaje'] = 'Paciente agregado correctamente.';
                    header('Location: pacientes.php');
                    exit;
                } else {
                    $mensaje .= 'Error al agregar el paciente. Por favor, intente nuevamente.<br />';
                }
            }
        } catch(PDOException $e) {
            $mensaje .= 'Error de base de datos: ' . $e->getMessage() . '<br />';
        }
    }
}

// Cargar la vista
require 'vista/agg_pacientes_vista.php';
?>