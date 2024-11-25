<?php
session_start();
$conn = new mysqli("localhost", "root", "", "Miniso_1307");

// Verificar sesión activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Obtener productos del carrito
$query = "
    SELECT p.id, p.nombre, p.precio, c.cantidad, (p.precio * c.cantidad) AS subtotal
    FROM carrito c
    INNER JOIN productos p ON c.producto_id = p.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
</head>
<body>
    <h2>Tu Carrito</h2>
    <table border="1">
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['nombre']; ?></td>
            <td><?php echo number_format($row['precio'], 2); ?></td>
            <td><?php echo $row['cantidad']; ?></td>
            <td><?php echo number_format($row['subtotal'], 2); ?></td>
        </tr>
        <?php $total += $row['subtotal']; ?>
        <?php endwhile; ?>
    </table>
    <h3>Total: $<?php echo number_format($total, 2); ?></h3>
    <form action="comprar.php" method="POST">
        <button type="submit">Comprar</button>
    </form>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>

