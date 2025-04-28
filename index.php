<?php
/* ───── 0. BUFFER + SESSÃO ───── */
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ───── 1. AUTOLOADER LOCAL (mesmo do projeto) ───── */
spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/';
    $file     = $base_dir . str_replace('\\','/',$class) . '.php';

    if (strpos($class,'Controllers')!==false) {
        $file = $base_dir.'controllers/'.basename(str_replace('Controllers\\','',$class)).'.php';
    } elseif (strpos($class,'Models')!==false) {
        $file = $base_dir.'models/'.basename(str_replace('Models\\','',$class)).'.php';
    } elseif (strpos($class,'Config')!==false) {
        $file = $base_dir.'config/'.basename(str_replace('Config\\','',$class)).'.php';
    }
    if (file_exists($file)) require $file;
});

/* ───── 2. PROTEÇÃO DE ROTA ───── */
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}

/* ───── 3. VARIÁVEIS DE SESSÃO ───── */
$usuarioSessao = $_SESSION['usuario'];
$nivelSessao   = $_SESSION['nivel']   ?? 'atleta';   // admin | treinador | atleta

/* ───── 4. CONSULTAS ÚTEIS ───── */
use Controllers\RegistroController;
$statusRegistro = RegistroController::obterStatusRegistro();   // 0 = liberado

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Painel Principal</title>

    <!-- Bootstrap + FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{background:#07272D;display:flex;justify-content:center;align-items:center;height:100vh;color:#FFF;}
        .top-bar{position:fixed;top:15px;right:15px;display:flex;align-items:center;gap:16px;z-index:1000;}
        .logo-fixed{position:fixed;bottom:18px;right:18px;width:120px;opacity:0.9;z-index:900;}
        .logout-icon{width:45px!important;height:45px!important;font-size:23px!important;border-radius:12px!important;}
        .container{background:rgba(255,255,255,0.08);padding:28px;border-radius:14px;text-align:center;max-width:420px;width:100%;}
        .logo{width:115px;margin-bottom:18px;}

        /* iPhone-grid */
        .menu-apps{display:flex;flex-wrap:wrap;gap:18px;justify-content:center;margin:25px 0;}
        .app-icon{width:78px;height:78px;border-radius:22px;background:#f5f5f7;display:flex;align-items:center;justify-content:center;font-size:34px;color:#0d6efd;text-decoration:none;transition:.25s;}
        .app-icon:hover{transform:scale(1.08);background:#e8e8ea;}
        .app-icon:active{transform:scale(.92);}
        .i-blue{color:#0d6efd}.i-green{color:#198754}.i-yellow{color:#ffc107}.i-red{color:#dc3545}
    </style>
</head>

<body>
    <!-- logo canto inferior-direito -->
    <img src="https://athleticmap.com/images/logo-atm.png" alt="Athletic Map" class="logo-fixed">

    <!-- botão sair -->
    <div class="top-bar">
        <a href="/pre-treino-rfc/controllers/LogoutController.php" class="app-icon i-red logout-icon" title="Sair">
            <i class="fas fa-power-off"></i>
        </a>
    </div>

    <!-- painel principal -->
    <div class="container">
        <img src="assets/img/logo-resende.png" class="logo" alt="Resende FC">
        <h3 class="text-center fw-bold mb-2">Módulo PSR &amp; PSE</h3>
        <p class="fw-bold" style="color:#E0E0E0;"><?= htmlspecialchars($usuarioSessao) ?></p>

        <div class="menu-apps">
            <!-- ─────────── LINHA AZUL (cadastros / listas) ─────────── -->
            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin' || $nivelSessao === 'treinador'): ?>
                    <!-- Atletas -->
                    <a href="/pre-treino-rfc/views/atleta_form.php"   class="app-icon i-blue" title="Cadastrar Atleta"><i class="fas fa-running"></i></a>
                    <a href="/pre-treino-rfc/views/lista_atletas.php" class="app-icon i-blue" title="Listar Atletas"><i class="fas fa-users-line"></i></a>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin'): ?>
                    <!-- Treinadores -->
                    <a href="/pre-treino-rfc/views/treinadores_form.php" class="app-icon i-blue" title="Cadastrar Treinador"><i class="fas fa-user-tie"></i></a>
                    <a href="/pre-treino-rfc/views/lista_treinadores.php" class="app-icon i-blue" title="Listar Treinadores"><i class="fas fa-chalkboard-user"></i></a>
                    <!-- Usuários -->
                    <a href="/pre-treino-rfc/views/lista_usuarios.php" class="app-icon i-blue" title="Usuários"><i class="fas fa-user-gear"></i></a>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin'): ?>
                    <!-- Cadastrar Jogo -->
                    <a href="/pre-treino-rfc/views/jogo_form.php"
                    class="app-icon i-blue" title="Cadastrar Jogo">
                    <i class="fas fa-futbol"></i></a>

                    <!-- Listar Jogos -->
                    <a href="/pre-treino-rfc/views/lista_jogos.php"
                    class="app-icon i-blue" title="Listar Jogos">
                    <i class="fas fa-calendar-day"></i></a>
                <?php endif; ?>
            </div>
            <!-- LINHA VERDE (Registros + Minutagem) -->
            <?php
            $podeRegistrar = ($nivelSessao === 'admin' || $nivelSessao === 'treinador')
                            || ($nivelSessao === 'atleta' && $statusRegistro == 0);
            ?>
            <?php if ($podeRegistrar): ?>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <!-- Pré / Pós -->
                <a href="/pre-treino-rfc/views/sono_form.php" class="app-icon i-green" title="Registrar Pré-Treino"><i class="fas fa-bed"></i></a>
                <a href="/pre-treino-rfc/views/pse_form.php"  class="app-icon i-green" title="Registrar Pós-Treino"><i class="fas fa-heartbeat"></i></a>

                <?php if (in_array($nivelSessao, ['admin','treinador'])): ?>
                    <!-- ★ NOVO: Minutagem -->
                    <a href="/pre-treino-rfc/views/minutagem_form.php"
                    class="app-icon i-green" title="Cadastrar Minutagem">
                    <i class="fas fa-stopwatch"></i></a>

                    <a href="/pre-treino-rfc/views/lista_minutagem.php"
                    class="app-icon i-green" title="Listar Minutagens">
                    <i class="fas fa-table-list"></i></a>
                <?php endif; ?>
            </div>
            <?php endif; ?>


            <!-- ─────────── LINHA AMARELA (relatórios) ─────────── -->
            <?php if ($nivelSessao === 'admin' || $nivelSessao === 'treinador'): ?>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <a href="/pre-treino-rfc/views/lista_sono.php" class="app-icon i-yellow" title="Pré-Treinos Registrados"><i class="fas fa-list"></i></a>
                <a href="/pre-treino-rfc/views/lista_pse.php" class="app-icon i-yellow" title="Pós-Treinos Registrados"><i class="fas fa-list"></i></a>
                <a href="/pre-treino-rfc/views/relatorio_presenca_form.php" class="app-icon i-yellow" title="Relatório de Presença"><i class="fas fa-file-lines"></i></a>
            </div>
            <?php endif; ?>

            <!-- ─────────── LINHA VERMELHA (admin – bloqueio) ─────────── -->
            <?php if ($nivelSessao === 'admin'): ?>
            <div class="d-flex justify-content-center gap-3">
                <form method="POST" action="/pre-treino-rfc/controllers/routerToggleRegistro.php">
                    <input type="hidden" name="status" value="<?= $statusRegistro == 0 ? 1 : 0 ?>">
                    <button type="submit" class="app-icon i-red"
                            title="<?= $statusRegistro == 0 ? 'Bloquear Registros' : 'Desbloquear Registros' ?>">
                        <i class="fas <?= $statusRegistro == 0 ? 'fa-lock' : 'fa-lock-open' ?>"></i>
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<?php ob_end_flush(); ?>
</html>
