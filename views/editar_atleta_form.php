<?php
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('Location: /index.php');
        exit;
    }

    require_once '../config/Database.php';
    require_once '../models/Atleta.php';
    require_once '../controllers/AtletaController.php';

    use Models\Atleta;
    use Controllers\AtletaController;

    $atletaController = new AtletaController();
    $atleta = null;

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $atleta = Atleta::buscarPorId($id);
    }

    if (!$atleta) {
        die("Atleta não encontrado.");
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Athletic Map - Editar Atleta</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-4">
            <h2 class="text-center">Editar Atleta</h2>
            <form method="POST" action="/psa-cbg/controllers/routerEditarAtleta.php">
                <input type="hidden" name="id" value="<?php echo $atleta['id']; ?>">
                <div class="mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($atleta['nome']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" name="data_nascimento" value="<?php echo htmlspecialchars($atleta['data_nascimento']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Posição</label>
                    <select class="form-select" name="posicao" required>
                        <option value="Nao-aplicavel" <?php echo ($atleta['categoria'] == 'Nao-aplicavel') ? 'selected' : ''; ?>>Nao-aplicavel</option>
                        <option value="Goleiro" <?php echo ($atleta['posicao'] == 'Goleiro') ? 'selected' : ''; ?>>Goleiro</option>
                        <option value="Zagueiro" <?php echo ($atleta['posicao'] == 'Zagueiro') ? 'selected' : ''; ?>>Zagueiro</option>
                        <option value="Lateral" <?php echo ($atleta['posicao'] == 'Lateral') ? 'selected' : ''; ?>>Lateral</option>
                        <option value="Volante" <?php echo ($atleta['posicao'] == 'Volante') ? 'selected' : ''; ?>>Volante</option>
                        <option value="Meia" <?php echo ($atleta['posicao'] == 'Meia') ? 'selected' : ''; ?>>Meia</option>
                        <option value="Atacante" <?php echo ($atleta['posicao'] == 'Atacante') ? 'selected' : ''; ?>>Atacante</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-mail</label>
                    <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($atleta['email']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Telefone/Celular</label>
                    <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($atleta['telefone']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select class="form-select" name="categoria" required>
                        <option value="Nao-aplicavel" <?php echo ($atleta['categoria'] == 'Nao-aplicavel') ? 'selected' : ''; ?>>Nao-aplicavel</option>
                        <option value="Sub-08" <?php echo ($atleta['categoria'] == 'Sub-08') ? 'selected' : ''; ?>>Sub-08</option>
                        <option value="Sub-09" <?php echo ($atleta['categoria'] == 'Sub-09') ? 'selected' : ''; ?>>Sub-09</option>
                        <option value="Sub-10" <?php echo ($atleta['categoria'] == 'Sub-10') ? 'selected' : ''; ?>>Sub-10</option>
                        <option value="Sub-11" <?php echo ($atleta['categoria'] == 'Sub-11') ? 'selected' : ''; ?>>Sub-11</option>
                        <option value="Sub-12" <?php echo ($atleta['categoria'] == 'Sub-12') ? 'selected' : ''; ?>>Sub-12</option>
                        <option value="Sub-13" <?php echo ($atleta['categoria'] == 'Sub-13') ? 'selected' : ''; ?>>Sub-13</option>
                        <option value="Sub-14" <?php echo ($atleta['categoria'] == 'Sub-14') ? 'selected' : ''; ?>>Sub-14</option>
                        <option value="Sub-15" <?php echo ($atleta['categoria'] == 'Sub-15') ? 'selected' : ''; ?>>Sub-15</option>
                        <option value="Sub-16" <?php echo ($atleta['categoria'] == 'Sub-16') ? 'selected' : ''; ?>>Sub-16</option>
                        <option value="Sub-17" <?php echo ($atleta['categoria'] == 'Sub-17') ? 'selected' : ''; ?>>Sub-17</option>
                        <option value="Sub-18" <?php echo ($atleta['categoria'] == 'Sub-18') ? 'selected' : ''; ?>>Sub-18</option>
                        <option value="Sub-19" <?php echo ($atleta['categoria'] == 'Sub-19') ? 'selected' : ''; ?>>Sub-19</option>
                        <option value="Sub-20" <?php echo ($atleta['categoria'] == 'Sub-20') ? 'selected' : ''; ?>>Sub-20</option>
                        <option value="Sub-21" <?php echo ($atleta['categoria'] == 'Sub-21') ? 'selected' : ''; ?>>Sub-21</option>
                        <option value="Sub-22" <?php echo ($atleta['categoria'] == 'Sub-22') ? 'selected' : ''; ?>>Sub-22</option>
                        <option value="Sub-23" <?php echo ($atleta['categoria'] == 'Sub-23') ? 'selected' : ''; ?>>Sub-23</option>
                        <option value="Profissional" <?php echo ($atleta['categoria'] == 'Profissional') ? 'selected' : ''; ?>>Profissional</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Acesso</label>
                    <select class="form-select" name="acesso" required>
                        <option value="1" <?php echo ($atleta['acesso'] == '1') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="2" <?php echo ($atleta['acesso'] == '2') ? 'selected' : ''; ?>>Atleta</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Nova Senha:</label>
                    <input type="password" class="form-control" name="senha" id="senha" placeholder="Digite a nova senha">
                    <small class="text-muted">Se não quiser alterar a senha, deixe este campo vazio.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                <a href="/psa-cbg/index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Máscara para telefone DDD + número brasileiro
            document.querySelector("input[name='telefone']").addEventListener("input", function(e) {
                let value = e.target.value.replace(/\D/g, "");
                if (value.length > 11) value = value.slice(0, 11);
                e.target.value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
            });

            // Máscara para e-mail
            document.querySelector("input[name='email']").addEventListener("input", function(e) {
                e.target.value = e.target.value.replace(/[^a-zA-Z0-9@._-]/g, "");
            });
        });
    </script>
    </body>
</html>