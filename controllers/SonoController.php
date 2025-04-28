<?php
namespace Controllers;

use Models\QualidadeSono;
use Config\Database;
use Exception; // <-- ADICIONAR ESTA LINHA


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
//     exit; // Interrompe o script para inspecionar os dados recebidos
// }

class SonoController {
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $atleta_id = $_POST['atleta_id'] ?? null;
            $hora_dormir = $_POST['hora_dormir'] ?? null;
            $hora_acordar = $_POST['hora_acordar'] ?? null;
            $dor_corpo = $_POST['dor_corpo'] ?? null;
            $local_dor = $_POST['local_dor'] ?? null;
            $intensidade_dor = $_POST['intensidade_dor'] ?? null;
            $avaliacao_psr = $_POST['avaliacao_psr'] ?? null;
            $avaliacao_sono = $_POST['avaliacao_sono'] ?? null;
            $acordou_durante_a_noite = $_POST['acordou_durante_a_noite'] ?? null;

            $data_hoje = date('Y-m-d');

            // echo "<pre>";
            // print("id-atleta:".$atleta_id."<br> Hora dormir:".$hora_dormir."<br> Hora acordar:".$hora_acordar."<br> PSR:".$avaliacao_psr."<br> Sono:".$avaliacao_sono."<br> Acordou:".$acordou_durante_a_noite."<br> Dor".$dor_corpo."<br>Local:".$local_dor."<br> Intensidade:".$intensidade_dor)."<br>";
            // print_r($_POST);
            // echo "</pre>";
            // exit; // Interrompe o script para inspecionar os dados recebidos

            // echo "<pre>";
            // print($local_dor);
            // echo "</pre>";
            // exit; // Interrompe o script para inspecionar os dados recebidos


            //Verifica se algum campo obrigatório está vazio
            if (!$atleta_id || !$hora_dormir ) { 
                die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
            }

            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM qualidade_sono WHERE atleta_id = ? AND DATE(created_at) = ?");
            $stmt->execute([$atleta_id, $data_hoje]);
            $existeCadastro = $stmt->fetchColumn();

            if ($existeCadastro > 0) {
                die("Erro: Apenas um cadastro de sono permitido por dia.");
            }

            try {
                if (QualidadeSono::create($atleta_id, $hora_dormir, $hora_acordar, $dor_corpo, $local_dor, $intensidade_dor, $avaliacao_psr, $avaliacao_sono, $acordou_durante_a_noite)) {
                    header('Location: /pre-treino-rfc/index.php?success=1');
                    exit;
                } else {
                    throw new Exception("Erro ao inserir no banco de dados.");
                }
            } catch (Exception $e) {
                die("Erro ao registrar qualidade do sono: " . $e->getMessage());
            }
        }
    }

    public function listarTodos($data = null)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
    
        $nivel = $_SESSION['nivel'] ?? 'aluno';
        $treinadorId = $_SESSION['usuario_id'] ?? 0;
    
        if ($nivel === 'admin') {
            return \Models\QualidadeSono::listarTodos($data);
        }
    
        if ($nivel === 'treinador') {
            return \Models\QualidadeSono::listarPorTreinador($treinadorId);
        }
    
        return []; // Outros níveis não podem ver nada
    }
    

    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = $_GET['id'];
            if (QualidadeSono::excluir($id)) {
                header('Location: /pre-treino-rfc/views/lista_sono.php?success=1');
                exit;
            } else {
                die("Erro ao excluir registro de sono.");
            }
        }
    }

    

}


