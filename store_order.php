<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $amount = floatval($_POST['amount']);
    $payment_id = mysqli_real_escape_string($conn, $_POST['payment_id']);
    $payment_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO orders (product_id, product_name, amount, payment_id, payment_date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("isdss", $product_id, $product_name, $amount, $payment_id, $payment_date);

    if ($stmt->execute()) {
        echo "Order saved successfully";
    } else {
        echo "Error saving order: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request method";
}
?>
