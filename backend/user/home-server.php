<?php
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {

        // products
        if ($_GET['action'] === 'homeProduct') {
            $sql = "SELECT * FROM products WHERE product_quantity != 0 group by product_name" ;
            $result = $conn->query($sql);
        
            // Prepare the data to be sent as JSON
            $data = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = encryptor('encrypt', $row['product_id']);
                    $row['product_id'] = $id; 
                    $data[] = $row;
                }
            }
        
            $response = array('products' => $data);
            echo json_encode($response);
        }

    }
    

} else {
    echo 'Unauthenticated';
    // header('Location: /403'); exit();
}
