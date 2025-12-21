<?php
// Caminho do arquivo JSON
$arquivo = __DIR__ . '/logs/acessos.json';

// Verifica existência e validade
if (!file_exists($arquivo)) {
    die('<p style="color:#6a0dad;">⚠ Nenhum registro encontrado.</p>');
}

$dados = json_decode(file_get_contents($arquivo), true);
if (!is_array($dados)) {
    die('<p style="color:red;">Erro: arquivo de log inválido.</p>');
}

// Totais
$total_acessos = count($dados);
$ips_unicos = count(array_unique(array_column($dados, 'ip')));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Relatório de Acessos</title>

<style>
    body {
        background-color: #0b0b0b;
        color: #fff;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    /* ====== TOPO ====== */
    h2 {
        color: #fff;
        background-color: #6a0dad;
        text-align: left;
        padding: 14px 20px;
        font-size: 20px;
        font-weight: bold;
        margin: 0;
        border-bottom: 3px solid #4b0a7a;
        letter-spacing: 0.3px;
        box-shadow: 0px 2px 6px rgba(106, 13, 173, 0.5);
    }

    /* ====== CONTADORES ====== */
    .contador {
        background-color: #141414;
        padding: 12px 20px;
        font-size: 15px;
        color: #ddd;
        border-bottom: 1px solid #222;
        display: flex;
        justify-content: flex-start;
        gap: 25px;
        font-weight: 600;
    }

    .contador span {
        background-color: #6a0dad;
        color: #fff;
        padding: 5px 10px;
        border-radius: 6px;
        font-weight: bold;
    }

    /* ====== TABELA ====== */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
        font-size: 14px;
    }

    thead {
        background-color: #0b0b0b;
    }

    thead th {
        color: #6a0dad;
        font-weight: bold;
        border-bottom: 1px solid #6a0dad;
        padding: 10px;
        text-align: left;
    }

    td {
        padding: 10px;
        border-bottom: 1px solid #222;
        vertical-align: top;
    }

    tr:nth-child(odd) {
        background-color: #0e0e0e;
    }

    tr:nth-child(even) {
        background-color: #1a1a1a;
    }

    tr:hover {
        background-color: #222;
        transition: 0.2s;
    }

    /* ====== UTM ====== */
    .utm {
        color: #b0b0b0;
        font-size: 12px;
    }

    /* ====== CLIQUE ====== */
    .click {
        color: #6a0dad;
        font-weight: bold;
    }

    /* ====== LINKS ====== */
    a {
        color: #6a0dad;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* ====== RODAPÉ ====== */
    footer {
        text-align: center;
        padding: 15px;
        color: #777;
        font-size: 13px;
        border-top: 1px solid #222;
        margin-top: 20px;
    }
</style>
</head>

<body>

<h2>📊 Relatório de Acessos</h2>

<div class="contador">
    <div>Total de acessos: <span><?= $total_acessos ?></span></div>
    <div>Visitantes únicos: <span><?= $ips_unicos ?></span></div>
</div>

<table>
    <thead>
        <tr>
            <th>Quando</th>
            <th>Local</th>
            <th>Acesso</th>
            <th>Origem</th>
            <th>IP</th>
            <th>Referência / UTM</th>
            <th>Visita ou Clique</th>
            <th>Botão Clicado</th>
            <th>Destino URL</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($dados as $linha): ?>
        <tr>
            <td><?= htmlspecialchars($linha['data'] ?? '-') ?></td>
            <td><?= htmlspecialchars(($linha['city'] ?? '-') . '/' . ($linha['region'] ?? '-') . ' - ' . ($linha['country'] ?? '')) ?></td>
            <td><?= htmlspecialchars($linha['acesso'] ?? '-') ?></td>
            <td><?= htmlspecialchars($linha['origem'] ?? '-') ?></td>
            <td><?= htmlspecialchars($linha['ip'] ?? '-') ?></td>
            <td>
                <?= htmlspecialchars($linha['ref'] ?? '-') ?><br>
                <?php if (!empty($linha['utm_source'])): ?>
                    <span class="utm">
                        utm_source=<?= htmlspecialchars($linha['utm_source']) ?> |
                        utm_medium=<?= htmlspecialchars($linha['utm_medium']) ?> |
                        utm_campaign=<?= htmlspecialchars($linha['utm_campaign']) ?>
                    </span>
                <?php endif; ?>
            </td>
            <td class="<?= ($linha['type'] ?? '') === 'click' ? 'click' : '' ?>">
                <?= ($linha['type'] ?? 'visit') === 'click' ? 'Clique' : 'Visita' ?>
            </td>
            <td><?= htmlspecialchars($linha['link_text'] ?? '-') ?></td>
            <td>
                <?php if (!empty($linha['link'])): ?>
                    <a href="<?= htmlspecialchars($linha['link']) ?>" target="_blank">
                        <?= htmlspecialchars($linha['link']) ?>
                    </a>
                <?php else: ?>
                    —
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<footer>
    Sistema de Registro Interno
</footer>

</body>
</html>
