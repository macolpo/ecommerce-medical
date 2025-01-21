<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['user_data']['user_id']) || $_SESSION['user_data']['user_type'] !== 'user') {
    session_destroy();
    session_unset();
    header('Location: /403');
    exit();
}  else {
    $user = $_SESSION['user_data']['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_GET["action"] === "profilePassword") {
        $currentPasswordInput = mysqli_real_escape_string($conn, $_POST["currentpassword"]);
        $newPassword = mysqli_real_escape_string($conn, $_POST["newpassword"]);
        $confirmPassword = mysqli_real_escape_string($conn, $_POST["confirmpassword"]);
        
        // Retrieve the current password hash from the database
        $getCurrentPasswordQuery = "SELECT password FROM users WHERE user_id = '$user'";
        $result = $conn->query($getCurrentPasswordQuery);
        
        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $currentPasswordHash = $row["password"];
            
            if (password_verify($currentPasswordInput, $currentPasswordHash)) {
                if ($newPassword != $confirmPassword) {
                    $response = array("status" => "warning", "message" => "New password and Confirm password do not match.");
                    echo json_encode($response);
                } else {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    
                    $updateQuery = "UPDATE users SET password = '$hashedPassword' WHERE user_id = '$user'";
                    if ($conn->query($updateQuery) === TRUE) {
                        $response = array("status" => "success", "message" => "Password updated successfully");
                        echo json_encode($response);
                    } else {
                        $response = array("status" => "error", "message" => "Error updating password");
                        echo json_encode($response);
                    }
                }
            } else {
                $response = array("status" => "error", "message" => "Current password is incorrect.");
                echo json_encode($response);
            }
        } 
    }
   
} else {
    echo 'Unauthenticated';
    session_destroy();
    header('Location: /403'); exit();
}
