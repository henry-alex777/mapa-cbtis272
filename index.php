<?php
//para la practica 4 aqui deberan modificar
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "db_nombres";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error al conectar: " . $conexion->connect_error);
}

if (isset($_POST['guardar'])) {
    $nombre = trim($_POST['nombre']);
    if (!empty($nombre)) {
        $stmt = $conexion->prepare("INSERT INTO personas (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        header("Location: index.php");
        exit();
    }
}

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM personas WHERE id = $id");
    header("Location: index.php");
    exit();
}

$resultado = $conexion->query("SELECT * FROM personas ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Nombres</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 40px;">

    <h2>Agregar Nuevo Nombre</h2>
    

    <form action="index.php" method="POST">
        <input type="text" name="nombre" placeholder="Escribe el nombre aquí..." required style="padding: 8px; width: 250px;">
        <button type="submit" name="guardar" style="padding: 8px 15px; cursor: pointer;">Guardar</button>
    </form>

    <hr style="margin: 30px 0;">

    <h2>Lista de Nombres Guardados</h2>
    
    <ul>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <li style="margin-bottom: 10px;">
                <strong><?php echo htmlspecialchars($fila['nombre']); ?></strong> 
                — <a href="index.php?eliminar=<?php echo $fila['id']; ?>" style="color: red; text-decoration: none;" onclick="return confirm('¿Seguro que deseas eliminar este nombre?');">[Eliminar]</a>
            </li>
        <?php endwhile; ?>
    </ul>

</body>
</html>
