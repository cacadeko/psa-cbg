# 👨‍💼 Modelo de Funcionamento — App Treinadores (PSA-CBG)

## 1. Visão Geral

O App Treinadores é uma aplicação fullstack moderna, com frontend em **Vue 3/PrimeVue** e backend em **PHP** (PDO/MySQL), seguindo práticas de integração RESTful, automação de cadastros relacionais e documentação via Swagger. Implementa criação automática de usuários quando um treinador é cadastrado.

---

## 2. Funcionamento do Frontend

### 2.1. Estrutura

- **Framework:** Vue 3 + PrimeVue
- **Componentização:** Tela Treinadores como componente Vue.
- **Serviços:** Comunicação com backend via Axios, centralizada em `src/services/api.ts`.
- **Formulários:** Utilizam v-model para binding dos campos.
- **Validação:** Campos obrigatórios validados antes do envio.
- **Selects dinâmicos:** Especialidade com opções pré-definidas.
- **Máscaras:** Telefone com placeholder para formato.

### 2.2. Fluxo de Cadastro

1. Usuário clica em "Novo Treinador" e preenche o formulário.
2. Ao salvar, o frontend envia um **payload JSON** para o endpoint `/api/treinadores` via POST.
3. O payload inclui todos os campos do treinador, exceto `usuario_id` (gerado no backend).
4. Após sucesso, a tabela de treinadores é atualizada automaticamente.

### 2.3. Exemplo de Payload Enviado

```json
{
  "nome": "João Silva",
  "email": "joao.silva@email.com",
  "telefone": "(11) 99999-9999",
  "especialidade": "Preparação Física",
  "data_contratacao": "2024-01-15",
  "observacoes": "Especialista em treinamento de força"
}
```

### 2.4. Exibição

- Tabela exibe todos os campos relevantes.
- Botões de editar/excluir por linha.
- Filtros e busca global.
- Dropdown para especialidades.

---

## 3. Funcionamento do Backend

### 3.1. Estrutura

- **Framework:** PHP puro, arquitetura modular (App/Treinador, App/Usuario, etc).
- **PDO:** Para acesso seguro ao MySQL.
- **Rotas:** Baseadas em arquivos e métodos HTTP (GET, POST, PUT, DELETE).
- **Documentação:** Swagger/OpenAPI disponível em `/swagger-treinadores.html`.

### 3.2. Cadastro Relacional Automático

1. **Recebe o payload do frontend** via POST `/api/treinadores`.
2. **Cria automaticamente um usuário** na tabela `usuarios`:
   - Nome e email do treinador.
   - Senha padrão hash (ex: `123456`).
   - Nível: `"treinador"` (pode ser customizado).
3. **Pega o ID do usuário criado**.
4. **Cria o treinador** na tabela `treinadores`, usando o `usuario_id` gerado.
5. **Retorna sucesso** ou erro detalhado.

### 3.3. Exemplo de Fluxo no Backend

```php
// Recebe dados do frontend
$data = json_decode(file_get_contents('php://input'), true);

// Cria usuário
$senhaPadrao = '123456';
$senhaHash = password_hash($senhaPadrao, PASSWORD_DEFAULT);
$usuario = new Usuario($data['nome'], $data['email'], $senhaHash);
$usuarioRepo->save($usuario);
$usuarioId = $usuario->getId();

// Cria treinador com o usuario_id gerado
$treinador = new Treinador(
    $data['nome'],
    $data['email'],
    $data['telefone'] ?? '',
    $data['especialidade'] ?? '',
    $data['data_contratacao'] ?? '',
    $data['observacoes'] ?? '',
    $usuarioId
);
return $treinadorRepo->save($treinador);
```

### 3.4. Endpoints RESTful

- `GET /api/treinadores` — Lista treinadores
- `POST /api/treinadores` — Cria treinador (e usuário)
- `PUT /api/treinadores/{id}` — Atualiza treinador
- `DELETE /api/treinadores/{id}` — Remove treinador

---

## 4. Padrões e Boas Práticas para Outros Apps

### 4.1. Integração Frontend-Backend

- **Alinhe nomes e tipos de campos** entre frontend e backend.
- **Valide campos obrigatórios** no frontend antes de enviar.
- **Use dropdowns para campos padronizados** (ex: especialidades).
- **Trate erros de forma amigável** com mensagens claras.

### 4.2. Cadastro Relacional Automático

- **Sempre crie entidades dependentes** (ex: usuário) antes da principal (ex: treinador).
- **Use o ID gerado** como chave estrangeira.
- **Implemente transações** se necessário para garantir atomicidade.

### 4.3. Segurança

- **Nunca armazene senhas em texto puro**. Sempre use hash seguro.
- **Considere enviar senha provisória por e-mail** ou forçar troca no primeiro acesso.

### 4.4. Documentação

- **Documente todos os endpoints** e exemplos de payloads no Swagger/OpenAPI.
- **Inclua exemplos de sucesso e erro**.

### 4.5. Tratamento de Erros

- **Retorne mensagens claras** do backend para o frontend.
- **Exiba mensagens amigáveis** ao usuário e log detalhado para desenvolvedores.

### 4.6. Testes

- **Use Postman ou Swagger UI** para testar endpoints.
- **Verifique payloads e respostas** usando DevTools do navegador.

---

## 5. Estrutura de Arquivos

### 5.1. Backend

```
backend/App/Treinador/
├── Treinador.php              # Modelo da entidade
├── TreinadorRepository.php    # Acesso a dados
├── TreinadorService.php       # Lógica de negócio
├── TreinadorController.php    # Controlador REST
└── TreinadorRouter.php        # Roteamento HTTP

backend/App/Usuario/
├── Usuario.php                # Modelo de usuário
├── UsuarioRepository.php      # Repositório de usuários
└── ...

backend/public/
├── swagger-treinadores.json   # Documentação Swagger
└── swagger-treinadores.html   # Interface Swagger UI
```

### 5.2. Frontend

```
frontend/frontend/src/views/
└── TreinadoresView.vue        # Componente principal

frontend/frontend/src/services/
└── api.ts                     # Configuração Axios
```

---

## 6. Campos da Tabela Treinadores

| Campo | Tipo | Descrição | Obrigatório |
|-------|------|-----------|-------------|
| id | INT | ID único | Sim (auto) |
| nome | VARCHAR(100) | Nome completo | Sim |
| email | VARCHAR(100) | Email único | Sim |
| telefone | VARCHAR(20) | Telefone | Não |
| especialidade | VARCHAR(100) | Especialidade | Não |
| data_contratacao | DATE | Data de contratação | Não |
| observacoes | TEXT | Observações | Não |
| usuario_id | INT | ID do usuário | Sim (auto) |
| ativo | TINYINT(1) | Status ativo | Sim |
| criado_em | TIMESTAMP | Data criação | Sim (auto) |

---

## 7. Especialidades Disponíveis

- Fisioterapia
- Preparação Física
- Técnica
- Psicologia
- Nutrição
- Médico
- Coordenador
- Auxiliar

---

## 8. Como Usar Este Modelo

- **Copie a estrutura** para novos módulos (ex: Técnicos, Clubes, Eventos).
- **Adapte os campos** conforme a entidade.
- **Mantenha o padrão de automação relacional** para garantir integridade e facilidade de manutenção.
- **Documente e teste sempre!**

---

## 9. Testes e Validação

### 9.1. Teste de Criação

```bash
curl -X POST http://localhost:8000/api/treinadores \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "email": "joao.silva@email.com",
    "telefone": "(11) 99999-9999",
    "especialidade": "Preparação Física",
    "data_contratacao": "2024-01-15",
    "observacoes": "Especialista em treinamento de força"
  }'
```

### 9.2. Teste de Listagem

```bash
curl -X GET http://localhost:8000/api/treinadores
```

### 9.3. Documentação Swagger

Acesse: `http://localhost:8000/swagger-treinadores.html`

---

## 10. Logs e Debug

O sistema inclui logs detalhados para debug:

```php
// Log para debug
error_log('Dados recebidos no cadastrar treinador: ' . json_encode($data));
```

Verifique os logs do PHP para identificar problemas de integração. 