<?php
namespace App\PreTreino;

class PreTreinoService {
    private PreTreinoRepositoryInterface $repo;
    
    public function __construct(PreTreinoRepositoryInterface $repo) {
        $this->repo = $repo;
    }

    public function cadastrar(array $data): int {
        error_log('PreTreinoService: Iniciando cadastro');
        
        // Validações básicas
        if (empty($data['atleta_id']) || empty($data['data_avaliacao'])) {
            throw new \Exception('Atleta e data de avaliação são obrigatórios');
        }
        
        // Validação de valores numéricos
        $camposNumericos = ['tqr_recuperacao', 'fadiga_bem_estar', 'nivel_fadiga', 'qualidade_sono', 
                           'dor_muscular_geral', 'escala_dor_visual', 'nivel_estresse', 'humor'];
        
        foreach ($camposNumericos as $campo) {
            if (!isset($data[$campo]) || !is_numeric($data[$campo])) {
                $data[$campo] = $this->getValorPadrao($campo);
            }
        }
        
        // Validação de campos enum
        $data['horas_sono'] = $this->validarHorasSono($data['horas_sono'] ?? 'Entre 6 e 7 horas');
        $data['tempo_dormir'] = $this->validarTempoDormir($data['tempo_dormir'] ?? 'Menos que 30 min');
        $data['vezes_acordou_noite'] = $this->validarVezesAcordou($data['vezes_acordou_noite'] ?? '1 vez');
        
        // Conversão de booleanos
        $data['periodo_pre_menstrual'] = (bool)($data['periodo_pre_menstrual'] ?? false);
        $data['periodo_menstrual'] = (bool)($data['periodo_menstrual'] ?? false);
        
        error_log('PreTreinoService: Dados validados, salvando...');
        $id = $this->repo->save($data);
        error_log('PreTreinoService: Pré-treino salvo, id=' . $id);
        return $id;
    }

    public function listar(): array {
        return $this->repo->listarTodos();
    }

    public function listarPorAtleta(int $atletaId): array {
        // Implementar se necessário
        $todos = $this->repo->listarTodos();
        return array_filter($todos, function($item) use ($atletaId) {
            return $item['atleta_id'] == $atletaId;
        });
    }

    public function editar(array $data): bool {
        if (empty($data['id'])) {
            throw new \Exception('ID é obrigatório para edição');
        }
        
        // Aplicar as mesmas validações do cadastro
        $camposNumericos = ['tqr_recuperacao', 'fadiga_bem_estar', 'nivel_fadiga', 'qualidade_sono', 
                           'dor_muscular_geral', 'escala_dor_visual', 'nivel_estresse', 'humor'];
        
        foreach ($camposNumericos as $campo) {
            if (!isset($data[$campo]) || !is_numeric($data[$campo])) {
                $data[$campo] = $this->getValorPadrao($campo);
            }
        }
        
        $data['horas_sono'] = $this->validarHorasSono($data['horas_sono'] ?? 'Entre 6 e 7 horas');
        $data['tempo_dormir'] = $this->validarTempoDormir($data['tempo_dormir'] ?? 'Menos que 30 min');
        $data['vezes_acordou_noite'] = $this->validarVezesAcordou($data['vezes_acordou_noite'] ?? '1 vez');
        
        $data['periodo_pre_menstrual'] = (bool)($data['periodo_pre_menstrual'] ?? false);
        $data['periodo_menstrual'] = (bool)($data['periodo_menstrual'] ?? false);
        
        return $this->repo->editar($data);
    }

    public function excluir(int $id): bool {
        return $this->repo->excluir($id);
    }

    private function getValorPadrao(string $campo): int {
        $padroes = [
            'tqr_recuperacao' => 10,
            'fadiga_bem_estar' => 3,
            'nivel_fadiga' => 5,
            'qualidade_sono' => 3,
            'dor_muscular_geral' => 3,
            'escala_dor_visual' => 5,
            'nivel_estresse' => 3,
            'humor' => 3
        ];
        
        return $padroes[$campo] ?? 0;
    }

    private function validarHorasSono(string $valor): string {
        $valoresValidos = ['Menos que 6 horas', 'Entre 6 e 7 horas', '8 horas ou mais'];
        return in_array($valor, $valoresValidos) ? $valor : 'Entre 6 e 7 horas';
    }

    private function validarTempoDormir(string $valor): string {
        $valoresValidos = ['Menos que 30 min', 'Entre 30 min e 1h', 'Mais que 1h'];
        return in_array($valor, $valoresValidos) ? $valor : 'Menos que 30 min';
    }

    private function validarVezesAcordou(string $valor): string {
        $valoresValidos = ['1 vez', '2 a 4 vezes', '4 vezes ou mais'];
        return in_array($valor, $valoresValidos) ? $valor : '1 vez';
    }
} 