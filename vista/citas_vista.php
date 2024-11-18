<?php
$mensaje = '';
try {
    $conexion = new PDO('mysql:host=localhost;dbname=centromedico', 'root', '');
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $consulta = $conexion->prepare("
        SELECT c.*, m.medEspecialidad 
        FROM citas c
        LEFT JOIN medicos m ON c.citMedico = m.mednombres
        ORDER BY c.idcita
    ");

    $consulta->execute();
    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);

    if (empty($resultados)) {
        $mensaje .= 'NO HAY CITAS PARA MOSTRAR';
    }
} catch(PDOException $e) {
    $mensaje .= "Error: " . $e->getMessage();
}
?>

<?php include 'plantillas/header.php'; ?>
<section class="main">
    <div class="wrapp">
        <?php include 'plantillas/nav.php'; ?>
        <article>
            <div class="mensaje">
                <h2>CITAS</h2>
            </div>
            <a class="agregar" href="agregarcitas.php">Agregar Citas</a>
            <?php if (!empty($resultados)): ?>
                <div class="tabla-container" style="max-height: 400px; overflow-y: auto;">
                    <table class="tabla" id="tablaCitas">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Medico</th>
                                <th>Especialidad</th>
                                <th>Consultorio</th>
                                <th>Estado</th>
                                <th colspan="2">Opciones</th>
                            </tr>
                            <tr>
                                <th><input type="text" id="filtroId" placeholder="Filtrar ID"></th>
                                <th><input type="text" id="filtroFecha" placeholder="Filtrar Fecha"></th>
                                <th><input type="text" id="filtroHora" placeholder="Filtrar Hora"></th>
                                <th><input type="text" id="filtroPaciente" placeholder="Filtrar Paciente"></th>
                                <th><input type="text" id="filtroMedico" placeholder="Filtrar Médico"></th>
                                <th><input type="text" id="filtroEspecialidad" placeholder="Filtrar Especialidad"></th>
                                <th><input type="text" id="filtroConsultorio" placeholder="Filtrar Consultorio"></th>
                                <th><input type="text" id="filtroEstado" placeholder="Filtrar Estado"></th>
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados as $cita): ?>
                                <tr>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['idcita']); ?></td>
                                    <td class="mayusculas"><?php echo date('d/m/Y', strtotime($cita['citfecha'])); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['cithora']); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['citPaciente']); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['citMedico']); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['medEspecialidad']); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['citConsultorio']); ?></td>
                                    <td class="mayusculas"><?php echo htmlspecialchars($cita['citestado']); ?></td>
                                    <td class="centrar"><a href="actualizarcitas.php?idcita=<?php echo $cita['idcita']; ?>" class="editar">Editar</a></td>
                                    <td class="centrar"><a href="eliminar_citas.php?idcita=<?php echo $cita['idcita']; ?>" class="eliminar">Eliminar</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (!empty($mensaje)): ?>
                <ul>
                    <li><?php echo $mensaje; ?></li>
                </ul>
            <?php endif; ?>
        </article>
    </div>
</section>

<script>
function filtrarTabla() {
    var tabla = document.getElementById("tablaCitas");
    var filas = tabla.getElementsByTagName("tr");
    
    var filtros = [
        document.getElementById("filtroId").value.toUpperCase(),
        document.getElementById("filtroFecha").value.toUpperCase(),
        document.getElementById("filtroHora").value.toUpperCase(),
        document.getElementById("filtroPaciente").value.toUpperCase(),
        document.getElementById("filtroMedico").value.toUpperCase(),
        document.getElementById("filtroEspecialidad").value.toUpperCase(),
        document.getElementById("filtroConsultorio").value.toUpperCase(),
        document.getElementById("filtroEstado").value.toUpperCase()
    ];

    for (var i = 2; i < filas.length; i++) {
        var mostrarFila = true;
        var celdas = filas[i].getElementsByTagName("td");
        
        for (var j = 0; j < filtros.length; j++) {
            if (filtros[j]) {
                if (celdas[j].textContent.toUpperCase().indexOf(filtros[j]) === -1) {
                    mostrarFila = false;
                    break;
                }
            }
        }
        
        filas[i].style.display = mostrarFila ? "" : "none";
    }
}

// Agregar event listeners a los campos de filtro
document.getElementById("filtroId").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroFecha").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroHora").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroPaciente").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroMedico").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroEspecialidad").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroConsultorio").addEventListener("keyup", filtrarTabla);
document.getElementById("filtroEstado").addEventListener("keyup", filtrarTabla);
</script>

<?php include 'plantillas/footer.php'; ?>
</body>
</html>