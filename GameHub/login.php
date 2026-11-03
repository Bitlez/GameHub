
<?php
session_start();

require_once "php/conexion.php";

$mensaje = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];

    if ($correo == "" || $contrasena == ""){
        $mensaje = "Completá todos los campos.";

    }else{
        $consulta = $conexion->prepare(
            "SELECT id, nombre, correo, contrasena
             FROM usuarios
             WHERE correo = ?"
        );

        $consulta->bind_param("s", $correo);
        $consulta->execute();

        $resultado = $consulta->get_result();

        if($usuario = $resultado->fetch_assoc()){

            if(password_verify($contrasena, $usuario["contrasena"])){

                session_regenerate_id(true);

                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nombre"] = $usuario["nombre"];
                $_SESSION["usuario_correo"] = $usuario["correo"];

                header("Location: index.php");
                exit();

            }else{
                $mensaje = "Correo o contraseña incorrectos.";
            }

        }else{
            $mensaje = "Correo o contraseña incorrectos.";
        }

        $consulta->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - GameHub</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <h1 class="logo">GameHub</h1>

            <div class="menu">
                <a href="index.php">Inicio</a>
                <a href="juegos.php">Juegos</a>
                <a href="registro.php">Registrarse</a>
                <a href="login.php">Iniciar sesión</a>
            </div>
        </nav>
    </header>

    <main class="formulario-contenedor">
        <h2>Iniciar sesión</h2>
        <p>¡Re piola poder volver a verte!</p>

        <?php if ($mensaje != "") { ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>

        <form action="login.php" method="POST">
            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   maxlength="150" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena"
                   required>

            <button type="submit" class="boton">Ingresar</button>
        </form>
    </main>

    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos</p>
    </footer>

</body>
</html>
