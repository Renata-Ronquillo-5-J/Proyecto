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
$query = "SELECT producto_id, cantidad FROM carrito WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Actualizar stock de los productos
while ($row = $result->fetch_assoc()) {
    $query_update = "UPDATE productos SET stock = stock - ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("ii", $row['cantidad'], $row['producto_id']);
    $stmt_update->execute();
}

// Vaciar el carrito
$query_clear = "DELETE FROM carrito WHERE user_id = ?";
$stmt_clear = $conn->prepare($query_clear);
$stmt_clear->bind_param("i", $user_id);
$stmt_clear->execute();

echo "Compra realizada con éxito. <a href='index.html'>Volver al inicio</a>";
?>
