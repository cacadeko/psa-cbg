# PSA-CBG - Sistema de Gestão de Atletas

Sistema completo de gestão de atletas da Confederação Brasileira de Ginástica (CBG), incluindo backend API em PHP e frontend dashboard em Vue 3.

## 🚀 Funcionalidades

### Backend (PHP)
- **API RESTful** com arquitetura limpa
- **Autenticação JWT** segura
- **CRUD completo** para todas as entidades
- **Documentação Swagger** automática
- **Validação de dados** robusta
- **Tratamento de erros** padronizado

### Frontend (Vue 3 + TypeScript)
- **Dashboard moderno** com PrimeVue
- **Autenticação** com token JWT
- **CRUD completo** para todas as entidades
- **Filtros e paginação** avançados
- **Modais** para criação/edição
- **Confirmação** para exclusões
- **Relatórios e gráficos** interativos
- **Interface responsiva** e intuitiva

### Entidades Gerenciadas
- **Atletas**: Cadastro completo de atletas
- **Usuários**: Gestão de usuários do sistema
- **PSE**: Percepção Subjetiva de Esforço
- **Sono**: Monitoramento de qualidade do sono
- **PFE**: Percepção de Fadiga e Esforço
- **Jogos**: Registro de jogos e competições
- **Minutagem**: Controle de tempo de treino
- **Treinadores**: Cadastro de treinadores

## 📊 Relatórios e Gráficos

O sistema inclui uma seção completa de relatórios com:

- **Distribuição Semanal**: Gráfico de pizza mostrando a distribuição dos tipos de treino
- **Percepção de Esforço**: Gráficos de linha com médias semanais de PFG, PFE e Técnico
- **Carga Agudo-Crônica**: Gráfico de dispersão para análise de carga de treino
- **Percepção de Fadiga**: Gráfico de barras com níveis de fadiga por dia
- **Carga Semanal**: Gráfico de área mostrando evolução da carga semanal
- **Filtros avançados**: Por tipo de treino, período e grupos

## 🛠️ Tecnologias

### Backend
- **PHP 8.0+**
- **MySQL/MariaDB**
- **JWT** para autenticação
- **Composer** para dependências
- **Swagger** para documentação

### Frontend
- **Vue 3** com Composition API
- **TypeScript**
- **PrimeVue** para componentes UI
- **Vite** para build
- **Axios** para requisições HTTP
- **Chart.js** para gráficos (opcional)

## 📋 Pré-requisitos

- **PHP 8.0** ou superior
- **MySQL 5.7** ou superior / **MariaDB 10.2** ou superior
- **Composer**
- **Node.js 16** ou superior
- **npm** ou **yarn**

## 🚀 Instalação

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd psa-cbg
```

### 2. Configuração do Backend

#### Instalar dependências PHP
```bash
cd backend
composer install
```

#### Configurar banco de dados
1. Crie um banco de dados MySQL
2. Copie o arquivo `.env.example` para `.env`
3. Configure as variáveis de banco de dados no `.env`:
```env
DB_HOST=localhost
DB_NAME=psa_cbg
DB_USER=seu_usuario
DB_PASS=sua_senha
JWT_SECRET=sua_chave_secreta_jwt
```

#### Executar migrações (se houver)
```bash
# Se houver migrações configuradas
php migrate.php
```

#### Iniciar servidor PHP
```bash
# Usando servidor embutido do PHP
php -S localhost:8000 -t public

# Ou usando XAMPP/WAMP - coloque os arquivos em htdocs
```

### 3. Configuração do Frontend

#### Instalar dependências
```bash
cd frontend/frontend
npm install
```

#### Instalar Chart.js (opcional - para gráficos avançados)
```bash
npm install chart.js
```

#### Configurar API
Edite o arquivo `src/services/api.ts` e configure a URL base da API:
```typescript
const api = axios.create({
  baseURL: 'http://localhost:8000', // URL do seu backend
  timeout: 10000
});
```

#### Iniciar servidor de desenvolvimento
```bash
npm run dev
```

## 🌐 URLs de Acesso

### Frontend
- **Dashboard**: http://localhost:5173
- **Login**: http://localhost:5173/login

### Backend
- **API Base**: http://localhost:8000
- **Documentação Swagger**: http://localhost:8000/swagger.yaml

## 📖 Uso

### 1. Acesso inicial
1. Acesse http://localhost:5173
2. Faça login com suas credenciais
3. Você será redirecionado para o dashboard

### 2. Navegação
- Use o menu lateral para acessar as diferentes seções
- Cada seção possui CRUD completo com filtros e paginação
- Use os botões de ação para criar, editar ou excluir registros

### 3. Relatórios
- Acesse a seção "Relatórios" no menu lateral
- Use os filtros para selecionar período e tipos de treino
- Navegue entre as abas para ver diferentes tipos de gráficos
- Clique em "Atualizar" para recarregar os dados

## 🔧 Configuração Avançada

### Variáveis de Ambiente (Backend)
```env
# Banco de dados
DB_HOST=localhost
DB_NAME=psa_cbg
DB_USER=root
DB_PASS=

# JWT
JWT_SECRET=sua_chave_secreta_muito_segura
JWT_EXPIRATION=24h

# CORS (se necessário)
CORS_ORIGIN=http://localhost:5173
```

### Configuração do Frontend
```typescript
// src/services/api.ts
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000',
  timeout: 10000,
  headers: {
    'Content-Type': 'application/json'
  }
});
```

## 📚 Documentação da API

A documentação completa da API está disponível em:
- **Swagger UI**: http://localhost:8000/swagger.yaml
- **Endpoints principais**:
  - `POST /auth/login` - Autenticação
  - `GET /atletas` - Listar atletas
  - `POST /atletas` - Criar atleta
  - `PUT /atletas/{id}` - Atualizar atleta
  - `DELETE /atletas/{id}` - Excluir atleta
  - `GET /relatorios/*` - Endpoints de relatórios

## 🚀 Deploy

### Backend (Produção)
1. Configure um servidor web (Apache/Nginx)
2. Configure o banco de dados de produção
3. Ajuste as variáveis de ambiente
4. Configure HTTPS

### Frontend (Produção)
```bash
npm run build
```
Os arquivos gerados estarão em `dist/` e podem ser servidos por qualquer servidor web.

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 🆘 Suporte

Para suporte, entre em contato com a equipe de desenvolvimento ou abra uma issue no repositório.

---

**Desenvolvido para a Confederação Brasileira de Ginástica (CBG)**

