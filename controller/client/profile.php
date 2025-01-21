<?php 
$title = " - Profile";

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    header('Location: /403');
}

// order
$sqlOrder = "SELECT * FROM transaction_history
JOIN transaction_details ON transaction_history.transaction_id = transaction_details.transaction_id
JOIN products ON transaction_details.product_id = products.product_id
WHERE user_id = ? AND status != 0
GROUP BY transaction_history.transaction_id
";
$stmt = $conn->prepare($sqlOrder);
$stmt->bind_param("i", $user);
$stmt->execute();
$orders  = $stmt->get_result();

require "views/client/profile.view.php";

