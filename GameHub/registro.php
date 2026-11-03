<?php
require_once "php/conexion.php";
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = trim($_POST["nombre"]);
    $correo = trim($_POST["correo"]);
    $contrasena = $_POST["contrasena"];

    if($nombre == "" || $correo == "" || $contrasena == ""){
        $mensaje = "Completá todos los campos";

    }else{
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $consulta = $conexion->prepare(
            "INSERT INTO usuarios (nombre, correo, contrasena)
             VALUES (?, ?, ?)"
        );

        $consulta->bind_param("sss", $nombre, $correo, $hash);
        if($consulta->execute()){
            $mensaje = "¡Cuenta creada correctamente!";
        }else{
            if($conexion->errno == 1062){
                $mensaje = "Ese correo ya está registrado.";
            }else{
                $mensaje = "Ocurrió un error al crear la cuenta.";
            }
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
    <title>Registrarse - GameHub</title>
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
        <h2>Crear una cuenta</h2>
        <p>Unite a la comunidad de GameHub.</p>

        <?php if ($mensaje != "") { ?>
            <p><?php echo htmlspecialchars($mensaje); ?></p>
        <?php } ?>

        <form action="registro.php" method="POST">
            <label for="nombre">Nombre de usuario</label>
            <input type="text" id="nombre" name="nombre"
                   maxlength="100" required>

            <label for="correo">Correo electrónico</label>
            <input type="email" id="correo" name="correo"
                   maxlength="150" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena"
                   required>

            <button type="submit" class="boton">Registrarse</button>
        </form>
    </main>

    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos.</p>
    </footer>

</body>
</html>
