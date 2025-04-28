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
    <title>Athletic Map - Cadastro de PSR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/styles.css">
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
<form method="POST" action="/pre-treino-rfc/controllers/routerSono.php">
    <div class="container mt-4">
        <h2 class="text-center">Cadastro de PSR</h2>
            <div class="mb-3">
                <h4>Atleta:</h4>
                <input type="text" name="atleta_nome" value="<?php echo htmlspecialchars($_SESSION['usuario']); ?>" readonly><br>
                <input type="hidden" name="atleta_id" value="<?php echo $_SESSION['usuario_id']; ?>">
            </div><br><br>
            <div class="mb-3">
                <h4>Percepção Subjetiva de Recuperação</h4>
                <?php 
                $labels = [
                    "0" => "Nada recuperado",
                    "1" => "Profundamente mal recuperado",
                    "2" => "Extremamente mal recuperado",
                    "3" => "Muito mal recuperado",
                    "4" => "Mal recuperado",
                    "5" => "Razoavelmente recuperado",
                    "6" => "Bem recuperado",
                    "7" => "Muito bem recuperado",
                    "8" => "Extremamente bem recuperado",
                    "9" => "Excepcionalmente bem recuperado",
                    "10" => "Totalmente recuperado"
                ];
                foreach ($labels as $valor => $descricao): ?>
                    <span id="linha_sono_<?php echo $valor ?>" class="linha_sono"> <input class="form-check-input" type="radio" name="avaliacao_psr" value="<?php echo $valor; ?>" required> <?php echo $descricao; ?></span>
                <?php endforeach; ?><br>

            </div>
            <div class="mb-3">

                <h4>Sono (Quantas horas dormiu? Quantas vezes acordou? Como acordou?)</h4>
                <?php 
                $labels = [
                    "0" => "Não descansado",
                    "1" => "Pouco descansado",
                    "2" => "Neutro",
                    "3" => "Descansado",
                    "4" => "Bem descansado",
                    "5" => "Muito descansado",
                ];
                foreach ($labels as $valor => $descricao): ?>
                    <span id="linha_sono_<?php echo $valor ?>" class="linha_sono"> <input class="form-check-input" type="radio" name="avaliacao_sono" value="<?php echo $valor; ?>" required> <?php echo $descricao; ?></span>
                <?php endforeach; ?><br>
            </div>
            <div class="mb-3">
                <h4>Acordou Durante a Noite?</h4>
                <input class="form-check-input" type="radio" name="acordou_durante_a_noite" value="Sim" required> Sim<br><br>
                <input class="form-check-input" type="radio" name="acordou_durante_a_noite" value="Não" required> Não<br>
            </div>
            <br>
            <div class="mb-3">
                <h4>Que horas foi dormir?</h4>
                <input type="time" class="form-control" name="hora_dormir" required><br>
            </div>
            <div class="mb-3">
                <h4>Acordou que horas?</h4>
                <input type="time" class="form-control" name="hora_acordar" required><br>    
            </div>

    <br>
    </div>

    <div class="container mt-4">
        <h4>Local da Dor</h4>
        <img src="https://athleticmap.com/images/mapador.png" alt="Mapa Dor" class="img-fluid" style="max-width: 300px;">
        <div class="mb-3">
            <label class="form-label">Está sentindo alguma dor ou desconforto no corpo?</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="dor_corpo" value="Sim" onclick="document.getElementById('local_dor')" required>
                    <label class="form-label">Sim</label>
                </div>
                <br>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="dor_corpo" value="Não" onclick="document.getElementById('local_dor')" required>
                    <label class="form-label">Não</label>
                </div>
            </div>
            
            <div id="local_dor" class="mb-3">
                <label class="form-label">Se sim, em que local?</label>
                <input type="text" class="form-control" name="local_dor" placeholder="Descreva o local da dor">
            </div>
        </div>
    </div>      
    <br>
    <div class="container mt-4">
        <div class="mb-3">    
            <h4>Intensidade de Dor</h4>
            <?php 
            $x=0;
            $intensidade_labels = ['Pior dor possivel', 'Dor muito severa', 'Dor severa' , 'Dor moderada', 'Dor leve', 'Sem dor'];
            foreach ($intensidade_labels as $valor): 
            
            ?>
                <span class="linha_sono" id="linha_sono_<?php echo $x; ?>"><input class="form-check-input" type="radio" name="intensidade_dor" value="<?php echo $valor; ?>"  /> <?php echo $valor; ?></span>
            <?php
                $x++; 
                endforeach; ?>
        </div>
    </div>
    <br> 
    <button type="submit" class="btn btn-primary w-100">Registrar</button>
    <a href="/pre-treino-rfc/index.php" class="btn btn-secondary w-100 mt-2">Cancelar</a>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
