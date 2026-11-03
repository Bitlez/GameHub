
<?php

session_start();

require_once "php/conexion.php";

//recibimos el ID del juego desde la URL
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

//buscamos el juego en la base de datos
if ($id && $id > 0){

    $consulta = $conexion->prepare(
        "SELECT * FROM juegos WHERE id = ?"
    );

    $consulta->bind_param("i", $id);
    $consulta->execute();

    $resultado = $consulta->get_result();
    $juego = $resultado->fetch_assoc();

    $consulta->close();

}else{
    $juego = null;
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $juego ? htmlspecialchars($juego["nombre"]) : "Juego no encontrado"; ?>
        - GameHub
    </title>

    <link rel="stylesheet" href="estilos.css">
</head>

<body>

    <header>
        <nav class="navbar">
            <h1 class="logo">GameHub</h1>

            <div class="menu">
                <a href="index.php">Inicio</a>
                <a href="juegos.php">Juegos</a>

                <?php if(isset($_SESSION["usuario_id"])){ ?>

                    <span class="usuario-menu">
                        Hola,
                        <?php echo htmlspecialchars($_SESSION["usuario_nombre"]); ?>
                    </span>

                    <a href="perfil.php">Mi perfil</a>
                    <a href="php/logout.php">Cerrar sesión</a>

                <?php }else{ ?>

                    <a href="php/registro.php">Registrarse</a>
                    <a href="login.php">Iniciar sesión</a>

                <?php } ?>
            </div>
        </nav>
    </header>

    <main class="detalle-contenedor">

        <?php if($juego){ ?>

            <h2>
                <?php echo htmlspecialchars($juego["nombre"]); ?>
            </h2>

            <div class="detalle-juego">

                <div class="detalle-imagen">

                    <?php if(!empty($juego["imagen"])){ ?>

                        <img
                            src="<?php echo htmlspecialchars($juego["imagen"]); ?>"
                            alt="<?php echo htmlspecialchars($juego["nombre"]); ?>">

                    <?php }else{ ?>

                        <p>Imagen no disponible.</p>

                    <?php } ?>

                </div>

                <div class="detalle-informacion">

                    <p>
                        <strong>Género:</strong>
                        <?php echo htmlspecialchars($juego["genero"]); ?>
                    </p>

                    <p>
                        <strong>Año de lanzamiento:</strong>

                        <?php
                        echo !empty($juego["anio_lanzamiento"])
                            ? htmlspecialchars((string) $juego["anio_lanzamiento"])
                            : "Próximamente";
                        ?>
                    </p>

                    <h3>Sobre el juego</h3>

                    <p>
                        <?php
                        echo !empty($juego["descripcion_completa"])
                            ? nl2br(htmlspecialchars($juego["descripcion_completa"]))
                            : "Todavía no agregamos una descripción completa.";
                        ?>
                    </p>

                    <h3>¿Sabías que...?</h3>

                    <p>
                        <?php
                        echo !empty($juego["curiosidad"])
                            ? nl2br(htmlspecialchars($juego["curiosidad"]))
                            : "Próximamente agregaremos una curiosidad.";
                        ?>
                    </p>

                </div>

            </div>

        <?php } else { ?>

            <h2>Juego no encontrado</h2>

            <p>El videojuego que buscás no existe en nuestro catálogo.</p>

        <?php } ?>

        <a href="juegos.php" class="boton">
            Volver al inventario
        </a>

    </main>

    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos.</p>
    </footer>

</body>

</html>
