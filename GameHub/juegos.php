
<?php

session_start();

require_once "php/conexion.php";

//Buscamos todos los juegos de la base de datos
$consulta = "SELECT * FROM juegos";
$resultado = $conexion->query($consulta);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juegos - GameHub</title>
    <link rel="stylesheet" href="estilos.css">
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
                        Hola,
                        <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
                    </span>

                    <a href="perfil.php">Mi perfil</a>
                    <a href="php/logout.php">Cerrar sesión</a>

                <?php } else { ?>

                    <a href="php/registro.php">Registrarse</a>
                    <a href="login.php">Iniciar sesión</a>

                <?php } ?>
            </div>
        </nav>
    </header>

    <main class="catalogo-contenedor">

        <h2>Inventario de videojuegos</h2>

        <p>Explorá nuestra colección y descubrí tu próximo juego favorito.</p>

        <div class="contenedor-juegos">

            <?php while ($juego = $resultado->fetch_assoc()) { ?>

                <a
                    href="detalle_juego.php?id=<?php echo $juego['id']; ?>"
                    class="tarjeta-juego">

                    <?php if (!empty($juego["imagen"])) { ?>

                        <img
                            src="<?php echo htmlspecialchars($juego["imagen"]); ?>"
                            alt="<?php echo htmlspecialchars($juego["nombre"]); ?>">

                    <?php } ?>

                    <div class="info-juego">

                        <h3>
                            <?php echo htmlspecialchars($juego["nombre"]); ?>
                        </h3>

                        <p>
                            <?php echo htmlspecialchars($juego["genero"]); ?>
                        </p>

                    </div>

                </a>

            <?php } ?>

        </div>

    </main>

    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos</p>
    </footer>

</body>

</html>
