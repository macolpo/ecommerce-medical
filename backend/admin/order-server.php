<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {

        // get all category
        if($_GET["action"] === "fetchData"){

            $query = "SELECT * FROM transaction_history JOIN users ON transaction_history.user_id = users.user_id WHERE transaction_history.action = 0 AND transaction_history.status = 1";
            if ($stmt = $conn->prepare($query)) {
               
                $stmt->execute();
                
                $result = $stmt->get_result();
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['created_at'] = date('F d, Y h:i A', strtotime($row['created_at']));
                        $data[] = $row;
                    }
                }
                echo json_encode($data);
            }
        }
        // fetch product details
        if ($_GET["action"] === "fetchDetails") {
            $transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : 0;
        
            $query = "SELECT * FROM transaction_details
                      JOIN products ON transaction_details.product_id = products.product_id
                      JOIN transaction_history ON transaction_details.transaction_id = transaction_history.transaction_id
                      WHERE transaction_details.transaction_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $transaction_id); 
                $stmt->execute();
        
                $result = $stmt->get_result();
                $data = array();
                $total = 0; // Initialize the total variable
        
                while ($row = $result->fetch_assoc()) {
                    $row['created_at'] = date('F d, Y h:i A', strtotime($row['created_at']));
                    $subtotal = $row['qty'] * $row['price'];
                    $total += $subtotal; 
                    $data[] = $row; 
                }
        
                echo json_encode($data); 
            }
        }

        if ($_GET["action"] === "updateOrder") {
            $transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : 0;
            $status = 1;
            
            // Step 1: Fetch transaction details (product_id, qty)
            $transaction_query = "SELECT product_id, qty FROM transaction_details WHERE transaction_id = ?";
            if ($stmt = $conn->prepare($transaction_query)) {
                $stmt->bind_param("i", $transaction_id);
                $stmt->execute();
                $stmt->store_result();
                
                if ($stmt->num_rows > 0) {
                    $stmt->bind_result($product_id, $qty);
                    while ($stmt->fetch()) {
                        // Step 2: Update the quantity in the products table
                        $update_product_query = "UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?";
                        if ($update_stmt = $conn->prepare($update_product_query)) {
                            $update_stmt->bind_param("ii", $qty, $product_id);
                            $update_stmt->execute();
                            
                            // Check if the product stock update was successful
                            if ($update_stmt->affected_rows !== 1) {
                                echo json_encode(['status' => 'error', 'message' => 'Failed to update product stock']);
                                exit;
                            }
                        }
                    }
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Transaction details not found']);
                    exit;
                }
                
                // Step 3: Update the transaction history action
                $query = "UPDATE transaction_history SET action = ? WHERE transaction_id = ?";
                if ($stmt = $conn->prepare($query)) {
                    $stmt->bind_param("si", $status, $transaction_id);
                    $stmt->execute();
        
                    if ($stmt->affected_rows === 1) {
                        echo json_encode(['status' => 'success']);
                    } else {
                        echo json_encode(['status' => 'error', 'message' => 'Failed to update transaction history']);
                    }
                }
            }
        }
        


        // arrived orders
        if($_GET["action"] === "fetchArrivedOrders"){

            $query = "SELECT * FROM transaction_history JOIN users ON transaction_history.user_id = users.user_id WHERE transaction_history.action = 1";
            if ($stmt = $conn->prepare($query)) {
               
                $stmt->execute();
                
                $result = $stmt->get_result();
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $row['created_at'] = date('F d, Y h:i A', strtotime($row['created_at']));
                        $data[] = $row;
                    }
                }
                echo json_encode($data);
            }
        }

        

        
    }
} else {
    echo 'Unauthenticated';
    header('Location: /403'); exit();
}
