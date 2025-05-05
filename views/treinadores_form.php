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
  <title>Athletic Map – Novo Treinador</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: #07272D;
      color: #FFF;
      min-height: 100vh;
      display: block;
      padding: 10px;
      margin: 0 auto;
      width: 80%;
    }
    .top-bar {
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
    }
    .box-top-bar {
      background: #5d5d5d;
      display: flex;
      justify-content: space-around;
      align-items: center;
      padding: 7px 0;
    }
    .home-icon, .logout-icon {
      width: 46px;
      height: 46px;
      font-size: 26px;
      border-radius: 18px;
      background: #f5f5f7;
      display: flex;
      justify-content: center;
      align-items: center;
      transition: all .25s;
      text-decoration: none;
    }
    .home-icon { color: rgb(11,104,39); }
    .logout-icon { color: #dc3545; }
    .home-icon:hover, .logout-icon:hover {
      transform: scale(1.08);
      background: #e8e8ea;
    }
    .logo-fixed { width: 117px; }
    label { font-weight: 500; color: #dddddd; }
    .box {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(4px);
      border-radius: 14px;
      padding: 32px 28px;
      width: 100%;
      margin: 60px 0 0 0;
    }
  </style>
</head>

<body>

<!-- TOP-BAR -->
<div class="top-bar">
  <div class="box-top-bar">
    <a href="/psa-cbg/" class="home-icon" title="Home"><i class="fas fa-home"></i></a>
    <img src="https://athleticmap.com/images/logo-atm.png" class="logo-fixed" alt="ATM">
    <a href="/psa-cbg/controllers/LogoutController.php" class="logout-icon" title="Sair"><i class="fas fa-power-off"></i></a>
  </div>
</div>

<!-- FORMULÁRIO -->
<div class="box">
  <h3 class="text-center mb-4">Cadastrar Treinador</h3>

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
