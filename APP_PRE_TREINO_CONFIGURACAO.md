# App Pré-Treino - PSA-CBG

## Descrição
O App Pré-Treino é um módulo completo para gerenciamento de avaliações pré-treino dos atletas, incluindo métricas de recuperação, sono, fadiga, dor muscular e bem-estar.

## Estrutura da Tabela `pre_treino`

### Campos Principais:
- **id**: Chave primária (AUTO_INCREMENT)
- **atleta_id**: ID do atleta (FOREIGN KEY)
- **data_avaliacao**: Data da avaliação (DATE)
- **tqr_recuperacao**: TQR Recuperação (1-10)
- **fadiga_bem_estar**: Fadiga/Bem-estar (1-5)
- **nivel_fadiga**: Nível de Fadiga (1-10)
- **qualidade_sono**: Qualidade do Sono (1-5)
- **horas_sono**: Horas de Sono (ENUM: 'Menos que 6 horas', 'Entre 6 e 7 horas', '8 horas ou mais')
- **tempo_dormir**: Tempo para Dormir (ENUM: 'Menos que 30 min', 'Entre 30 min e 1h', 'Mais que 1h')
- **motivo_sono_inquieto**: Motivo do Sono Inquieto (TEXT, NULL)
- **vezes_acordou_noite**: Vezes que Acordou na Noite (ENUM: '1 vez', '2 a 4 vezes', '4 vezes ou mais')
- **dor_muscular_geral**: Dor Muscular Geral (1-5)
- **escala_dor_visual**: Escala de Dor Visual (1-10)
- **local_dor_especifica**: Local da Dor Específica (VARCHAR(255), NULL)
- **tipo_dor_muscular**: Tipo de Dor Muscular (VARCHAR(255), NULL)
- **nivel_estresse**: Nível de Estresse (1-5)
- **humor**: Humor (1-5)
- **periodo_pre_menstrual**: Período Pré-Menstrual (BOOLEAN)
- **periodo_menstrual**: Período Menstrual (BOOLEAN)
- **medicacao_uso**: Medicação em Uso (TEXT, NULL)
- **motivo_medicacao**: Motivo da Medicação (TEXT, NULL)
- **observacoes**: Observações (TEXT, NULL)
- **created_at**: Data de Criação (TIMESTAMP)
- **updated_at**: Data de Atualização (TIMESTAMP)

## Arquivos Criados

### Backend:
1. **PreTreinoModel.php** - Modelo com métodos CRUD básicos
2. **PreTreinoRepository.php** - Interface e implementação do repositório
3. **PreTreinoService.php** - Camada de serviço com validações
4. **PreTreinoController.php** - Controlador para gerenciar requisições
5. **PreTreinoRouter.php** - Roteamento da API

### Frontend:
1. **PreTreinoView.vue** - Interface completa com formulário e tabela

### Configurações:
1. **backend/public/index.php** - Adicionada rota `/pre-treino`
2. **frontend/src/router.ts** - Adicionada rota `/pre-treino`
3. **frontend/src/App.vue** - Adicionado item no menu lateral

## Funcionalidades Implementadas

### Backend:
- ✅ CRUD completo (Create, Read, Update, Delete)
- ✅ Validações de dados
- ✅ Tratamento de erros
- ✅ Logs para debug
- ✅ Valores padrão para campos obrigatórios
- ✅ Validação de enums e tipos de dados

### Frontend:
- ✅ Tabela com paginação e filtros
- ✅ Modal de cadastro/edição
- ✅ Formulário com todos os campos da tabela
- ✅ Sliders para campos numéricos
- ✅ Selects para campos enum
- ✅ Checkboxes para campos booleanos
- ✅ Validação de formulário
- ✅ Confirmação de exclusão
- ✅ Integração com API de atletas
- ✅ Interface responsiva e moderna

## Endpoints da API

- **GET** `/api/pre-treino` - Listar todos os pré-treinos
- **POST** `/api/pre-treino` - Criar novo pré-treino
- **PUT** `/api/pre-treino` - Atualizar pré-treino
- **DELETE** `/api/pre-treino/{id}` - Excluir pré-treino

## Como Usar

1. **Acessar**: Navegue para `/pre-treino` no menu lateral
2. **Criar**: Clique em "Novo Pré-Treino" e preencha o formulário
3. **Editar**: Clique no ícone de lápis na tabela
4. **Excluir**: Clique no ícone de lixeira na tabela
5. **Filtrar**: Use a barra de busca para encontrar registros

## Validações Implementadas

- Atleta e data de avaliação são obrigatórios
- Campos numéricos têm valores padrão se não informados
- Campos enum são validados contra valores permitidos
- Campos booleanos são convertidos corretamente
- Datas são formatadas adequadamente

## Próximos Passos Sugeridos

1. Adicionar gráficos de evolução temporal
2. Implementar relatórios comparativos
3. Adicionar filtros por período
4. Implementar exportação de dados
5. Adicionar notificações para valores críticos
6. Implementar dashboard específico para pré-treino

## Tecnologias Utilizadas

- **Backend**: PHP 8+, PDO, MySQL
- **Frontend**: Vue 3, TypeScript, PrimeVue
- **Arquitetura**: MVC com Repository Pattern
- **API**: RESTful com JSON
- **Estilização**: CSS Grid, Flexbox, PrimeVue Components 