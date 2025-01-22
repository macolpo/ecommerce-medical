<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {

        // get all category
        if($_GET["action"] === "fetchData"){

            $query = "SELECT * FROM transaction_history JOIN users ON transaction_history.user_id = users.user_id WHERE transaction_history.action = 0";
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
        
                echo json_encode($data); // Output the data with total
            }
        }
        if ($_GET["action"] === "updateOrder") {
            $transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : 0;
            $status = 1;
        
            $query = "UPDATE transaction_history SET action = ? WHERE transaction_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("si", $status, $transaction_id);
                $stmt->execute();
        
                if ($stmt->affected_rows === 1) {
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error']);
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
