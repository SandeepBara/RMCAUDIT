<?php
/* require_once('./razor/vendor/autoload.php');
$api_key_id = "rzp_test_XGkvlYb77Wsb7n";
$api_secret = "TXU3hwQmE4Uwl1nCWrBHqHaO";

use Razorpay\Api\Api;
$api = new Api($api_key_id, $api_secret);
$order_id = $api->order->create(array(
    'receipt' => '123',
    'amount' => 100,
    'currency' => 'INR'
    )
  );
print_r($order_id); */

$username = "rzp_test_XGkvlYb77Wsb7n";
$password = "TXU3hwQmE4Uwl1nCWrBHqHaO";

$host = "https://api.razorpay.com/v1/orders";
$payloadName = [
    'amount' => 1000, // amount in the smallest currency unit
    'currency'  => 'INR',// <a href="/docs/payment-gateway/payments/international-payments/#supported-currencies" target="_blank">See the list of supported currencies</a>.)
    'receipt' => 'order_rcptid_11',
    'payment_capture' => 1
];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $host);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadName));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$return = curl_exec($ch);
curl_close($ch);

$jsonArray = json_decode($return, true);
if(array_key_exists('error', $jsonArray)) {
    $order_id = NULL;
} else {
    $order_id = $jsonArray['id'];
}
    
?>