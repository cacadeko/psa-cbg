<?php
session_start(); if(!isset($_SESSION['usuario'])) exit;
$id=(int)($_GET['id']??0); require_once '../config/Database.php';
$pdo=\Config\Database::getConnection();
$u=$pdo->prepare("SELECT * FROM usuarios WHERE id=?");$u->execute([$id]);
$user=$u->fetch(PDO::FETCH_ASSOC) ?: die('Usuário não encontrado');
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Editar Usuário</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body{background:#07272D;color:#FFF;display:flex;justify-content:center;align-items:center;min-height:100vh;padding:40px}
  label, h3{
    color:#dddddd;      /* cinza claro */
    font-weight:500;    /* mantém a ênfase se quiser */
  }  
</style>
</head><body><div class="card p-4" style="background:rgba(255,255,255,0.08);max-width:500px;width:100%;">
<h3 class="text-center mb-4">Editar Usuário</h3>
<form method="POST" action="/psa-cbg/controllers/routerEditarUsuario.php">
<input type="hidden" name="id" value="<?= $user['id'] ?>">
<div class="mb-3"><label class="form-label">Nome *</label><input name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required></div>
<div class="mb-3"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required></div>
<div class="mb-3"><label class="form-label">Senha (deixe vazio para manter)</label><input type="password" name="senha" class="form-control"></div>
<div class="mb-3"><label class="form-label">Nível</label>
<select name="nivel" class="form-select">
  <?php foreach(['atleta','treinador','admin'] as $n): ?>
    <option value="<?= $n ?>" <?= $user['nivel']==$n?'selected':'' ?>><?= ucfirst($n) ?></option>
  <?php endforeach; ?>
</select></div>
<button class="btn btn-primary w-100">Salvar</button>
<a href="/psa-cbg/views/lista_usuarios.php" class="btn btn-light w-100 mt-2">Cancelar</a>
</form></div></body></html>
