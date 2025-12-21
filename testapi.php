<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache");

function get_client_ip() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
  else return $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
}

$ip = get_client_ip();

// ✅ Nova API: ipwho.is
$url = "https://ipwho.is/{$ip}";
$response = @file_get_contents($url);

if ($response === FALSE) {
  echo json_encode([
    "ip" => $ip,
    "city" => "Desconhecido",
    "region" => "",
    "country_name" => "",
    "latitude" => "",
    "longitude" => ""
  ]);
  exit;
}

$data = json_decode($response, true);

echo json_encode([
  "ip" => $ip,
  "city" => $data['city'] ?? 'Desconhecido',
  "region" => $data['region'] ?? '',
  "country_name" => $data['country'] ?? '',
  "latitude" => $data['latitude'] ?? '',
  "longitude" => $data['longitude'] ?? ''
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
