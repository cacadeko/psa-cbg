<?php
namespace Controllers;
require_once __DIR__ . '/../models/PsaTreino.php';
use Models\PsaTreino;

class PSATreinoController
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
        PsaTreino::create($dados);
        header('Location: /psa-cbg/views/lista_pse.php?success=1');
        exit;
    }

    public function listarTodos(?string $filtro_data = null): array
    {
        return \Models\PsaTreino::listarTodos($filtro_data);
    }
}
