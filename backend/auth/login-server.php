<?php
require('../conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_GET["action"] === "authLogin") {
        authLogin($conn);
    } elseif ($_GET["action"] === "authRegister"){
        authRegister($conn);
    }else {
        header('Location: /403');
    }
}

function authRegister($conn) {
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $phone_number = filter_input(INPUT_POST, 'phonenumber', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$firstname || !$lastname || !$phone_number || !$email || !$password) {
        echo json_encode(["status" => false, "message" => 'All fields are required. Please provide valid information.']);
        return;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => false, "message" => 'Invalid email format']);
        return;  
    }
    // check domain
    $domain = substr(strrchr($email, "@"), 1);
    if (!checkdnsrr($domain, "MX")) {
        echo json_encode(["status" => false, "message" => 'Invalid email domain. The domain does not exist.']);
        return;
    }
    $sqlCheckEmail = "SELECT * FROM users WHERE email = ?";
    try {
        $stmt = $conn->prepare($sqlCheckEmail);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo json_encode(["status" => false, "message" => "This email address is already an existing account."]);
            return;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $sqlInsert = "INSERT INTO users (firstname, lastname, phone_number, email, password) VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sqlInsert);
        $stmt->bind_param("sssss", $firstname, $lastname, $phone_number, $email, $hashedPassword);
        if ($stmt->execute()) {
            echo json_encode(["status" => true, "message" => "Registration successful!"]);
        } else {
            echo json_encode(["status" => false, "message" => "An error occurred while processing your registration. Please try again later."]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => "An error occurred: " . $e->getMessage()]);
    } 
}


function authLogin($conn) {
    session_start();
    $email = filter_input(INPUT_POST, 'email');
    $password = $_POST['password'];

    if (!$email || !$password) {
        echo json_encode(["status" => false, "message" => 'Invalid email or password']);
        return;
    }

    $sqlAdmin = "SELECT * FROM users WHERE email=?";

    try {
        // Check in admins table
        $stmt = $conn->prepare($sqlAdmin);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Admin user
                $_SESSION['user_data'] = $row;
                $_SESSION['user_type'] = 'user';
                echo json_encode(["status" => "success", "user_type" => "user"]);
                exit();
            }
        }

        echo json_encode(["status" => false, "message" => "Invalid email or password"]);
        
    } catch (Exception $e) {
        echo json_encode(["status" => false, "message" => "An error occurred: " . $e->getMessage()]);
    } 
}

