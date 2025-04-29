<?php
namespace Controllers;

use Models\PSE;
use Config\Database;
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
//     exit; // Interrompe o script para inspecionar os dados recebidos
// }

class PSEController {
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $atleta_id = $_POST['atleta_id'] ?? null;
            $descricao = $_POST['descricao'] ?? null;
            $nota_pse = $_POST['nota_pse'] ?? null;
            $data_hoje = date('Y-m-d');

            if (!$atleta_id || !$descricao || !$nota_pse) {
                die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
            }

            $conn = Database::getConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) FROM pse WHERE atleta_id = ? AND DATE(created_at) = ?");
            $stmt->execute([$atleta_id, $data_hoje]);
            $existeCadastro = $stmt->fetchColumn();

            if ($existeCadastro > 0) {
                print("Erro: Apenas um cadastro de esforço permitido por dia.");
                header('Location: /psa-cbg/views/pse_form.php?success=3');
                exit;
            }

            if (PSE::create($atleta_id, $descricao, $nota_pse)) {
                header('Location: /psa-cbg/index.php?success=1');
                exit;
            } else {
                die("Erro ao registrar PSE.");
            }
        }
    }

    public function listarTodos($data = null) {
        return PSE::listarTodos($data);
    }

    public static function listarPorTreinador($treinadorId)
    {
        $conn = \Config\Database::getConnection();
        $stmt = $conn->prepare("
            SELECT pse.*, a.nome AS atleta_nome
            FROM pse pse
            INNER JOIN atletas a ON pse.atleta_id = a.id
            WHERE a.treinador_id = ?
            ORDER BY pse.created_at DESC
        ");
        $stmt->execute([$treinadorId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    
    public function excluir() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $id = $_GET['id'];
            if (PSE::excluir($id)) {
                header('Location: /psa-cbg/views/lista_pse.php?success=1');
                exit;
            } else {
                die("Erro ao excluir registro de PSE.");
            }
        }
    }
}
?>
