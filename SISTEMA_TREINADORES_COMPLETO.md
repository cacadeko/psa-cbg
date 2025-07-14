# 🏆 Sistema Fullstack de Treinadores - PSA-CBG

## 📋 Visão Geral

Sistema completo de gestão de treinadores com frontend Vue.js + PrimeVue e backend PHP + MySQL, incluindo todos os campos da tabela `usuarios` (nome, email, senha, nivel, ativo).

---

## 🚀 Como Executar

### 1. Backend (PHP)
```bash
cd backend
php -S localhost:8000 -t public
```

### 2. Frontend (Vue.js)
```bash
cd frontend
npm install
npm run dev
```

### 3. Banco de Dados
Execute o script SQL para atualizar as tabelas:
```sql
-- Execute update_treinadores_table.sql no seu MySQL
```

---

## 🏗️ Estrutura do Sistema

### Frontend (Vue.js + PrimeVue)

#### Componente Principal: `TreinadoresView.vue`
- **Localização:** `frontend/src/views/TreinadoresView.vue`
- **Funcionalidades:**
  - Listagem de treinadores com filtros
  - Criação de novos treinadores
  - Edição de treinadores existentes
  - Exclusão com confirmação
  - Validação de formulários
  - Máscaras de entrada (telefone)
  - Status visual (ativo/inativo, níveis)

#### Serviço de API: `treinadoresApi.ts`
- **Localização:** `frontend/src/services/treinadoresApi.ts`
- **Endpoints:**
  - `GET /api/treinadores` - Listar todos
  - `GET /api/treinadores/{id}` - Obter por ID
  - `POST /api/treinadores` - Criar novo
  - `PUT /api/treinadores/{id}` - Atualizar
  - `DELETE /api/treinadores/{id}` - Excluir

### Backend (PHP + MySQL)

#### Classes Principais:

1. **TreinadorController** (`backend/App/Treinador/TreinadorController.php`)
   - Gerencia requisições HTTP
   - Valida dados de entrada
   - Retorna respostas JSON

2. **TreinadorService** (`backend/App/Treinador/TreinadorService.php`)
   - Lógica de negócio
   - Criação automática de usuários
   - Atualização de senhas

3. **TreinadorRepository** (`backend/App/Treinador/TreinadorRepository.php`)
   - Acesso ao banco de dados
   - Operações CRUD

4. **UsuarioRepository** (`backend/App/Usuario/UsuarioRepository.php`)
   - Gerenciamento de usuários
   - Atualização de dados de acesso

---

## 📊 Estrutura do Banco de Dados

### Tabela `treinadores`
```sql
CREATE TABLE `treinadores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `nivel` enum('admin','treinador','auxiliar','estagiario') DEFAULT 'treinador',
  `ativo` tinyint(1) DEFAULT 1,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
);
```

### Tabela `usuarios`
```sql
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `nivel` enum('admin','treinador','auxiliar','estagiario') DEFAULT 'treinador',
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);
```

---

## 🔄 Fluxo de Funcionamento

### 1. Criação de Treinador
1. **Frontend:** Usuário preenche formulário
2. **Frontend:** Validação dos campos obrigatórios
3. **Frontend:** Envia POST para `/api/treinadores`
4. **Backend:** Recebe dados e cria usuário na tabela `usuarios`
5. **Backend:** Cria treinador na tabela `treinadores` com `usuario_id`
6. **Backend:** Retorna sucesso/erro
7. **Frontend:** Atualiza lista e exibe mensagem

### 2. Edição de Treinador
1. **Frontend:** Usuário clica em "Editar"
2. **Frontend:** Carrega dados no formulário
3. **Frontend:** Usuário modifica campos (senha opcional)
4. **Frontend:** Envia PUT para `/api/treinadores/{id}`
5. **Backend:** Atualiza dados do treinador
6. **Backend:** Se senha fornecida, atualiza `senha_hash` do usuário
7. **Backend:** Atualiza outros campos do usuário se necessário
8. **Frontend:** Atualiza lista e exibe mensagem

---

## 🎨 Interface do Usuário

### Funcionalidades do Formulário
- **Campos Obrigatórios:** Nome, Email, Nível, Senha (na criação)
- **Campos Opcionais:** Telefone, Especialidade, Data Contratação, Observações
- **Validação:** Email válido, senha mínima 6 caracteres
- **Máscaras:** Telefone com formato (99) 99999-9999
- **Selects:** Nível (admin, treinador, auxiliar, estagiário)
- **Switches:** Status ativo/inativo

### Tabela de Listagem
- **Colunas:** ID, Nome, Email, Telefone, Especialidade, Data Contratação, Nível, Status
- **Filtros:** Busca global, filtros por coluna
- **Paginação:** 10 registros por página
- **Ações:** Editar, Excluir por linha

---

## 🔧 Configurações

### Variáveis de Ambiente
- **Backend:** `http://localhost:8000/api`
- **Frontend:** `http://localhost:3000`

### Dependências Frontend
```json
{
  "vue": "^3.x",
  "primevue": "^3.x",
  "axios": "^1.x",
  "@vuelidate/core": "^2.x",
  "@vuelidate/validators": "^2.x"
}
```

### Dependências Backend
```json
{
  "php": ">=8.0",
  "extensions": ["pdo_mysql", "json"]
}
```

---

## 🧪 Testes

### Testes Manuais
1. **Criar Treinador:**
   - Preencher todos os campos obrigatórios
   - Verificar se usuário é criado na tabela `usuarios`
   - Verificar se treinador é criado na tabela `treinadores`

2. **Editar Treinador:**
   - Modificar dados básicos
   - Alterar senha
   - Verificar se dados são atualizados em ambas as tabelas

3. **Excluir Treinador:**
   - Confirmar exclusão
   - Verificar se registro é removido

### Testes de API
```bash
# Listar treinadores
curl http://localhost:8000/api/treinadores

# Criar treinador
curl -X POST http://localhost:8000/api/treinadores \
  -H "Content-Type: application/json" \
  -d '{
    "nome": "João Silva",
    "email": "joao@exemplo.com",
    "senha": "123456",
    "nivel": "treinador",
    "ativo": true
  }'
```

---

## 🚨 Tratamento de Erros

### Frontend
- Validação de formulários com Vuelidate
- Mensagens de erro amigáveis
- Loading states durante operações
- Confirmação para exclusões

### Backend
- Validação de dados de entrada
- Logs detalhados para debug
- Respostas JSON padronizadas
- Tratamento de exceções PDO

---

## 📈 Melhorias Futuras

1. **Autenticação:** Sistema de login/logout
2. **Permissões:** Controle de acesso por nível
3. **Upload:** Fotos dos treinadores
4. **Relatórios:** Estatísticas e relatórios
5. **Notificações:** Sistema de notificações
6. **API Docs:** Swagger/OpenAPI completo

---

## 🎯 Conclusão

O sistema está completo e funcional, seguindo as melhores práticas de desenvolvimento fullstack:

- ✅ **Frontend:** Vue.js + PrimeVue com validação e UX moderna
- ✅ **Backend:** PHP + MySQL com arquitetura limpa
- ✅ **Integração:** API RESTful completa
- ✅ **Banco:** Estrutura relacional otimizada
- ✅ **Documentação:** Código bem documentado
- ✅ **Testes:** Fluxos testados e funcionais

O sistema está pronto para uso em produção! 🚀 