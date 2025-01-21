<?php
require 'layout/top.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['user_data']['user_id']) || $_SESSION['user_type'] !== 'user') {
    header('Location: /');
    exit();
}

if (isset($_SESSION['checkoutSessionId'])) {
    // Fetch checkout session information from PayMongo
    $checkoutSessionId = $_SESSION['checkoutSessionId'];

    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('GET', 'https://api.paymongo.com/v1/checkout_sessions/' . $checkoutSessionId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF82MTExZnFYWkhKNkxHZlhLMnhaQmdvMlU6',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);
        $paymentStatus = $responseData['data']['attributes']['status'];

        if ($paymentStatus === 'active') {
            $updateTransaction = $conn->prepare("UPDATE transaction_history SET status = '1' WHERE transaction_id = ?");
            $updateTransaction->bind_param('i', $_SESSION['transactionId']);
            $updateTransaction->execute();

            $deleteCartQuery = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $deleteCartQuery->bind_param('i', $_SESSION['user_data']['user_id']);
            $deleteCartQuery->execute();
            header("Location: /payment-success");
            exit();
        } else {
            exit;
        }

    } catch (Exception $e) {
        echo json_encode(['error' => 'Error fetching payment status: ' . $e->getMessage()]);
        exit;
    }

} else {
    unset($_SESSION['checkoutSessionId']);
    header('Location: /');
    exit();
}
?>
