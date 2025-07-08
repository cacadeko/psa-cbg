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
            $avaliacao_recuperacao = $_POST['avaliacao_recuperacao'] ?? null;
            $avaliacao_fadiga = $_POST['avaliacao_fadiga'] ?? null;
            $avaliacao_sono = $_POST['avaliacao_sono'] ?? null;
            $avaliacao_dor = $_POST['avaliacao_dor'] ?? null;
            $horas_sono_total = $_POST['horas_sono_total'] ?? null;
            $tempo_para_dormir = $_POST['tempo_para_dormir'] ?? null;
            $uso_medicacao = isset($_POST['uso_medicacao']) ? implode(',', $_POST['uso_medicacao']) : null;
            $acordou_durante_a_noite = $_POST['acordou_durante_a_noite'] ?? null;
            $local_dor = $_POST['local_dor'] ?? null;
            $dor_corpo = $_POST['dor_corpo'] ?? null;
            $estresse_geral = $_POST['estresse_geral'] ?? null;
            $estresse_umor = $_POST['estresse_umor'] ?? null;
            $periodo_premenstrual = $_POST['periodo_premenstrual'] ?? null;
            $periodo_menstrual = $_POST['periodo_menstrual'] ?? null;
            $uso_medicacao_outro = $_POST['uso_medicacao_outro'] ?? null;
            $motivo_medicacao = isset($_POST['motivo_medicacao']) ? implode(',', $_POST['motivo_medicacao']) : null;
            $motivo_medicacao_outro = $_POST['motivo_medicacao_outro'] ?? null;

            $salvo = \Models\QualidadeSono::create(
                $atleta_id, $avaliacao_recuperacao, $avaliacao_fadiga, $avaliacao_sono,
                $avaliacao_dor, $horas_sono_total, $tempo_para_dormir, $uso_medicacao,
                $acordou_durante_a_noite, $local_dor, $dor_corpo, $estresse_geral,
                $estresse_umor, $periodo_premenstrual, $periodo_menstrual,
                $uso_medicacao_outro, $motivo_medicacao, $motivo_medicacao_outro
            );

            if ($salvo) {
                header('Location: /psa-cbg/views/lista_sono.php?success=1');
            } else {
                echo "Erro ao salvar.";
            }
            exit;
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
                header('Location: /psa-cbg/views/lista_sono.php?success=1');
                exit;
            } else {
                die("Erro ao excluir registro de sono.");
            }
        }
    }

    

}


