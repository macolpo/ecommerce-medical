<?php 

$title = " - Product Details";

$product_id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = encryptor('decrypt',$product_id);
// Fetch product details from the database
$sql = "SELECT p.*, c.category_name FROM products as p
JOIN categories as c ON p.category_id = c.category_id
WHERE product_id = ? AND product_quantity != 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    header('Location: /403');
}
require "views/client/product.details.php";

