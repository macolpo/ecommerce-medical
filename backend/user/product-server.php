<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');

if(!isset($_SESSION['user_data']['user_id'])){
    session_destroy();
    session_unset();
} else {
    $user = $_SESSION['user_data']['user_id'];
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {


        // product size
        if ($_GET['action'] === 'productSize') {
            $productname = $_POST['productname'];
            $size = $_POST['size'];
            $color = $_POST['color'];

            $sql = "SELECT * FROM products WHERE product_name = '$productname' AND product_size = '$size' AND product_color = '$color'" ;
            $result = $conn->query($sql);
        
            // Prepare the data to be sent as JSON
            $data = array();
            if ($result->num_rows > 0) {
                if ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
        
            echo json_encode($data);

        }

        // // cart
        if ($_GET['action'] === 'formCart') {
            $id = $_POST['product_id'];
        
            try {
                // Fetch the product details
                $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $product = $stmt->get_result()->fetch_assoc();

                if ($product) {
                    $checkCartStmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
                    $checkCartStmt->bind_param("ii", $user, $id);
                    $checkCartStmt->execute();
                    $cartItem = $checkCartStmt->get_result()->fetch_assoc();
        
                    if ($cartItem) {
                        $newQuantity = $cartItem['cart_quantity'] + 1;
                        $newTotal = $newQuantity * $product['product_price'];
        
                        $updateStmt = $conn->prepare("UPDATE cart SET cart_quantity = ?, cart_total = ? WHERE cart_id = ?");
                        $updateStmt->bind_param("idi", $newQuantity, $newTotal, $cartItem['cart_id']);
                        $updateStmt->execute();
        
                        $response = json_encode(['success' => true, 'message' => 'Cart Updated']);
                    } else {
                        $insert ="INSERT into cart (user_id, product_id, cart_quantity, cart_total) values ('$user','{$product['product_id']}','1','{$product['product_price']}')";
                        $result = $conn->query($insert);
                        $response = json_encode(['success' => true, 'message' => 'Add to Cart Success!']);
                    }

                    echo $response;
                }
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        }

        // cartlist
        if ($_GET["action"] === "cartList") {
            // Prepare the SQL query to fetch the cart data
            $query = "SELECT
                        p.product_id,
                        p.product_name,
                        p.product_price,
                        p.product_size,
                        p.product_color,
                        p.product_img,
                        c.cart_quantity
                    FROM cart c
                    LEFT JOIN products p ON c.product_id = p.product_id
                    WHERE c.user_id = ? ORDER by c.cart_id DESC";
            
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $user);
                $stmt->execute();
                
                $result = $stmt->get_result();
                
                $data = array();
                $overallTotalPrice = 0; 
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = encryptor('encrypt', $row['product_id']);
                        $row['product_id'] = $id;
                
                        $quantity = $row['cart_quantity'];
                        $price = $row['product_price'];
                        $totalPrice = $price * $quantity;
                        $overallTotalPrice += $totalPrice;
                
                        $data[] = $row;
                    }
                } 
                $response = array(
                    'overallTotalPrice' => $overallTotalPrice,
                    'products' => $data
                );
                echo json_encode($response);
            } 
        }

        // count cart
        if ($_GET["action"] === "countCartList") {
            $query = "SELECT count(c.product_id) as countOrder FROM cart c
                    JOIN products p on c.product_id = p.product_id
                    WHERE c.user_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $user); 
                $stmt->execute();
                $result = $stmt->get_result();
                $data = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data['countOrder'] = $row['countOrder'];
                    }
                    echo json_encode($data);
                } else {
                    echo json_encode(['error' => 'No items found in cart']);
                }
            } 
        }
        
        // cart add quantity
        if ($_GET["action"] === "addQuantity") {
            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
            $id = encryptor('decrypt', $product_id);

            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();

            if($product){
                $checkCartStmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
                $checkCartStmt->bind_param("i", $id);
                $checkCartStmt->execute();
                $cartItem = $checkCartStmt->get_result()->fetch_assoc();
                if ($cartItem) {
                    $newQuantity = $cartItem['cart_quantity'] + 1;
                    $newTotal = $newQuantity * $product['product_price'];

                    $updateStmt = $conn->prepare("UPDATE cart SET cart_quantity = ?, cart_total = ? WHERE cart_id = ?");
                    $updateStmt->bind_param("idi", $newQuantity, $newTotal, $cartItem['cart_id']);
                    $updateStmt->execute();
                    $response = json_encode(['success' => true, 'message' => 'Cart Updated']);
                }
            }
            
        
            echo json_encode($response);
        }

        // cart minus quantity
        if ($_GET["action"] === "minusQuantity") {
            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
            $id = encryptor('decrypt', $product_id);
        
            $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $product = $stmt->get_result()->fetch_assoc();
        
            if ($product) {
                $checkCartStmt = $conn->prepare("SELECT * FROM cart WHERE product_id = ?");
                $checkCartStmt->bind_param("i", $id);
                $checkCartStmt->execute();
                $cartItem = $checkCartStmt->get_result()->fetch_assoc();
        
                if ($cartItem) {
                    $newQuantity = $cartItem['cart_quantity'] - 1;
        
                    if ($newQuantity < 1) {
                        $newQuantity = 1;
                    }
        
                    $newTotal = $newQuantity * $product['product_price'];
        
                    $updateStmt = $conn->prepare("UPDATE cart SET cart_quantity = ?, cart_total = ? WHERE cart_id = ?");
                    $updateStmt->bind_param("idi", $newQuantity, $newTotal, $cartItem['cart_id']);
                    $updateStmt->execute();
                    $response = json_encode(['success' => true, 'message' => 'Cart Updated']);
                }
            }
        
            echo json_encode($response);
        }

        // cart delete
        if ($_GET["action"] === "deleteCart") {
            $product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;
            $id = encryptor('decrypt', $product_id); 
            
            $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $response = json_encode(['success' => true, 'message' => 'Item Deleted!']);
            } 
            
            echo $response;
        }

        //************** */ product.php
        // category list
        if($_GET["action"] === "productCategories"){
            $query = "SELECT * FROM categories";
               

            if ($stmt = $conn->prepare($query)) {
                $stmt->execute();
                
                $result = $stmt->get_result();
                
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = encryptor('encrypt', $row['category_id']);
                        $row['category_id'] = $id;
                        $data[] = $row;
                    }
                } 
                echo json_encode($data);
            }

        }
        // product list
        if($_GET["action"] === "productList"){
            $catId = $_POST['filter'];

            if ($catId === "all") {
                $query = "SELECT * FROM products GROUP BY product_name;";
            } else {
                $id = encryptor('decrypt', $catId);
                $query = "SELECT * FROM products WHERE category_id = ? GROUP BY product_color";
            }
            if ($stmt = $conn->prepare($query)) {
                if ($catId !== "all") {
                    $stmt->bind_param("i", $id);
                }
                $stmt->execute();
                
                $result = $stmt->get_result();
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = encryptor('encrypt', $row['product_id']);
                        $row['product_id'] = $id;
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
