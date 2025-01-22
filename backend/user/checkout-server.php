<?php
session_start();
header('Content-Type: application/json');
require('../conn.php');
require('../../vendor/autoload.php');

// Check if user is authenticated
if (!isset($_SESSION['user_data']['user_id']) || $_SESSION['user_type'] !== 'user') {
    session_destroy();
    session_unset();
    echo json_encode(['error' => 'Unauthenticated']);
    exit;
} else {
    $user = $_SESSION['user_data']['user_id'];
}

if ($_GET['action'] === "checkout") {
    $paymentMethod = $_POST['paymentMethod'];
    $client = new \GuzzleHttp\Client();

    if ($paymentMethod == "paymongo") {

        $query = "SELECT 
                    cart.product_id,
                    cart.cart_quantity,
                    products.product_price,
                    products.product_name
                  FROM cart
                  LEFT JOIN products ON cart.product_id = products.product_id
                  WHERE cart.user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartItems = [];
        $totalPrice = 0;

        while ($row = $result->fetch_assoc()) {
            $totalProductPrice = $row['cart_quantity'] * $row['product_price'];
            $row['total_price'] = $totalProductPrice;
            $cartItems[] = $row;
            $totalPrice += $totalProductPrice;
        }

        if (count($cartItems) === 0) {
            echo json_encode(['error' => 'Your cart is empty. Please add products before checkout.']);
            exit;
        }

        $transactionNumber = generateTransactionNumber();

        $insertQuery = $conn->prepare("INSERT INTO transaction_history (transaction_number, user_id, total_price, mop) 
                                      VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param('siiss', $transactionNumber, $user, $totalPrice, $paymentMethod);
        $insertQuery->execute();
        $transactionId = $conn->insert_id;

        $_SESSION['transactionId'] = $transactionId;

        $lineItems = [];
        foreach ($cartItems as $item) {
            $productId = $item['product_id'];
            $qty = $item['cart_quantity']; 
            $price = $item['product_price']; 
            $productName = $item['product_name'];

            $insertCartItemQuery = $conn->prepare("INSERT INTO transaction_details (transaction_id, product_id, qty, price) 
                                                  VALUES (?, ?, ?, ?)");
            $insertCartItemQuery->bind_param('iiid', $transactionId, $productId, $qty, $price);
            $insertCartItemQuery->execute();

            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => $price * 100,
                'description' => 'Product ID: ' . $productId,
                'name' => ucwords($productName),
                'quantity' => intval($qty),
            ];
        }

        $selectUserQuery = $conn->prepare("SELECT firstname, lastname, email, phone_number FROM users WHERE user_id = ?");
        $selectUserQuery->bind_param('i', $user);
        $selectUserQuery->execute();
        $selectUserResult = $selectUserQuery->get_result();
        $userData = $selectUserResult->fetch_assoc();

        $fullname = $userData['firstname'] . ' ' . $userData['lastname'];
        $email = $userData['email'];
        $contact = $userData['phone_number'];

        $data = [
            'data' => [
                'attributes' => [
                    'billing' => [
                        'name' => $fullname,
                        'email' => $email,
                        'phone' => $contact,
                    ],
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'payment_method_types' => ["gcash", "grab_pay", "brankas_metrobank", "brankas_landbank", "card"],
                    'cancel_url' => 'http://localhost:8080/cart',
                    'description' => $transactionNumber,
                    'line_items' => $lineItems,  
                    'success_url' => 'http://localhost:8080/success',
                    'reference_number' => $transactionNumber,
                ],
            ],
        ];

        $bodyJson = json_encode($data);

        try {
            $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
                'body' => $bodyJson,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'accept' => 'application/json',
                    'authorization' => 'Basic c2tfdGVzdF82MTExZnFYWkhKNkxHZlhLMnhaQmdvMlU6',
                ],
            ]);

            $responseData = json_decode($response->getBody(), true);
            $checkoutUrl = $responseData['data']['attributes']['checkout_url'];

            // Get the PayMongo checkout session ID
            $checkoutSessionId = $responseData['data']['id']; 
            $_SESSION['checkoutSessionId'] = $checkoutSessionId;

            // Response back to the client
            $response = [
                'success' => 'Thank you for shopping with us.',
                'MOP' => $paymentMethod,
                'direct' => $checkoutUrl,
            ];

            echo json_encode($response);

        } catch (Exception $e) {
            echo json_encode(['error' => 'Payment request failed: ' . $e->getMessage()]);
        }
    }
}

function generateTransactionNumber() {
    return time() . mt_rand(1000, 9999);
}
?>
