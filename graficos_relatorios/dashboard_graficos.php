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
    <title>Dashboard - PSA CBG</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        .sidebar {
            height: 100vh;
            width: 220px;
            position: fixed;
            background-color: #0033A0;
            padding-top: 20px;
            color: white;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #0051cc;
        }

        .main {
            margin-left: 240px;
            padding: 20px;
        }

        iframe {
            width: 100%;
            height: 90vh;
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background: white;
        }

        .bloco-volume {
  display: flex;
  justify-content: center;
  gap: 50px;
  background-color: #fefefe;
  padding: 20px 0;
}

.bloco {
  text-align: center;
  padding: 15px 25px;
  font-weight: bold;
  flex: 1;
  min-width: 300px;
}

    </style>
</head>
<body>

<div class="sidebar">
    <h2 style="text-align:center;">📊 Dashboard</h2>
    <a href="graficos.php" target="conteudo">Distribuição Semanal</a>
    <a href="percepcao_esforco_semana.php" target="conteudo">Percepcao Semanal de Esforco</a>
    <a href="percepcao_grupo.php" target="conteudo">Percepcao de Esforço por Grupo</a>
    <a href="percepcao_esforco_semanal.php" target="conteudo">Percepcao de Esforco Semanal</a>
    <a href="diferenca_semanas.php" target="conteudo">Manutenção de Volume de Treino</a>
    <a href="carga_agudo_cronica.php" target="conteudo">Carga Agudo-Crônica</a>
    <a href="percepcao_fadiga.php" target="conteudo">Percepção de Fadiga</a>  
    <a href="carga_semanal.php" target="conteudo">Carga Semanal</a>  
</div>

<div class="main">
    <iframe name="conteudo" src="graficos.php"></iframe>
</div>

<script>
    function navegar(ancora) {
        const frame = document.querySelector("iframe");
        const atual = frame.src;
        if (!atual.includes("#")) {
            frame.src += "#" + ancora;
        } else {
            frame.src = atual.split("#")[0] + "#" + ancora;
        }
    }
</script>
</body>
</html>
