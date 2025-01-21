<?php 
session_start();
require 'backend/conn.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];


$routes = [

    // client
    '/' => 'controller/client/home.php',
    '/contact' => 'controller/client/contact.php',
    '/about' => 'controller/client/about.php',
    '/login' => 'controller/client/login.php',
    '/register' => 'controller/client/register.php',
    '/logout' => 'controller/client/logout.php',
    '/profile' => 'controller/client/profile.php',
    '/change-password' => 'controller/client/change-password.php',

    '/product' => 'controller/client/product.php',
    '/product-details' => 'controller/client/product-details.php',
    '/cart' => 'controller/client/cart.php',

];

if (array_key_exists($uri, $routes)){
    require $routes[$uri];
} else{
    require ('views/403.php');
}