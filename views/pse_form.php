<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: /pre-treino-rfc/views/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletic Map - Formulário PSE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-check-input {
            width: 1.5em;
            height: 1.5em;
        }

        .linha_sono {
            display: block;
            width: 100%;
            padding: 25px;
        }

        #linha_sono_0 {
            background: #bd2b2b;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_1 {
            background: #cb6767;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_2 {
            background: #96408b;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_3 {
            background: #b168a0;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_4 {
            background: #817fb1;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_5 {
            background: #3279a7;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
        #linha_sono_6 {
            background: #4099bb;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
        #linha_sono_7 {
            background: #65c0db;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
        #linha_sono_8 {
            background: #8cb435;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
        #linha_sono_9 {
            background: #aacc53;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }

        #linha_sono_10 {
            background: #999999;
            font-size: 20px;
            font-weight: bold;
            color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Formulário PSE</h1>
        <h2 class="text-center">Percepção Subjetiva de Esforço</h2><br><br>
        <form method="POST" action="/pre-treino-rfc/controllers/routerPSE.php">
            <div class="mb-3">
                <h4>Atleta:</h4>
                <input type="text" class="form-control" name="atleta_nome" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" readonly>
                <input type="hidden" name="atleta_id" value="<?php echo $_SESSION['usuario_id']; ?>">
            </div>
            <div class="mb-3">
                <h4>Como está se sentindo hoje?</h4>
                <textarea class="form-control" name="descricao" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <div class="container mt-4">
                    <div class="mb-3">    
                        <h4>QUAL FOI A SENSAÇÃO DE CANSAÇO FÍSICO DO TREINO/JOGO  DE HOJE?
                        </h4>
                        <?php 
                        $x=10;
                        $y=0;
                        $intensidade_labels = ['0 - Nenhum esforço', '1 - Leve', '2 - Extremamente leve' , '3 - Moderado', '4 - Um pouco cansativo', '5 - Cansativo', '6 - Mais cansativo', '7 - Muito cansativo', '8 - Extremamente cansativo', '9 - Esforço máximo', '10 - Exausto'];
                        foreach ($intensidade_labels as $valor): 
                        
                        ?>
                            <span class="linha_sono" id="linha_sono_<?php echo $x; ?>"><input class="form-check-input" type="radio" name="nota_pse" value="<?php echo $y; ?>" required /> <?php echo $valor; ?></span>
                        <?php
                            $x--; 
                            $y++;
                            endforeach; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrar</button>
            <a href="/pre-treino-rfc/index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
            </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
