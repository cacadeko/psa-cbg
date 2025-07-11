<?php

namespace App\Atleta;

use App\Atleta\AtletaService;

class AtletaController {
    private AtletaService $service;
    public function __construct(AtletaService $service) {
        $this->service = $service;
    }

    public function cadastrar(array $request): void {
        $this->service->cadastrar($request);
        echo json_encode(['status' => 'ok']);
    }
} 