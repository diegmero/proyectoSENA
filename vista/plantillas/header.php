<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Sistema Medico SENA</title>
	<link rel="stylesheet" href="css/estilos.css">
	<link href="https://fonts.googleapis.com/css?family=Antic" rel="stylesheet">
	<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
	<link rel="icon" type="image/x-icon" href="img/favicon.png">
	<link rel="stylesheet" href="/centromedico/css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
		<div class="wrapp title encabezado">
				<a href="index.php" title="CenterMedicine">Gestión de Citas</a>
				<?php if(isset($_SESSION['usuario'])): ?>
                <div class="mensaje-bienvenida">
                    <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></h3>
                </div>
            <?php endif; ?>
            <div class="usuario">
                <a href="cerrar.php" title="Cerrar Sesion"> Salir</a>                
            </div>
		</div>
	</header>