<?php 

$title = " - Product Details";

$product_id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = encryptor('decrypt',$product_id);
// Fetch product details from the database
$sql = "SELECT * FROM products WHERE product_id = ? AND product_quantity != 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    header('Location: /403');
}

$zero = '0';

// color
$sql_colors = "SELECT DISTINCT product_id, product_color FROM products WHERE product_name = ? AND product_quantity != ? GROUP BY product_color";
$stmt_colors = $conn->prepare($sql_colors);
$stmt_colors->bind_param("si", $product['product_name'], $zero);
$stmt_colors->execute();
$colors_result = $stmt_colors->get_result();

// size
$sql_sizes = "SELECT DISTINCT product_size FROM products WHERE product_name = ? AND product_color = ? AND product_quantity != ? ORDER BY product_size ASC";
$stmt_sizes = $conn->prepare($sql_sizes);
$stmt_sizes->bind_param("ssi", $product['product_name'],$product['product_color'], $zero);
$stmt_sizes->execute();
$sizes_result = $stmt_sizes->get_result();

require "views/client/product.details.php";

