<?php
require_once __DIR__ . '/autoload.php';

header('Content-Type: application/json');

echo json_encode([
    'status' => 'ok',
    'message' => 'API Backend PSA-CBG',
    'docs' => '/docs/swagger.yaml'
]); 