<?php
use App\Infrastructure\Persistence\UsuarioRepository;
use App\Application\Services\UsuarioService;
use App\Presentation\Controllers\UsuarioController;

// Setup PDO
$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);
$repo = new UsuarioRepository($pdo);
$service = new UsuarioService($repo, $_ENV['JWT_SECRET']);
$controller = new UsuarioController($service);

// Roteamento simples
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/login') {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller->login($data);
    exit;
}

// Roteamento para listar jogos
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/jogos') {
    require_once __DIR__ . '/../../Jogo/Jogo.php';
    require_once __DIR__ . '/../../Jogo/JogoRepository.php';
    require_once __DIR__ . '/../../Jogo/JogoService.php';
    require_once __DIR__ . '/../../Jogo/JogoController.php';

    $repo = new \App\Jogo\JogoRepository($pdo);
    $service = new \App\Jogo\JogoService($repo);
    $controller = new \App\Jogo\JogoController($service);

    $result = $controller->listar();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Roteamento para criar jogo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/jogos') {
    require_once __DIR__ . '/../../Jogo/Jogo.php';
    require_once __DIR__ . '/../../Jogo/JogoRepository.php';
    require_once __DIR__ . '/../../Jogo/JogoService.php';
    require_once __DIR__ . '/../../Jogo/JogoController.php';

    $repo = new \App\Jogo\JogoRepository($pdo);
    $service = new \App\Jogo\JogoService($repo);
    $controller = new \App\Jogo\JogoController($service);

    $data = json_decode(file_get_contents('php://input'), true);
    $jogo = $service->criarJogo(
        0,
        $data['data_jogo'],
        $data['local_jogo'],
        $data['adversario'],
        $data['campeonato'] ?? null,
        $data['resultado'] ?? null,
        $data['observacoes'] ?? null
    );
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($jogo);
    exit;
}

// Roteamento para editar jogo
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('#^/api/jogos/(\d+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    require_once __DIR__ . '/../../Jogo/Jogo.php';
    require_once __DIR__ . '/../../Jogo/JogoRepository.php';
    require_once __DIR__ . '/../../Jogo/JogoService.php';
    require_once __DIR__ . '/../../Jogo/JogoController.php';

    $repo = new \App\Jogo\JogoRepository($pdo);
    $service = new \App\Jogo\JogoService($repo);
    $controller = new \App\Jogo\JogoController($service);

    $id = (int)$matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    $jogo = $service->obterJogo($id);
    if ($jogo) {
        $jogo->setData($data['data_jogo']);
        $jogo->setLocal($data['local_jogo']);
        $jogo->setAdversario($data['adversario']);
        $jogo->setCampeonato($data['campeonato'] ?? null);
        $jogo->setResultado($data['resultado'] ?? null);
        $jogo->setObservacoes($data['observacoes'] ?? null);
        $service->atualizarJogo($jogo);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Jogo não encontrado']);
    }
    exit;
}

// Roteamento para deletar jogo
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('#^/api/jogos/(\d+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    require_once __DIR__ . '/../../Jogo/Jogo.php';
    require_once __DIR__ . '/../../Jogo/JogoRepository.php';
    require_once __DIR__ . '/../../Jogo/JogoService.php';
    require_once __DIR__ . '/../../Jogo/JogoController.php';

    $repo = new \App\Jogo\JogoRepository($pdo);
    $service = new \App\Jogo\JogoService($repo);
    $controller = new \App\Jogo\JogoController($service);

    $id = (int)$matches[1];
    $service->removerJogo($id);
    http_response_code(204);
    exit;
}

// Roteamento para listar minutagens
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === '/api/minutagem') {
    require_once __DIR__ . '/../../Minutagem/Minutagem.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemRepository.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemService.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemController.php';

    $repo = new \App\Minutagem\MinutagemRepository($pdo);
    $service = new \App\Minutagem\MinutagemService($repo);
    $controller = new \App\Minutagem\MinutagemController($service);

    $result = $controller->listar();
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}

// Roteamento para criar minutagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/minutagem') {
    require_once __DIR__ . '/../../Minutagem/Minutagem.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemRepository.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemService.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemController.php';

    $repo = new \App\Minutagem\MinutagemRepository($pdo);
    $service = new \App\Minutagem\MinutagemService($repo);
    $controller = new \App\Minutagem\MinutagemController($service);

    $data = json_decode(file_get_contents('php://input'), true);
    $minutagem = $service->criarRegistro(
        0,
        $data['atleta_id'],
        $data['jogo_id'],
        $data['titular'] ?? true,
        $data['minuto_entrou'],
        $data['minuto_saiu'],
        $data['minutos_primeiro_tempo'],
        $data['minutos_segundo_tempo'],
        $data['observacoes'] ?? ''
    );
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($minutagem);
    exit;
}

// Roteamento para editar minutagem
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('#^/api/minutagem/(\d+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    require_once __DIR__ . '/../../Minutagem/Minutagem.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemRepository.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemService.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemController.php';

    $repo = new \App\Minutagem\MinutagemRepository($pdo);
    $service = new \App\Minutagem\MinutagemService($repo);
    $controller = new \App\Minutagem\MinutagemController($service);

    $id = (int)$matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    $minutagem = $service->obterRegistro($id);
    if ($minutagem) {
        $minutagem->setAtletaId($data['atleta_id']);
        $minutagem->setData($data['jogo_id']);
        $minutagem->setMinutos($data['minutos_primeiro_tempo'] + $data['minutos_segundo_tempo']);
        $minutagem->setAtividade($data['observacoes'] ?? '');
        $service->atualizarRegistro($minutagem);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Minutagem não encontrada']);
    }
    exit;
}

// Roteamento para deletar minutagem
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('#^/api/minutagem/(\d+)$#', $_SERVER['REQUEST_URI'], $matches)) {
    require_once __DIR__ . '/../../Minutagem/Minutagem.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemRepository.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemService.php';
    require_once __DIR__ . '/../../Minutagem/MinutagemController.php';

    $repo = new \App\Minutagem\MinutagemRepository($pdo);
    $service = new \App\Minutagem\MinutagemService($repo);
    $controller = new \App\Minutagem\MinutagemController($service);

    $id = (int)$matches[1];
    $service->removerRegistro($id);
    http_response_code(204);
    exit;
} 