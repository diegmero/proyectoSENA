<?php include 'plantillas/header.php'; ?>
<section class="main">
    <div class="wrapp">
        <?php include 'plantillas/nav.php'; ?>
        <article>
            <div class="mensaje">
                <h2>USUARIOS</h2>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
                <h2>REGISTRAR USUARIOS</h2><br/>
                <input type="text" name="usuario" placeholder="Usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <input type="password" name="password2" placeholder="Repita la contraseña" required>
                <input type="text" name="nombres" placeholder="Nombres" required>
                <input type="text" name="apellidos" placeholder="Apellidos" required>
                <select name="roll" id="roll" onchange="toggleMedicoFields()">
                    <option value="admin">Admin</option>
                    <option value="Limitado">Limitado</option>
                    <option value="medico">Médico</option>
                </select>
                
                <div id="medicoFields" style="display: none;">
                    <select name="especialidad" id="especialidad">
                        <option value="">Seleccione una especialidad</option>
                        <?php foreach ($especialidades as $especialidad): ?>
                            <option value="<?php echo $especialidad['idespecialidad']; ?>">
                                <?php echo $especialidad['espNombre']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="tel" name="telefono" placeholder="Teléfono">
                    <input type="email" name="correo" placeholder="Correo electrónico">
                </div>

                <input type="submit" value="Registrar" />
                <?php if(!empty($errores)): ?>
                    <ul>
                        <?php echo $errores; ?>
                    </ul>
                <?php endif; ?>
            </form>
        </article>
    </div>
</section>

<script>
function toggleMedicoFields() {
    var roll = document.getElementById('roll').value;
    var medicoFields = document.getElementById('medicoFields');
    if (roll === 'medico') {
        medicoFields.style.display = 'block';
    } else {
        medicoFields.style.display = 'none';
    }
}
</script>

<?php include 'plantillas/footer.php'; ?>