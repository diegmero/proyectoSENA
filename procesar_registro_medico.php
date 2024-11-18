<?php
session_start();
require_once 'conexion.php';  // Asegúrate de tener un archivo de conexión

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $especialidad = $_POST['especialidad'];

    try {
        $stmt = $conexion->prepare("INSERT INTO medicos (nombre, apellido, email, password, especialidad) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $email, $password, $especialidad]);

        $_SESSION['mensaje'] = "Registro exitoso. Por favor, inicie sesión.";
        header('Location: login.php');
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>