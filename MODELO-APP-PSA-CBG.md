# 🏅 Modelo de Funcionamento — App Atletas (PSA-CBG)

## 1. Visão Geral

O App Atletas é um exemplo de aplicação fullstack moderna, com frontend em **Vue 3/PrimeVue** e backend em **PHP** (PDO/MySQL), seguindo práticas de integração RESTful, automação de cadastros relacionais e documentação via Swagger.

---

## 2. Funcionamento do Frontend

### 2.1. Estrutura

- **Framework:** Vue 3 + PrimeVue
- **Componentização:** Cada tela (ex: Atletas) é um componente Vue.
- **Serviços:** Comunicação com backend via Axios, centralizada em `src/services/api.ts`.
- **Formulários:** Utilizam v-model para binding dos campos.
- **Validação:** Campos obrigatórios validados antes do envio.
- **Máscaras:** Exemplo: telefone com máscara `(99) 99999-9999`.
- **Selects dinâmicos:** Exemplo: Treinador, Grupo, Posição.

### 2.2. Fluxo de Cadastro

1. Usuário clica em “Novo Atleta” e preenche o formulário.
2. Ao salvar, o frontend envia um **payload JSON** para o endpoint `/api/atletas` via POST.
3. O payload inclui todos os campos do atleta, exceto `usuario_id` (gerado no backend).
4. O campo `treinador_id` é sempre enviado como inteiro.
5. Após sucesso, a tabela de atletas é atualizada automaticamente.

### 2.3. Exemplo de Payload Enviado

```json
{
  "nome": "Fernandes",
  "data_nascimento": "2000-02-02",
  "posicao": "Atleta",
  "email": "fernandes@email.com",
  "telefone": "(99) 99999-9999",
  "categoria": "G1",
  "acesso": "",
  "senha": "",
  "treinador_id": 2
}
```

### 2.4. Exibição

- Tabela exibe todos os campos relevantes, incluindo nome do treinador (relacionamento).
- Botões de editar/excluir por linha.
- Filtros e busca global.

---

## 3. Funcionamento do Backend

### 3.1. Estrutura

- **Framework:** PHP puro, arquitetura modular (App/Atleta, App/Usuario, etc).
- **PDO:** Para acesso seguro ao MySQL.
- **Rotas:** Baseadas em arquivos e métodos HTTP (GET, POST, PUT, DELETE).
- **Documentação:** Swagger/OpenAPI disponível em `/swagger.html`.

### 3.2. Cadastro Relacional Automático

1. **Recebe o payload do frontend** via POST `/api/atletas`.
2. **Cria automaticamente um usuário** na tabela `usuarios`:
   - Nome e email do atleta.
   - Senha padrão hash (ex: `123456`).
   - Nível: `"atleta"` (pode ser customizado).
3. **Pega o ID do usuário criado**.
4. **Cria o atleta** na tabela `atletas`, usando o `usuario_id` gerado.
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

// Cria atleta com o usuario_id gerado
$atleta = new Atleta(
    $data['nome'],
    $data['data_nascimento'],
    $data['posicao'],
    $data['email'],
    $data['telefone'],
    $data['categoria'],
    $data['acesso'],
    $data['senha'],
    $data['treinador_id'],
    $usuarioId
);
$atletaRepo->save($atleta);
```

### 3.4. Endpoints RESTful

- `GET /api/atletas` — Lista atletas
- `POST /api/atletas` — Cria atleta (e usuário)
- `PUT /api/atletas` — Atualiza atleta
- `DELETE /api/atletas/{id}` — Remove atleta

---

## 4. Padrões e Boas Práticas para Outros Apps

### 4.1. Integração Frontend-Backend

- **Alinhe nomes e tipos de campos** entre frontend e backend.
- **Valide campos obrigatórios** no frontend antes de enviar.
- **Converta IDs para inteiro** antes de enviar ao backend.
- **Use máscaras e selects dinâmicos** para campos padronizados.

### 4.2. Cadastro Relacional Automático

- **Sempre crie entidades dependentes** (ex: usuário) antes da principal (ex: atleta).
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

## 5. Template Markdown para Projetos Futuros

```markdown
# Modelo de Integração Fullstack (Vue + PHP)

## Cadastro Relacional Automático

- Receba o payload da entidade principal (ex: atleta) no backend.
- Crie a entidade dependente (ex: usuário) antes da principal.
- Use o ID gerado como chave estrangeira.
- Valide e converta tipos de dados (ex: IDs como inteiros).
- Use hash seguro para senhas.
- Documente endpoints e payloads no Swagger/OpenAPI.
- Trate erros de integridade referencial e retorne mensagens claras.
- Teste endpoints com Postman ou Swagger UI.
- Mantenha exemplos de payloads válidos para automação e testes.

## Exemplo de Fluxo

1. Recebe payload do frontend.
2. Cria usuário (nome, email, senha hash).
3. Pega o ID do usuário.
4. Cria entidade principal (ex: atleta) usando o ID.
5. Retorna sucesso ou erro detalhado.
```

---

## 6. Como Usar Este Modelo

- **Copie a estrutura** para novos módulos (ex: Técnicos, Clubes, Eventos).
- **Adapte os campos** conforme a entidade.
- **Mantenha o padrão de automação relacional** para garantir integridade e facilidade de manutenção.
- **Documente e teste sempre!** 