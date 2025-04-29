<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /psa-cbg/views/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map – Novo Treinador</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#07272D;
            color:#FFF;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:40px 10px;
        }
        .box{
            background:rgba(255,255,255,0.08);
            backdrop-filter:blur(4px);
            border-radius:14px;
            padding:32px 28px;
            max-width:560px;
            width:100%;
        }
        .box h3{margin-bottom:28px;}
        label{font-weight:500;}
    </style>
</head>
<body>

<div class="box">
    <h3 class="text-center">Cadastrar Treinador</h3>

    <!-- ajuste o action se usar router/controller diferente -->
    <form method="POST" action="/psa-cbg/controllers/routerTreinador.php">

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">E-mail *</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Telefone</label>
                <input type="text" name="telefone" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Especialidade</label>
                <input type="text" name="especialidade" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label">Data de Contratação</label>
                <input type="date" name="data_contratacao" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" class="form-control" rows="3"></textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">Salvar</button>
        <a href="/psa-cbg/index.php" class="btn btn-light w-100 mt-2">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
