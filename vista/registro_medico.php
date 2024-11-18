<?php include 'plantillas/header.php'; ?>
<div class="wrapp">
    <section class="formulario-registro">
        <h2>Registro de Médico</h2>
        <form action="../procesar_registro_medico.php" method="POST">
            <input type="text" name="nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" placeholder="Apellido" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="text" name="especialidad" placeholder="Especialidad" required>
            <input type="submit" value="Registrarse">
        </form>
    </section>
</div>
<?php include 'plantillas/footer.php'; ?>