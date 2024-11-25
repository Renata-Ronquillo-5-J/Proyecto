<?php
// Conexión a la base de datos
$serverName = "localhost"; // Cambia esto si tu servidor SQL está en otra máquina
$username = "root";       // Usuario de la base de datos
$password = "";           // Contraseña de la base de datos
$database = "Miniso_1307"; // Nombre de tu base de datos

$conn = new mysqli($serverName, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener productos de la base de datos
$query = "SELECT * FROM productos";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="sty.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<center>   <h1 style=" color: rgb(0, 0, 0); font-family:  serif; padding: 0px;">  <img src="Imagenes/Logo.png" alt="Logo" style="width:30px;height:40px;"> MINISO  <img src="Imagenes/logo2.png" alt="Logo" style="width:30px;height:40px;"> </h1></center>
    

    
    
    <div class="barra">
    <nav>
        <ul>
            <img src="Imagenes/Logo.png" alt="Logo" style="width:30px;height:40px;">
            <li><a href="Index.html">Inicio</a></li>
            <li><a href="Categorias.html">Categorias</a></li>
            <li><a href="Carrito.html">Carrito</a></li>
            <li><a href="Contacto.html">Contacto</a></li>
        </ul>
    </nav>
</div>
<center> <h4 style="color: rgb(255, 255, 255);  background-color: #333; font-family: 'Times New Roman', Times, serif;"> INICIO </h4></center>


<br> </br>

    <h1>Productos Disponibles</h1>
    <div class="productos">
        <?php while ($producto = $result->fetch_assoc()): ?>
        <div class="producto">
            <h2><?php echo $producto['nombre']; ?></h2>
            <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
            <p>Stock: <?php echo $producto['stock']; ?> unidades</p>
            <!-- Formulario para añadir al carrito -->
            <form action="agregar_carrito.php" method="POST">
            <img src="<?php echo $producto['ruta_imagen']; ?>" alt="Imagen de producto">

                <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                <label for="cantidad">Cantidad:</label>
                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $producto['stock']; ?>" required>
                <button type="submit">Añadir al Carrito</button>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

<?php
// Cerrar conexión
$conn->close();
?>
