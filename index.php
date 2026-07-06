<?php
// 1. Configuración de seguridad (CORS) - ¡NO BORRAR!
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Si el navegador pregunta si puede conectar, le decimos que sí (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

// 2. Recibir datos del frontend
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// 3. Preparar la petición a Mercado Pago
$url = "https://api.mercadopago.com/checkout/preferences";
$token = "APP_USR-7999825053986712-070600-e87c755b0a3f934c8b18448ecdca50ac-250750027";

$body = json_encode([
    "items" => [[
        "title" => "Compra en Taleh",
        "quantity" => 1,
        "unit_price" => (float)($data['total'] ?? 0)
    ]],
    "back_urls" => [
        "success" => "https://camilaailin97.github.io/taleh/",
        "failure" => "https://camilaailin97.github.io/taleh/",
        "pending" => "https://camilaailin97.github.io/taleh/"
    ],
    "auto_return" => "approved"
]);

// 4. Ejecutar la petición con cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// 5. Devolver la respuesta a tu web
header('Content-Type: application/json');
echo $response;
?>
