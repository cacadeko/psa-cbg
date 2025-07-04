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
    <title>Cadastrar Atleta</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome p/ ícone sair -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body{
            background: #07272D;
            color: #FFF;
            min-height: 100vh;
            display: block;
            align-items: center;
            justify-content: center;
            padding: 10px;
            margin: 0 auto;
            width: 80%;
        }
        /* ─── top-bar com logo + sair ─── */
        .top-bar{
            position: fixed;
            align-items: center;
            z-index: 1000;
            width: 100%;
            height: auto;
            left: 0;
            top: 0;
        }
        .box-top-bar {
            width: 100%;
            height: auto;
            background: #5d5d5d;
            display: flex;
            flex-direction: row;
            justify-content: space-around;
            padding: 7px 0;
        }
        
        .top-bar .logo-mini{ width:220px; }
        .logout-icon{
            width: 46px;
            height: 46px;
            font-size: 26px;
            border-radius: 18px;
            background: #f5f5f7;
            color: #dc3545;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all .25s;
            text-decoration: none;
        }
        .home-icon{
            width: 46px;
            height: 46px;
            font-size: 26px;
            border-radius: 18px;
            background: #f5f5f7;
            color:rgb(11, 104, 39);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all .25s;
            text-decoration: none;
        }
        .logout-icon:hover{ transform:scale(1.08); background:#e8e8ea; }

        /* ─── container translúcido ─── */
        .box{
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
            border-radius: 14px;
            padding: 32px 28px;
            width: 100%;
            margin: 60px 0 0 0;
        }
        label{font-weight:500;color:#dddddd;}  /* labels em #dddddd */

        /* ─── logo fixa inferior-direita ─── */
        .logo-fixed{
            width: 117px;
        }
    </style>
</head>
<body>

<!-- top-bar -->
<div class="top-bar">
    <div class="box-top-bar">
        <a href="/psa-cbg/"
        class="home-icon" title="Home"><i class="fas fa-home"></i></a>

        <img src="https://athleticmap.com/images/logo-atm.png" class="logo-fixed" alt="ATM">

        <a href="/psa-cbg/controllers/LogoutController.php"
        class="logout-icon" title="Sair"><i class="fas fa-power-off"></i></a>
    </div>
</div>

<!-- formulário -->
<div class="box">
    <h3 class="text-center mb-4">Cadastrar Atleta</h3>

    <form method="POST" action="/psa-cbg/controllers/routerAtleta.php">

        <!-- id do treinador logado -->
        <input type="hidden" name="treinador_id"
               value="<?= htmlspecialchars($_SESSION['usuario_id'] ?? 0) ?>">

        <input type="hidden" name="acesso" value="2">

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label">Nome *</label>
                <input type="text" name="nome" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Data de Nascimento *</label>
                <input type="date" name="data_nascimento" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Posição:</label>
                <select class="form-select" name="posicao" required>
                    <option value="" disabled selected>Selecione a posição</option>
                    <option value="Nao-aplicavel">Não aplicável</option>
                    <option value="Goleiro">Goleiro</option>
                    <option value="Zagueiro">Zagueiro</option>
                    <option value="Lateral">Lateral</option>
                    <option value="Volante">Volante</option>
                    <option value="Meia">Meia</option>
                    <option value="Atacante">Atacante</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">E-mail:</label>
                <input type="text" class="form-control" name="email">
            </div>
            <div class="mb-3">
                <label class="form-label">Telefone/Celular:</label>
                <input type="text" class="form-control" name="telefone">
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria:</label>
                <select class="form-select" name="categoria" required>
                    <option value="" disabled selected>Selecione a categoria</option>
                    <option value="Nao-aplicavel">Não aplicável</option>
                    <option value="Sub-08">Sub-08</option>
                    <option value="Sub-09">Sub-09</option>
                    <option value="Sub-10">Sub-10</option>
                    <option value="Sub-11">Sub-11</option>
                    <option value="Sub-12">Sub-12</option>
                    <option value="Sub-13">Sub-13</option>
                    <option value="Sub-14">Sub-14</option>
                    <option value="Sub-15">Sub-15</option>
                    <option value="Sub-16">Sub-16</option>
                    <option value="Sub-17">Sub-17</option>
                    <option value="Sub-18">Sub-18</option>
                    <option value="Sub-19">Sub-19</option>
                    <option value="Sub-20">Sub-20</option>
                    <option value="Sub-21">Sub-21</option>
                    <option value="Sub-22">Sub-22</option>
                    <option value="Sub-23">Sub-23</option>
                    <option value="Profissional">Profissional</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Senha inicial</label>
                <input type="password" name="senha" class="form-control">
            </div>
        </div>

        <button class="btn btn-primary w-100">Salvar</button>
        <a href="/psa-cbg/index.php" class="btn btn-light w-100 mt-2">Cancelar</a>
    </form>
</div>
    <!-- logo inferior-direita -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
