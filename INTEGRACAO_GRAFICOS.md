# 📊 Integração de Gráficos - Frontend com Backend

Este documento fornece instruções detalhadas para integrar os gráficos do frontend Vue.js com o backend PHP, incluindo endpoints necessários, estruturas de dados e implementação.

## 🎯 Objetivo

Criar endpoints no backend PHP que forneçam dados estruturados para os gráficos do frontend, substituindo os dados mockados por dados reais do banco de dados.

## 📋 Endpoints Necessários

### 1. Distribuição Semanal dos Tipos de Treino

#### Endpoint
```
GET /api/relatorios/distribuicao-semanal
```

#### Parâmetros
- `dataInicio` (string, formato: YYYY-MM-DD)
- `dataFim` (string, formato: YYYY-MM-DD)
- `grupo` (string, opcional) - Filtro por grupo de atletas

#### Resposta Esperada
```json
{
  "success": true,
  "data": [
    {
      "tipo": "PFE",
      "percentual": 23.51,
      "minutos": 790,
      "quantidade": 15
    },
    {
      "tipo": "PFS", 
      "percentual": 16.38,
      "minutos": 550,
      "quantidade": 12
    },
    {
      "tipo": "Técnico Misto",
      "percentual": 22.30,
      "minutos": 750,
      "quantidade": 18
    },
    {
      "tipo": "Técnico Fita",
      "percentual": 17.92,
      "minutos": 602,
      "quantidade": 14
    },
    {
      "tipo": "Físico-Técnico",
      "percentual": 17.32,
      "minutos": 582,
      "quantidade": 13
    },
    {
      "tipo": "Intervalo",
      "percentual": 2.03,
      "minutos": 68,
      "quantidade": 2
    }
  ]
}
```

#### Query SQL Exemplo
```sql
SELECT 
    tipo_treino as tipo,
    COUNT(*) as quantidade,
    SUM(duracao_minutos) as minutos,
    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM treinos WHERE data BETWEEN ? AND ?)), 2) as percentual
FROM treinos 
WHERE data BETWEEN ? AND ?
GROUP BY tipo_treino
ORDER BY percentual DESC;
```

### 2. Percepção de Esforço (PSE)

#### Endpoint
```
GET /api/relatorios/pse
```

#### Parâmetros
- `dataInicio` (string, formato: YYYY-MM-DD)
- `dataFim` (string, formato: YYYY-MM-DD)
- `tipo` (string) - PFG, PFE, TÉCNICO
- `grupo` (string, opcional)

#### Resposta Esperada
```json
{
  "success": true,
  "data": {
    "dados_diarios": [
      {
        "dia": "Seg",
        "data": "2025-01-20",
        "pfg": 3.2,
        "pfe": 5.1,
        "tecnico": 4.3
      },
      {
        "dia": "Ter", 
        "data": "2025-01-21",
        "pfg": 4.1,
        "pfe": 6.2,
        "tecnico": 5.0
      }
    ],
    "medias": {
      "pfg": 4.0,
      "pfe": 6.0,
      "tecnico": 5.0
    }
  }
}
```

#### Query SQL Exemplo
```sql
-- Dados diários
SELECT 
    DATE_FORMAT(data, '%a') as dia,
    data,
    AVG(CASE WHEN tipo = 'PFG' THEN valor END) as pfg,
    AVG(CASE WHEN tipo = 'PFE' THEN valor END) as pfe,
    AVG(CASE WHEN tipo = 'TÉCNICO' THEN valor END) as tecnico
FROM pse 
WHERE data BETWEEN ? AND ?
GROUP BY data
ORDER BY data;

-- Médias gerais
SELECT 
    AVG(CASE WHEN tipo = 'PFG' THEN valor END) as pfg,
    AVG(CASE WHEN tipo = 'PFE' THEN valor END) as pfe,
    AVG(CASE WHEN tipo = 'TÉCNICO' THEN valor END) as tecnico
FROM pse 
WHERE data BETWEEN ? AND ?;
```

### 3. Carga Agudo-Crônica

#### Endpoint
```
GET /api/relatorios/carga-agudo-cronica
```

#### Parâmetros
- `dataInicio` (string, formato: YYYY-MM-DD)
- `dataFim` (string, formato: YYYY-MM-DD)
- `atleta_id` (integer, opcional)

#### Resposta Esperada
```json
{
  "success": true,
  "data": [
    {
      "data": "2025-01-20",
      "carga_cronica": 200,
      "carga_aguda": 150,
      "ratio": 0.75,
      "status": "normal"
    },
    {
      "data": "2025-01-21", 
      "carga_cronica": 300,
      "carga_aguda": 200,
      "ratio": 0.67,
      "status": "normal"
    }
  ]
}
```

#### Query SQL Exemplo
```sql
SELECT 
    data,
    carga_cronica,
    carga_aguda,
    ROUND(carga_aguda / carga_cronica, 2) as ratio,
    CASE 
        WHEN (carga_aguda / carga_cronica) > 1.5 THEN 'alto_risco'
        WHEN (carga_aguda / carga_cronica) > 1.0 THEN 'moderado'
        ELSE 'normal'
    END as status
FROM carga_treino 
WHERE data BETWEEN ? AND ?
ORDER BY data;
```

### 4. Percepção de Fadiga

#### Endpoint
```
GET /api/relatorios/fadiga
```

#### Parâmetros
- `dataInicio` (string, formato: YYYY-MM-DD)
- `dataFim` (string, formato: YYYY-MM-DD)
- `atleta_id` (integer, opcional)

#### Resposta Esperada
```json
{
  "success": true,
  "data": [
    {
      "dia": "Seg",
      "data": "2025-01-20",
      "nivel": 3.2,
      "quantidade_avaliacoes": 15
    },
    {
      "dia": "Ter",
      "data": "2025-01-21", 
      "nivel": 5.1,
      "quantidade_avaliacoes": 18
    }
  ]
}
```

#### Query SQL Exemplo
```sql
SELECT 
    DATE_FORMAT(data, '%a') as dia,
    data,
    AVG(nivel_fadiga) as nivel,
    COUNT(*) as quantidade_avaliacoes
FROM pfe 
WHERE data BETWEEN ? AND ?
GROUP BY data
ORDER BY data;
```

### 5. Carga Semanal

#### Endpoint
```
GET /api/relatorios/carga-semanal
```

#### Parâmetros
- `dataInicio` (string, formato: YYYY-MM-DD)
- `dataFim` (string, formato: YYYY-MM-DD)
- `atleta_id` (integer, opcional)

#### Resposta Esperada
```json
{
  "success": true,
  "data": [
    {
      "semana": "Semana 1",
      "data_inicio": "2025-01-13",
      "data_fim": "2025-01-19",
      "carga": 250,
      "treinos": 5
    },
    {
      "semana": "Semana 2",
      "data_inicio": "2025-01-20", 
      "data_fim": "2025-01-26",
      "carga": 300,
      "treinos": 6
    }
  ]
}
```

#### Query SQL Exemplo
```sql
SELECT 
    CONCAT('Semana ', WEEK(data, 1)) as semana,
    MIN(data) as data_inicio,
    MAX(data) as data_fim,
    SUM(carga_treino) as carga,
    COUNT(*) as treinos
FROM treinos 
WHERE data BETWEEN ? AND ?
GROUP BY YEARWEEK(data, 1)
ORDER BY data_inicio;
```

## 🏗️ Implementação no Backend

### 1. Criar Controller de Relatórios

```php
<?php
// backend/App/Relatorios/RelatoriosController.php

namespace App\Relatorios;

use App\Infrastructure\Database\Connection;
use App\Domain\Relatorios\RelatoriosService;

class RelatoriosController {
    private $relatoriosService;
    
    public function __construct() {
        $this->relatoriosService = new RelatoriosService();
    }
    
    public function getDistribuicaoSemanal($request) {
        try {
            $dataInicio = $request['dataInicio'] ?? date('Y-m-d', strtotime('-7 days'));
            $dataFim = $request['dataFim'] ?? date('Y-m-d');
            $grupo = $request['grupo'] ?? null;
            
            $dados = $this->relatoriosService->getDistribuicaoSemanal($dataInicio, $dataFim, $grupo);
            
            return [
                'success' => true,
                'data' => $dados
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    public function getPSE($request) {
        try {
            $dataInicio = $request['dataInicio'] ?? date('Y-m-d', strtotime('-7 days'));
            $dataFim = $request['dataFim'] ?? date('Y-m-d');
            $tipo = $request['tipo'] ?? null;
            $grupo = $request['grupo'] ?? null;
            
            $dados = $this->relatoriosService->getPSE($dataInicio, $dataFim, $tipo, $grupo);
            
            return [
                'success' => true,
                'data' => $dados
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    // Implementar outros métodos...
}
```

### 2. Criar Service de Relatórios

```php
<?php
// backend/App/Domain/Relatorios/RelatoriosService.php

namespace App\Domain\Relatorios;

use App\Infrastructure\Database\Connection;

class RelatoriosService {
    private $connection;
    
    public function __construct() {
        $this->connection = Connection::getInstance();
    }
    
    public function getDistribuicaoSemanal($dataInicio, $dataFim, $grupo = null) {
        $sql = "SELECT 
                    tipo_treino as tipo,
                    COUNT(*) as quantidade,
                    SUM(duracao_minutos) as minutos,
                    ROUND((COUNT(*) * 100.0 / (
                        SELECT COUNT(*) FROM treinos 
                        WHERE data BETWEEN ? AND ?
                    )), 2) as percentual
                FROM treinos 
                WHERE data BETWEEN ? AND ?";
        
        $params = [$dataInicio, $dataFim, $dataInicio, $dataFim];
        
        if ($grupo) {
            $sql .= " AND grupo = ?";
            $params[] = $grupo;
        }
        
        $sql .= " GROUP BY tipo_treino ORDER BY percentual DESC";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getPSE($dataInicio, $dataFim, $tipo = null, $grupo = null) {
        // Implementar lógica para PSE
        // Retornar dados diários e médias
    }
    
    // Implementar outros métodos...
}
```

### 3. Criar Router de Relatórios

```php
<?php
// backend/App/Relatorios/RelatoriosRouter.php

require_once __DIR__ . '/RelatoriosController.php';

$controller = new \App\Relatorios\RelatoriosController();

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/relatorios/', '', $path);

switch ($path) {
    case 'distribuicao-semanal':
        if ($method === 'GET') {
            $response = $controller->getDistribuicaoSemanal($_GET);
        }
        break;
        
    case 'pse':
        if ($method === 'GET') {
            $response = $controller->getPSE($_GET);
        }
        break;
        
    case 'carga-agudo-cronica':
        if ($method === 'GET') {
            $response = $controller->getCargaAgudoCronica($_GET);
        }
        break;
        
    case 'fadiga':
        if ($method === 'GET') {
            $response = $controller->getFadiga($_GET);
        }
        break;
        
    case 'carga-semanal':
        if ($method === 'GET') {
            $response = $controller->getCargaSemanal($_GET);
        }
        break;
        
    default:
        $response = ['success' => false, 'error' => 'Endpoint não encontrado'];
}

header('Content-Type: application/json');
echo json_encode($response);
```

### 4. Registrar Router no index.php

```php
<?php
// backend/public/index.php

// ... código existente ...

require __DIR__ . '/../App/Relatorios/RelatoriosRouter.php';

// ... resto do código ...
```

## 🔄 Atualização do Frontend

### 1. Atualizar RelatoriosService

```typescript
// frontend/frontend/src/services/relatoriosService.ts

// Substituir métodos mockados por chamadas reais da API
async getDistribuicaoSemanalAPI(dataInicio: string, dataFim: string, grupo?: string): Promise<DadosDistribuicaoSemanal[]> {
  try {
    const params = new URLSearchParams({
      dataInicio,
      dataFim,
      ...(grupo && { grupo })
    });
    
    const response = await api.get(`/relatorios/distribuicao-semanal?${params}`);
    return response.data.data;
  } catch (error) {
    console.error('Erro ao buscar dados de distribuição semanal:', error);
    return this.getDistribuicaoSemanal(); // Fallback para dados mockados
  }
}

async getDadosPSEAPI(dataInicio: string, dataFim: string, tipo?: string, grupo?: string): Promise<DadosPSE[]> {
  try {
    const params = new URLSearchParams({
      dataInicio,
      dataFim,
      ...(tipo && { tipo }),
      ...(grupo && { grupo })
    });
    
    const response = await api.get(`/relatorios/pse?${params}`);
    return response.data.data.dados_diarios;
  } catch (error) {
    console.error('Erro ao buscar dados PSE:', error);
    return this.getDadosPSE(dataInicio, dataFim);
  }
}

// Implementar outros métodos...
```

### 2. Atualizar RelatoriosView

```typescript
// frontend/frontend/src/views/RelatoriosView.vue

// Substituir chamadas mockadas por chamadas reais
async function carregarDados() {
  try {
    const dataInicio = filtros.value.dataInicio.toISOString().split('T')[0];
    const dataFim = filtros.value.dataFim.toISOString().split('T')[0];
    
    // Usar métodos da API real
    dadosDistribuicao.value = await relatoriosService.getDistribuicaoSemanalAPI(dataInicio, dataFim, filtros.value.tag);
    dadosPSE.value = await relatoriosService.getDadosPSEAPI(dataInicio, dataFim, filtros.value.tipo1, filtros.value.tag);
    // ... outros dados
    
  } catch (error) {
    console.error('Erro ao carregar dados:', error);
  }
}
```

## 🗄️ Estrutura do Banco de Dados

### Tabelas Necessárias

```sql
-- Tabela de treinos
CREATE TABLE treinos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    atleta_id INT,
    data DATE,
    tipo_treino ENUM('PFE', 'PFS', 'Técnico Misto', 'Técnico Fita', 'Físico-Técnico', 'Intervalo'),
    duracao_minutos INT,
    carga_treino DECIMAL(10,2),
    grupo VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de PSE
CREATE TABLE pse (
    id INT PRIMARY KEY AUTO_INCREMENT,
    atleta_id INT,
    data DATE,
    tipo ENUM('PFG', 'PFE', 'TÉCNICO'),
    valor DECIMAL(3,1),
    grupo VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de PFE (fadiga)
CREATE TABLE pfe (
    id INT PRIMARY KEY AUTO_INCREMENT,
    atleta_id INT,
    data DATE,
    nivel_fadiga DECIMAL(3,1),
    grupo VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de carga agudo-crônica
CREATE TABLE carga_treino (
    id INT PRIMARY KEY AUTO_INCREMENT,
    atleta_id INT,
    data DATE,
    carga_aguda DECIMAL(10,2),
    carga_cronica DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🧪 Testes

### 1. Testar Endpoints

```bash
# Testar distribuição semanal
curl "http://localhost:8000/api/relatorios/distribuicao-semanal?dataInicio=2025-01-20&dataFim=2025-01-26"

# Testar PSE
curl "http://localhost:8000/api/relatorios/pse?dataInicio=2025-01-20&dataFim=2025-01-26&tipo=PFE"

# Testar carga agudo-crônica
curl "http://localhost:8000/api/relatorios/carga-agudo-cronica?dataInicio=2025-01-20&dataFim=2025-01-26"
```

### 2. Verificar Frontend

1. Acesse http://localhost:5173/relatorios
2. Configure filtros de data
3. Verifique se os gráficos carregam dados reais
4. Teste diferentes combinações de filtros

## 🔧 Configurações Adicionais

### 1. CORS (se necessário)

```php
// backend/public/index.php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### 2. Cache de Dados

```php
// Implementar cache para relatórios pesados
public function getDistribuicaoSemanal($dataInicio, $dataFim, $grupo = null) {
    $cacheKey = "distribuicao_{$dataInicio}_{$dataFim}_{$grupo}";
    
    if ($cached = $this->cache->get($cacheKey)) {
        return $cached;
    }
    
    $dados = $this->calcularDistribuicaoSemanal($dataInicio, $dataFim, $grupo);
    $this->cache->set($cacheKey, $dados, 3600); // Cache por 1 hora
    
    return $dados;
}
```

### 3. Paginação para Dados Grandes

```php
// Para relatórios com muitos dados
public function getPSE($dataInicio, $dataFim, $tipo = null, $grupo = null, $page = 1, $limit = 100) {
    $offset = ($page - 1) * $limit;
    
    $sql = "SELECT ... LIMIT ? OFFSET ?";
    // Implementar paginação
}
```

## 📝 Notas Importantes

1. **Performance**: Relatórios podem ser pesados, considere implementar cache
2. **Segurança**: Valide todos os parâmetros de entrada
3. **Erro Handling**: Implemente tratamento robusto de erros
4. **Logs**: Registre consultas lentas para otimização
5. **Backup**: Mantenha dados mockados como fallback
6. **Testes**: Crie testes unitários para os serviços de relatórios

## 🚀 Próximos Passos

1. Implementar endpoints no backend
2. Testar com dados reais
3. Otimizar queries se necessário
4. Implementar cache
5. Adicionar mais filtros conforme necessário
6. Criar testes automatizados

---

**Este documento deve ser atualizado conforme a implementação avança e novos requisitos surgem.** 