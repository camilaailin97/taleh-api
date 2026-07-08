<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$tipo = $data['tipo']; 
$monto = (float)($data['monto'] ?? 0);

$url = "https://api.mercadopago.com/checkout/preferences";
$token = getenv('ACCESS_TOKEN');

$body = json_encode([
    "items" => [[
        "title" => ($tipo === 'TALEH') ? "Donación - Proyecto Taleh" : "Donación - Causas Benéficas",
        "quantity" => 1,
        "unit_price" => $monto
    ]],
    "external_reference" => ($tipo === 'TALEH') ? "DONACION_TALEH" : "DONACION_BENEFICA",
    "back_urls" => [
        "success" => "https://camilaailin97.github.io/taleh/",
        "failure" => "https://camilaailin97.github.io/taleh/",
        "pending" => "https://camilaailin97.github.io/taleh/"
    ],
    "auto_return" => "approved"
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer $token"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
?>