<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {

        // get all products
        if($_GET["action"] === "fetchData"){

            $query = "SELECT * FROM products";
            if ($stmt = $conn->prepare($query)) {
               
                $stmt->execute();
                
                $result = $stmt->get_result();
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                echo json_encode($data);
            }
        }

        // fetch product details
        if($_GET["action"] === "fetchProductDetails") {
            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : 0;
        
            $query = "SELECT * FROM products WHERE product_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $product_id); 
                $stmt->execute();
        
                $result = $stmt->get_result();
                $data = array();
        
                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                }
                echo json_encode($data);
            }
        }

        // get all category
        if($_GET["action"] === "fetchCategories"){

            $query = "SELECT * FROM categories";
            if ($stmt = $conn->prepare($query)) {
               
                $stmt->execute();
                
                $result = $stmt->get_result();
                $data = array();
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $data[] = $row;
                    }
                }
                echo json_encode($data);
            }
        }

        if ($_GET['action'] === "insertData") {
            $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
            $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
            $product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
            $product_category = mysqli_real_escape_string($conn, $_POST['product_category']);
            
            // Image upload handling
            $product_image = $_FILES['product_image']['name'];
            $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
            $product_image_folder = '../../img/products/' . $product_image;
        
            // Check if the product name already exists
            $checkQuery = "SELECT COUNT(*) as count FROM products WHERE product_name = '$product_name'";
            $checkResult = $conn->query($checkQuery);
            $checkRow = $checkResult->fetch_assoc();
        
            if ($checkRow['count'] > 0) {
                $response = array("status" => "warning", "message" => "Product name already exists.");
            } else {
                // Check if the image is uploaded successfully
                if (move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
                    // Insert data into the database
                    $sql = "INSERT INTO products (product_name, product_price, product_quantity, category_id, product_img)
                            VALUES ('$product_name', '$product_price', '$product_quantity', '$product_category', '$product_image')";
                    if ($conn->query($sql) === TRUE) {
                        $response = array("status" => "success", "message" => "Product added successfully.");
                    } else {
                        $response = array("status" => "error", "message" => "Failed to insert data into the database: " . $conn->error);
                    }
                } else {
                    $response = array("status" => "error", "message" => "Failed to move uploaded file.");
                }
            }
            // Return response in JSON format
            echo json_encode($response);
        }

        if ($_GET['action'] === "updateData") {
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
            $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
            $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
            $product_quantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
            $product_category = mysqli_real_escape_string($conn, $_POST['product_category']);
            
            // Image upload handling (optional, if a new image is provided)
            $product_image = isset($_FILES['product_image']['name']) ? $_FILES['product_image']['name'] : '';
            $product_image_tmp_name = isset($_FILES['product_image']['tmp_name']) ? $_FILES['product_image']['tmp_name'] : '';
            $product_image_folder = '../../img/products/' . $product_image;
        
            $checkQuery = "SELECT product_name FROM products WHERE product_name = '$product_name' AND product_id != '$product_id'";
            $checkResult = $conn->query($checkQuery);
            $checkRow = $checkResult->fetch_assoc();
        
            if ($checkRow > 1) {
                $response = array("status" => "warning", "message" => "Product already exist.");
            } else {
                // Prepare update query
                if (!empty($product_image)) {
                    if (move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
                        $sql = "UPDATE products 
                                SET product_name = '$product_name', 
                                    product_price = '$product_price', 
                                    product_quantity = '$product_quantity', 
                                    category_id = '$product_category', 
                                    product_img = '$product_image' 
                                WHERE product_id = '$product_id'";
                    } else {
                        $response = array("status" => "error", "message" => "Failed to move uploaded file.");
                        echo json_encode($response);
                        exit();
                    }
                } else {
                    $sql = "UPDATE products 
                            SET product_name = '$product_name', 
                                product_price = '$product_price', 
                                product_quantity = '$product_quantity', 
                                category_id = '$product_category' 
                            WHERE product_id = '$product_id'";
                }
        
                if ($conn->query($sql) === TRUE) {
                    $response = array("status" => "success", "message" => "Product updated successfully.");
                } else {
                    $response = array("status" => "error", "message" => "Failed to update data in the database: " . $conn->error);
                }
            }
        
            // Return response in JSON format
            echo json_encode($response);
        }


        // if ($_GET['action'] === "deleteData") {
        //     $product_id = htmlspecialchars($_POST['product_id']);
        
        //     $sql = "DELETE FROM products WHERE product_id = '$product_id'";
        //     if ($conn->query($sql) === TRUE) {
        //         $response = array("status" => "success", "message" => "Product deleted successfully.");
        //     } else {
        //         $response = array("status" => "error", "message" => "Failed to delete data from the database: " . $conn->error);
        //     }
        
        //     // // Return response in JSON format
        //     echo json_encode($response);
        // }
        
        

        
    }
} else {
    echo 'Unauthenticated';
    header('Location: /403'); exit();
}
