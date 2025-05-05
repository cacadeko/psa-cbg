<?php
namespace Controllers;
require_once __DIR__ . '/../models/PFE.php';
use Models\PFE;

class PFEController
{
    public function store(): void
    {
        session_start();
        $dados = [
            ':atleta_id'    => $_POST['atleta_id'],
            ':grupo'        => $_POST['grupo'],
            ':nota_pse'     => $_POST['nota_pse'],
            ':tempo_treino' => $_POST['tempo_treino'],
            ':turno'        => $_POST['turno']
        ];
        PFE::create($dados);
        header('Location: /psa-cbg/views/lista_pfe.php?success=1');
        exit;
    }

    public function listarTodos(?string $filtro_data = null): array
    {
        return \Models\PFE::listarTodos($filtro_data);
    }
}
