# psa-cbg
Percepção subjetiva da Confederação Brasileira de Ginastica

# Sistema de Análise de Percepção Subjetiva - CBG

Este sistema foi desenvolvido para a Confederação Brasileira de Ginástica com o objetivo de coletar, organizar e analisar dados subjetivos de atletas, como dor muscular, fadiga, qualidade do sono, entre outros indicadores que impactam o desempenho esportivo.

## 🔧 Tecnologias Utilizadas

- **Linguagem:** PHP 8+
- **Banco de Dados:** MySQL
- **Padrão de Arquitetura:** MVC (Model-View-Controller)
- **Frontend:** HTML5, CSS3, JavaScript
- **Gerenciamento de Sessões:** nativo via `session_start()`
- **Autoload:** Autoloader manual baseado na pasta `/controllers`, `/models`, `/config`

## 📁 Estrutura do Projeto

psa-cbg/
│
├── config/ # Configurações do sistema e banco
├── controllers/ # Lógica de controle (ex: QualidadeSonoController)
├── models/ # Modelos que interagem com o banco de dados
├── views/ # Interfaces HTML com formulários e dashboards
├── assets/ # Imagens, CSS e scripts
├── index.php # Entrada principal protegida por sessão
├── pretreino.sql # Script de criação de tabela e estrutura de banco
└── README.md # Documentação geral



## 🔐 Segurança

- O sistema protege a rota principal `index.php` exigindo autenticação (`$_SESSION['usuario']`).
- Não utiliza framework externo, sendo ideal para ambientes com restrições institucionais.

## 📈 Objetivo Funcional

- Permitir que atletas registrem diariamente suas percepções (fadiga, sono, dor, etc).
- Auxiliar treinadores e preparadores físicos a tomarem decisões com base em dados subjetivos.
- Armazenar histórico de percepções para análises futuras.

## 📌 Requisitos para Execução

- Servidor Apache/Nginx com PHP 8+
- Banco de dados MySQL
- Pasta `assets` com permissões de escrita para upload de arquivos

