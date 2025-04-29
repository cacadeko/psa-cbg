<?php session_start(); if(!isset($_SESSION['usuario'])) exit; ?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>Novo Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#07272D;color:#FFF;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:40px}</style>
</head><body><div class="card p-4" style="background:rgba(255,255,255,0.08);max-width:500px;width:100%;">
<h3 class="text-center mb-4">Cadastrar Usuário</h3>
<form method="POST" action="/psa-cbg/controllers/routerUsuario.php">
<div class="mb-3"><label class="form-label">Nome *</label><input name="nome" class="form-control" required></div>
<div class="mb-3"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Senha *</label><input type="password" name="senha" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Nível</label>
<select name="nivel" class="form-select">
  <option value="aluno">Aluno</option><option value="treinador">Treinador</option><option value="admin">Admin</option>
</select></div>
<button class="btn btn-primary w-100">Salvar</button>
<a href="/psa-cbg/index.php" class="btn btn-light w-100 mt-2">Cancelar</a>
</form></div></body></html>
