<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "medical_supply";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['user_data']['user_id']) && $_SESSION['user_type'] === 'user') {
  $user = $_SESSION['user_data']['user_id'];
}


// url
function urlIs($value){
  return $_SERVER['REQUEST_URI'] === $value;
}

// taga encrypt
function encryptor($action, $string) {
  $output = false;
  $encrypt_method = "AES-256-CBC";
  
  $secret_key = 'Kukune';
  $secret_iv = 'kukune@12345678';

 
  $key = hash('sha256', $secret_key);
  
 
  $iv = substr(hash('sha256', $secret_iv), 0, 16);

  
  if( $action == 'encrypt' ) 
  {
      $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
      $output = base64_encode($output);
  }
  else if( $action == 'decrypt' )
  {
    
      $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0,      $iv);
  }

  return $output;
}