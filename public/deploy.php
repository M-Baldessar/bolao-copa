<?php

define('DEPLOY_SECRET', getenv('DEPLOY_SECRET') ?: '');
define('DEPLOY_SCRIPT', __DIR__ . '/../deploy.sh');
define('DEPLOY_LOG', __DIR__ . '/../storage/logs/deploy.log');

function log_deploy(string $message): void
{
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents(DEPLOY_LOG, $line, FILE_APPEND);
}

// Verifica método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Verifica assinatura do GitHub
$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (empty(DEPLOY_SECRET)) {
    http_response_code(500);
    log_deploy('ERRO: DEPLOY_SECRET não configurado.');
    exit('Server misconfiguration');
}

$expected = 'sha256=' . hash_hmac('sha256', $payload, DEPLOY_SECRET);

if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    log_deploy('Tentativa de acesso com assinatura inválida. IP: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'));
    exit('Unauthorized');
}

// Só executa em push para a branch main
$data   = json_decode($payload, true);
$branch = $data['ref'] ?? '';

if ($branch !== 'refs/heads/main') {
    http_response_code(200);
    exit('Branch ignorado: ' . $branch);
}

// Executa o script de deploy
log_deploy('Deploy iniciado por push de: ' . ($data['pusher']['name'] ?? 'desconhecido'));

$output = shell_exec('bash ' . escapeshellarg(DEPLOY_SCRIPT) . ' 2>&1');

log_deploy('Deploy concluído. Output: ' . PHP_EOL . $output);

http_response_code(200);
echo 'Deploy executado com sucesso.';
