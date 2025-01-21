<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action']) && !empty($_GET['action'])) {

        // get all category
        if($_GET["action"] === "fetchData"){

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

        // fetch product details
        if($_GET["action"] === "fetchDetails") {
            $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : 0;
        
            $query = "SELECT * FROM categories WHERE category_id = ?";
            if ($stmt = $conn->prepare($query)) {
                $stmt->bind_param("i", $category_id); 
                $stmt->execute();
        
                $result = $stmt->get_result();
                $data = array();
        
                if ($result->num_rows > 0) {
                    $data = $result->fetch_assoc();
                }
                echo json_encode($data);
            }
        }


        // Add new category
        if ($_GET['action'] === "insertData") {
            $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
        
            // Check if a category with the same name already exists
            $checkQuery = "SELECT category_name FROM categories WHERE category_name = '$category_name'";
            $checkResult = $conn->query($checkQuery);
            $checkRow = $checkResult->fetch_assoc();
        
            if ($checkRow > 1) {
                $response = array("status" => "warning", "message" => "Category already exists.");
            } else {
                // Prepare the insert query
                $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";
        
                if ($conn->query($sql) === TRUE) {
                    $response = array("status" => "success", "message" => "Category added successfully.");
                } else {
                    $response = array("status" => "error", "message" => "Failed to add data to the database: " . $conn->error);
                }
            }
        
            // Return response in JSON format
            echo json_encode($response);
        }


        if ($_GET['action'] === "updateData") {
            $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
            $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
            
            // Check if a category with the same name already exists (but not the current category)
            $checkQuery = "SELECT category_name FROM categories WHERE category_name = '$category_name' AND category_id != '$category_id'";
            $checkResult = $conn->query($checkQuery);
            $checkRow = $checkResult->fetch_assoc();
        
            if ($checkRow > 1) {
                $response = array("status" => "warning", "message" => "Category already exists.");
            } else {
                // Prepare the update query
                $sql = "UPDATE categories 
                        SET category_name = '$category_name'
                        WHERE category_id = '$category_id'";
        
                if ($conn->query($sql) === TRUE) {
                    $response = array("status" => "success", "message" => "Category updated successfully.");
                } else {
                    $response = array("status" => "error", "message" => "Failed to update data in the database: " . $conn->error);
                }
            }
        
            // Return response in JSON format
            echo json_encode($response);
        }
        


        

        
    }
} else {
    echo 'Unauthenticated';
    header('Location: /403'); exit();
}
