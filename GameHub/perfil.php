
<?php

session_start();

require_once "php/conexion.php";

//si no inició sesión, lo enviamos al login
if(!isset($_SESSION["usuario_id"])){
    header("Location: login.php");
    exit();
}


$mensajeFoto = "";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_perfil"])){
    if($_FILES["foto_perfil"]["error"] !== UPLOAD_ERR_OK){
        $mensajeFoto = "No se pudo subir la imagen.";

    }elseif($_FILES["foto_perfil"]["size"] > 2 * 1024 * 1024){
        $mensajeFoto = "La imagen no puede superar los 2 MB.";

    }else{
        $archivoTemporal = $_FILES["foto_perfil"]["tmp_name"];

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $tipo = $finfo->file($archivoTemporal);

        $formatosPermitidos = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp"
        ];

        if(!isset($formatosPermitidos[$tipo])){
            $mensajeFoto = "Usá una imagen JPG, PNG o WEBP.";

        }else{
            $carpeta = __DIR__ . "/img/perfiles/";

            $nombreArchivo = bin2hex(random_bytes(16))
                . "." . $formatosPermitidos[$tipo];

            $destino = $carpeta . $nombreArchivo;
            $rutaFoto = "img/perfiles/" . $nombreArchivo;

            if(move_uploaded_file($archivoTemporal, $destino)){

                $consultaFoto = $conexion->prepare(
                    "UPDATE usuarios SET foto_perfil = ? WHERE id = ?"
                );

                $consultaFoto->bind_param(
                    "si", $rutaFoto, $_SESSION["usuario_id"]
                );

                if($consultaFoto->execute()){
                    header("Location: perfil.php");
                    exit();
                }else{
                    unlink($destino);
                    $mensajeFoto = "No se pudo guardar la foto.";
                }

                $consultaFoto->close();

            }else{
                $mensajeFoto = "No se pudo guardar el archivo.";
            }
        }
    }
}


//buscamos los datos del usuario
$id = $_SESSION["usuario_id"];

$consulta = $conexion->prepare(
    "SELECT nombre, correo, fecha_registro, foto_perfil
     FROM usuarios
     WHERE id = ?"
);

$consulta->bind_param("i", $id);
$consulta->execute();

$resultado = $consulta->get_result();
$usuario = $resultado->fetch_assoc();

$consulta->close();

if(!$usuario){
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil - GameHub</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <h1 class="logo">GameHub</h1>

            <div class="menu">
                <a href="index.php">Inicio</a>
                <a href="juegos.php">Juegos</a>
                <a href="perfil.php">Mi perfil</a>
                <a href="php/logout.php">Cerrar sesión</a>
            </div>
        </nav>
    </header>

    <main class="perfil-contenedor">

        <h2>Mi perfil</h2>
        
    <?php if (!empty($usuario["foto_perfil"])) { ?>

    <img
        src="<?php echo htmlspecialchars($usuario["foto_perfil"]); ?>"
        alt="Foto de perfil"
        class="foto-perfil">

    <?php } else { ?> 

    <div class="foto-perfil-vacia">
        <?php echo strtoupper(substr($usuario["nombre"], 0, 1)); ?>
    </div>

    <?php } ?>

    <?php if ($mensajeFoto != "") { ?>
    <p><?php echo htmlspecialchars($mensajeFoto); ?></p>
    <?php } ?>

    
    <form action="perfil.php" method="POST" enctype="multipart/form-data">

    <label for="foto_perfil">Elegí tu foto de perfil</label>

    <input
        type="file"
        id="foto_perfil"
        name="foto_perfil"
        accept=".jpg,.jpeg,.png,.webp"
        required>

    <button type="submit" class="boton">
        Cambiar foto
    </button>

    </form>


        <p>¡Bienvenido a tu espacio personal de GameHub!</p>

        <div class="perfil-datos">
            <p>
                <strong>Nombre de usuario:</strong>
                <?php echo htmlspecialchars($usuario["nombre"]); ?>
            </p>

            <p>
                <strong>Correo electrónico:</strong>
                <?php echo htmlspecialchars($usuario["correo"]); ?>
            </p>

            <p>
                <strong>Fecha de registro:</strong>
                <?php echo htmlspecialchars($usuario["fecha_registro"]); ?>
            </p>
        </div>

        <a href="index.php" class="boton">Volver al inicio</a>

    </main>

    <footer class="footer">
        <p>GameHub © 2026 - Santiago Guinel</p>
        <p>Un espacio para descubrir videojuegos</p>
    </footer>

</body>
</html>
