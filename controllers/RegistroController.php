<?php
namespace Controllers;

use Models\Registro;
use Config\Database;
use PDO;

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     echo "<pre>";
//     print_r($_POST);
//     echo "</pre>";
//     exit; // Interrompe o script para inspecionar os dados recebidos
// }

    class RegistroController {

        public function listarTodos($data = null)
        {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $nivel = $_SESSION['nivel'] ?? 'aluno';
            $treinadorId = $_SESSION['usuario_id'] ?? 0;

            if ($nivel === 'admin') {
                return \Models\Registro::listarTodos($data);
            }

            if ($nivel === 'treinador') {
                return \Models\Registro::listarPorTreinador($treinadorId);
            }

            return []; // outros usuários não acessam
        }


        public static function alterarStatusRegistro($status) {
            $conn = Database::getConnection();
            $stmt = $conn->prepare("UPDATE configuracoes SET registro_liberado = ? WHERE id = 1");
            return $stmt->execute([$status]);
            header('Location: /psa-cbg/index.php?success=1&status=1');
            exit;

        }
        
        public static function obterStatusRegistro() {
            $conn = Database::getConnection();
            $stmt = $conn->query("SELECT registro_liberado FROM configuracoes WHERE id = 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['registro_liberado'] : 0;
        }
        
        public static function toggleStatus($status) {
            if (in_array($status, [0, 1])) {
                return self::alterarStatusRegistro($status);
            }
            return false;
        }
    }
?>