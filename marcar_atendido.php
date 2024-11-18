<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != 'medico') {
    header('Location: login.php');
    exit;
}

$idcita = isset($_GET['idcita']) ? intval($_GET['idcita']) : 0;
$medicoLogueado = $_SESSION['usuario'];

if ($idcita > 0) {
    try {
        $conexion = new PDO('mysql:host=localhost;dbname=centromedico', 'root', '');
        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar si la cita pertenece al médico logueado
        $consulta = $conexion->prepare("SELECT * FROM citas WHERE idcita = :idcita AND citMedico = :medico");
        $consulta->execute([':idcita' => $idcita, ':medico' => $medicoLogueado]);
        
        if ($consulta->rowCount() > 0) {
            // Actualizar el estado de la cita
            $actualizar = $conexion->prepare("UPDATE citas SET citestado = 'atendido' WHERE idcita = :idcita");
            $actualizar->execute([':idcita' => $idcita]);
            
            header('Location: citas.php?mensaje=Cita marcada como atendida');
        } else {
            header('Location: citas.php?mensaje=No tiene permiso para modificar esta cita');
        }
    } catch(PDOException $e) {
        header('Location: citas.php?mensaje=Error: ' . $e->getMessage());
    }
} else {
    header('Location: citas.php?mensaje=ID de cita inválido');
}
exit;