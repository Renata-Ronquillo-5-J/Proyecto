<?php
session_start();
$conn = new mysqli("localhost", "root", "", "Miniso_1307");

// Verificar sesión activa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Datos del formulario
$user_id = $_SESSION['user_id'];
$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];

// Verificar stock disponible
$query_stock = "SELECT stock FROM productos WHERE id = ?";
$stmt = $conn->prepare($query_stock);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();

if ($producto['stock'] < $cantidad) {
    echo "No hay suficiente stock disponible.";
    exit;
}

// Agregar al carrito
$query = "INSERT INTO carrito (user_id, producto_id, cantidad) VALUES (?, ?, ?) 
          ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $user_id, $producto_id, $cantidad);

if ($stmt->execute()) {
    header("Location: carrito.php");
} else {
    echo "Error al agregar al carrito: " . $stmt->error;
}
?>
