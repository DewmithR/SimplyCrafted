<?php
include 'config/db.php';
session_start();

if (isset($_POST['cart_id'], $_POST['quantity']) && isset($_SESSION['user'])) {
    $cart_id = intval($_POST['cart_id']);
    $quantity = intval($_POST['quantity']);
    $user_id = $_SESSION['user']['id'];

    if ($quantity > 0) {
        $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE id=? AND user_id=?");
        $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
        $stmt->execute();
    }
}
?>