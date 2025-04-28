<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}

/* ─── carrega somente o controller (ele inclui o model) ─── */
require_once __DIR__ . '/../controllers/AtletaController.php';
use Controllers\AtletaController;

/* lista já filtrada conforme o nível (admin | treinador) */
$atletaController = new AtletaController();
$atletas          = $atletaController->listar();   // ← filtrado
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Lista de Atletas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#07272D;color:#FFF;padding:20px;}
        .container{background:rgba(255,255,255,0.1);padding:20px;border-radius:10px;}
        .logo{width:150px;display:block;margin:0 auto 20px;}
        .table-responsive{white-space:nowrap;}
    </style>
</head>
<body>
<div class="container">
    <img src="https://athleticmap.com/images/logo-atm.png" class="logo" alt="Athletic Map Logo">
    <h2 class="text-center">Lista de Atletas</h2>

    <div class="table-responsive">
        <table class="table table-striped table-dark align-middle">
            <thead>
            <tr>
                <th>Nome</th>
                <th>Data de Nascimento</th>
                <th>Posição</th>
                <th>E-mail</th>
                <th>Telefone</th>
                <th>Categoria</th>
                <th class="text-center">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($atletas): ?>
                <?php foreach ($atletas as $atleta): ?>
                    <tr>
                        <td><?= htmlspecialchars($atleta['nome']) ?></td>
                        <td><?= htmlspecialchars($atleta['data_nascimento']) ?></td>
                        <td><?= htmlspecialchars($atleta['posicao']) ?></td>
                        <td><?= htmlspecialchars($atleta['email']) ?></td>
                        <td><?= htmlspecialchars($atleta['telefone']) ?></td>
                        <td><?= htmlspecialchars($atleta['categoria']) ?></td>
                        <td class="text-center">
                            <a href="/pre-treino-rfc/views/editar_atleta_form.php?id=<?= $atleta['id'] ?>"
                               class="btn btn-warning btn-sm">Editar</a>
                            <a href="/pre-treino-rfc/controllers/routerExcluirAtleta.php?id=<?= $atleta['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tem certeza que deseja excluir este atleta?');">
                               Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">Nenhum atleta encontrado.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="/pre-treino-rfc/index.php" class="btn btn-warning btn-sm">Voltar para o menu</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
