<?php
/******************** SEGURANÇA *************************/
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}

/******************** BUSCA DADOS DO TREINADOR **********/
if (!isset($_GET['id'])) {
    die('ID inválido');
}
$id = (int) $_GET['id'];

require_once '../config/Database.php';
use Config\Database;

$pdo  = Database::getConnection();
$stmt = $pdo->prepare("SELECT * FROM treinadores WHERE id = ?");
$stmt->execute([$id]);
$treinador = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$treinador) {
    die('Treinador não encontrado');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Treinador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{background:#07272D;color:#FFF;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:40px 12px;}
        .box{background:rgba(255,255,255,0.08);backdrop-filter:blur(4px);border-radius:14px;padding:32px 28px;max-width:560px;width:100%;}
        label{font-weight:500;}
    </style>
</head>
<body>
<div class="box">
    <h3 class="text-center mb-4">Editar Treinador</h3>

    <form method="POST" action="/psa-cbg/controllers/routerEditarTreinador.php">
        <input type="hidden" name="id" value="<?= $treinador['id'] ?>">

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($treinador['nome']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($treinador['email']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($treinador['telefone']) ?>">
            </div>

            <div class="col-md-6">
                <label class="form-label">Especialidade</label>
                <input type="text" name="especialidade" class="form-control" value="<?= htmlspecialchars($treinador['especialidade']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Data de Contratação</label>
                <input type="date" name="data_contratacao" class="form-control"
                       value="<?= $treinador['data_contratacao'] ? date('Y-m-d', strtotime($treinador['data_contratacao'])) : '' ?>">
            </div>

            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"><?= htmlspecialchars($treinador['observacoes']) ?></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
        <a href="/psa-cbg/views/lista_treinadores.php" class="btn btn-light w-100 mt-2">Cancelar</a>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
