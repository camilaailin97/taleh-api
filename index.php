<?php
// Permitir conexiones desde tu web
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

$data = json_decode(file_get_contents('php://input'), true);
$total = $data['total'] ?? 0;

$url = "https://api.mercadopago.com/checkout/preferences";
$token = "APP_USR-7999825053986712-070600-e87c755b0a3f934c8b18448ecdca50ac-250750027";

$body = json_encode([
    "items" => [[
        "title" => "Compra en Taleh",
        "quantity" => 1,
        "unit_price" => (float)$total
    ]],
    "back_urls" => [
        "success" => "https://camilaailin97.github.io/taleh/",
        "failure" => "https://camilaailin97.github.io/taleh/",
        "pending" => "https://camilaailin97.github.io/taleh/"
    ],
    "auto_return" => "approved"
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type:application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
