<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub by Santiago Guinel</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="js/script.js"></script>
</head>

<body>
<header>
    <nav class="navbar">
    <h1 class="logo">GameHub</h1>

    <div class="menu">
        <a href="index.php">Inicio</a>
        <a href="juegos.php">Juegos</a>

        <?php if (isset($_SESSION["usuario_id"])) { ?>

            <span class="usuario-menu">
                Hola, <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
            </span>

            <a href="perfil.php">Mi perfil</a>
            <a href="php/logout.php">Cerrar sesión</a>

        <?php } else { ?>

            <a href="php/registro.php">Registrarse</a>
            <a href="login.php">Iniciar sesión</a>

        <?php } ?>
    </div>
</nav>
    <section class="portada">
        <h2>DESCUBRÍ TU PRÓXIMO JUEGO</h2>

        <p>
            ¡Explorá nuestra colección de videojuegos
            y encontrá tu próxima aventura!
        </p>

        <a href="juegos.php" class="boton">Ver juegos</a>
    </section>

    <section class="juegos-destacados">

    <h2>Juegos destacados</h2>
    <div class="buscador">
    <input type="text" id="buscarJuego" placeholder="Buscar un videojuego...">
    </div>

    <div class="contenedor-juegos juegos-destacados">

    <?php

    require_once "php/conexion.php";

    $consulta = "SELECT * FROM juegos LIMIT 3";
    $resultado = $conexion->query($consulta);

    while($juego = $resultado->fetch_assoc()){
    ?>

        <div class="tarjeta-juego">
            <img src="<?php echo $juego['imagen']; ?>"
                 alt="<?php echo $juego['nombre']; ?>">

            <div class="info-juego">
                <h3><?php echo $juego['nombre']; ?></h3>
                <p><?php echo $juego['genero']; ?></p>
            </div>
        </div>

    <?php
    }
    ?>

</div>

</section>
</main>
    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos</p>
    </footer>

</body>
</html>