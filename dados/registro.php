<?php
header('Content-Type: application/json; charset=utf-8');

// Lê o corpo da requisição (JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !is_array($input)) {
    echo json_encode(['status' => 'erro', 'msg' => 'Nenhum dado recebido']);
    exit;
}

// Caminho do arquivo de log
$arquivo = __DIR__ . '/logs/acessos.json';

// Se não existir, cria vazio
if (!file_exists($arquivo)) {
    file_put_contents($arquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Lê registros existentes
$dados = json_decode(file_get_contents($arquivo), true);
if (!is_array($dados)) $dados = [];

// 🔹 Monta o registro
$log = [
    'data'         => date('d/m/Y \à\s H:i'),
    'when_iso'     => $input['when_iso'] ?? date('c'),
    'type'         => $input['type'] ?? 'visit', // visit | click
    'visitor_id'   => $input['visitor_id'] ?? '',
    'page'         => $input['page'] ?? '',
    'link'         => $input['link'] ?? '',
    'link_text'    => $input['link_text'] ?? '',
    'is_external'  => $input['is_external'] ?? 0,

    'ip'           => $input['ip'] ?? '',
    'city'         => $input['city'] ?? '',
    'region'       => $input['region'] ?? '',
    'country'      => $input['country'] ?? '',

    'acesso'       => $input['acesso'] ?? 'Acesso direto',
    'origem'       => $input['origem'] ?? 'Desconhecido',
    'ref'          => $input['ref'] ?? '',

    'utm_source'   => $input['utm_source'] ?? '',
    'utm_medium'   => $input['utm_medium'] ?? '',
    'utm_campaign' => $input['utm_campaign'] ?? '',
    'utm_content'  => $input['utm_content'] ?? ''
];

// Adiciona novo registro no início
array_unshift($dados, $log);

// Salva de volta
file_put_contents($arquivo, json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode(['status' => 'ok', 'msg' => 'Registro salvo']);
