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
    header('Location: /psa-cbg/views/login.php');
    exit;
}

/* ───── 3. VARIÁVEIS DE SESSÃO ───── */
$usuarioSessao = $_SESSION['usuario'];
$nivelSessao   = $_SESSION['nivel'];

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
        .app-icon {
            width: 78px;
            height: 78px;
            border-radius: 22px;
            background: #f5f5f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: .25s;
            display: block;
            margin: 0;
            padding: 0;
        }
        
        .app-icon i {
            display: block;
            font-size: 42px;
            margin: 5px 0 0 0;
        }
                
        .app-icon span {
            font-size: 11.6px;
            color: #696e75;
            display: inline-block !important;
            line-height: 10px;
            font-weight: normal;
            width: 70px;
        }
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
    <!-- MENU SUPERIOR -->
        <a href="/psa-cbg/index.php" style="color:white; margin-right:30px; font-weight:bold; text-decoration:none;">🏠 Início</a>
        <a href="/psa-cbg/views/dashboard_graficos.php" style="color:white; margin-right:30px; font-weight:bold; text-decoration:none;">📊 Gráficos e Relatórios</a>
                <a href="/psa-cbg/controllers/LogoutController.php" class="app-icon i-red logout-icon text-center" title="Sair">
            <i class="fas fa-power-off"></i>
        </a>
    </div>



    <!-- painel principal -->
    <div class="container">
        <img src="assets/img/logo-cbg.svg" class="logo" alt="CBG">
        <h3 class="text-center fw-bold mb-2">Módulo PSR &amp; PSE</h3>
        <p class="fw-bold" style="color:#E0E0E0;"><?= htmlspecialchars($usuarioSessao) ?></p>

        <div class="menu-apps">
            <!-- ─────────── LINHA AZUL (cadastros / listas) ─────────── -->
            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin' || $nivelSessao === 'treinador'): ?>
                    <!-- Exemplo de botão com ícone e rótulo -->
                    <a href="/psa-cbg/views/atleta_form.php" class="app-icon i-blue text-center" title="Cadastrar Atleta">
                        <div>
                            <i class="fas fa-running"></i>
                            <span class="small d-block mt-1">Cadastrar Atleta</span>
                        </div>
                    </a>

                    <a href="/psa-cbg/views/lista_atletas.php" class="app-icon i-blue text-center" title="Listar Atletas">
                        <div>
                            <i class="fas fa-users-line"></i>
                            <span class="small d-block mt-1">Listar Atletas</span>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin'): ?>
                    <!-- Treinadores -->
                    <a href="/psa-cbg/views/treinadores_form.php" class="app-icon i-blue text-center" title="Cadastrar Treinador">
                        <i class="fas fa-user-tie"></i>
                        <span class="small d-block mt-1">Cadastrar Treinador</span>
                    </a>
                    <a href="/psa-cbg/views/lista_treinadores.php" class="app-icon i-blue text-center" title="Listar Treinadores">
                        <i class="fas fa-chalkboard-user"></i>
                        <span class="small d-block mt-1">Listar Treinador</span>
                    </a>
                    <!-- Usuários -->
                    <a href="/psa-cbg/views/lista_usuarios.php" class="app-icon i-blue text-center" title="Usuários">
                        <i class="fas fa-user-gear"></i>
                        <span class="small d-block mt-1">Listar Usuários</span>
                    </a>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if ($nivelSessao === 'admin'): ?>
                    <!-- Cadastrar Jogo -->
                    <!-- <a href="/psa-cbg/views/jogo_form.php" class="app-icon i-blue text-center" title="Cadastrar Jogo">
                        <i class="fas fa-futbol"></i>
                        <span class="small d-block mt-1">Cadastrar Jogo</span>
                    </a> -->

                    <!-- Listar Jogos -->
                    <!-- <a href="/psa-cbg/views/lista_jogos.php" class="app-icon i-blue text-center" title="Listar Jogos">
                        <i class="fas fa-calendar-day"></i>
                        <span class="small d-block mt-1">Listar Jogos</span>
                    </a> -->
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
                <a href="/psa-cbg/views/sono_form.php" class="app-icon i-green text-center" title="Pré-Treino">
                    <div>
                        <i class="fas fa-bed"></i>
                        <span class="small d-block mt-1">Pré-Treino</span>
                    </div>
                </a>

                <a href="/psa-cbg/views/pse_form.php" class="app-icon i-green text-center" title="Pós-Treino">
                    <div>
                        <i class="fas fa-heartbeat"></i>
                        <span class="small d-block mt-1">PFG</span>
                    </div>
                </a>
                <a href="/psa-cbg/views/pse_pos_tecnico.php" class="app-icon i-green text-center" title="Pós-Treino">
                    <div>
                        <i class="fas fa-heartbeat"></i>
                        <span class="small d-block mt-1">Pós-Técnico</span>
                    </div>
                </a>
                <a href="/psa-cbg/views/pse_pfe.php" class="app-icon i-green text-center" title="Pós-Treino">
                    <div>
                        <i class="fas fa-heartbeat"></i>
                        <span class="small d-block mt-1">Pós-PFE</span>
                    </div>
                </a>
            </div>
            <?php endif; ?>

            <?php
            $podeRegistrar = ($nivelSessao === 'admin' || $nivelSessao === 'treinador')
                            || ($nivelSessao === 'atleta' && $statusRegistro == 0);
            ?>
            <?php if ($podeRegistrar): ?>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <?php if (in_array($nivelSessao, ['admin','treinador'])): ?>
                    <!-- ★ NOVO: Minutagem -->
                    <!-- <a href="/psa-cbg/views/minutagem_form.php" class="app-icon i-green text-center" title="Cadastrar Minutagem">
                        <i class="fas fa-stopwatch"></i>
                        <span class="small d-block mt-1">Cadastrar Minutagem</span>
                    </a>

                    <a href="/psa-cbg/views/lista_minutagem.php" class="app-icon i-green text-center" title="Listar Minutagens">
                        <i class="fas fa-table-list"></i>
                        <span class="small d-block mt-1">Listar Minutagem</span>
                    </a> -->
                <?php endif; ?>
            </div>
            <?php endif; ?>


            <!-- ─────────── LINHA AMARELA (relatórios) ─────────── -->
            <?php if ($nivelSessao === 'admin' || $nivelSessao === 'treinador'): ?>
            <div class="d-flex justify-content-center gap-3 mb-3">
                <a href="/psa-cbg/views/lista_sono.php" class="app-icon i-yellow text-center" title="Pré-Treinos Registrados">
                    <i class="fas fa-list"></i>
                    <span class="small d-block mt-1">Pré-Treinos Registrados</span>
                </a>
                <a href="/psa-cbg/views/lista_pse.php" class="app-icon i-yellow text-center" title="Pós-Treinos Registrados">
                    <i class="fas fa-list"></i>
                    <span class="small d-block mt-1">PFG Registrados</span>
                </a>
                <a href="/psa-cbg/views/lista_psa_treino.php" class="app-icon i-yellow text-center" title="Pós-Treinos Registrados">
                    <i class="fas fa-list"></i>
                    <span class="small d-block mt-1">Pós-Técnico Registrados</span>
                </a>
                <a href="/psa-cbg/views/lista_pfe.php" class="app-icon i-yellow text-center" title="Pós-Treinos Registrados">
                    <i class="fas fa-list"></i>
                    <span class="small d-block mt-1">Pós-PFE Registrados</span>
                </a>
            </div>
            <?php endif; ?>

            <!-- ─────────── LINHA AMARELA (relatórios) ─────────── -->
            <?php if ($nivelSessao === 'admin' || $nivelSessao === 'treinador'): ?>
                <div class="d-flex justify-content-center gap-3 mb-3">
                    <a href="/psa-cbg/views/relatorio_presenca_form.php" class="app-icon i-yellow text-center" title="Relatório de Presença">
                        <i class="fas fa-file-lines"></i>
                        <span class="small d-block mt-1">Relatório de Presença</span>
                    </a>
                </div>
            <?php endif; ?>

            <!-- ─────────── LINHA VERMELHA (admin – bloqueio) ─────────── -->
            <?php if ($nivelSessao === 'admin'): ?>
            <div class="d-flex justify-content-center gap-3">
                <form method="POST" action="/psa-cbg/controllers/routerToggleRegistro.php">
                    <input type="hidden" name="status" value="<?= $statusRegistro == 0 ? 1 : 0 ?>">
                    <button type="submit" class="app-icon i-red" title="<?= $statusRegistro == 0 ? 'Bloquear Registros' : 'Desbloquear Registros' ?>">
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
