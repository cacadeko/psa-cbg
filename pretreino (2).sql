-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/04/2025 às 21:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `pretreino`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `atletas`
--

CREATE TABLE `atletas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `data_nascimento` date NOT NULL,
  `posicao` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `acesso` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `atletas`
--

INSERT INTO `atletas` (`id`, `nome`, `data_nascimento`, `posicao`, `email`, `telefone`, `categoria`, `senha`, `acesso`, `created_at`, `usuario_id`) VALUES
(1, 'carlos', '2025-03-12', 'Goleiro', 'carlos@atm.com', 'Sub-11', 'Sub-11', '123456', 1, '2025-03-12 17:45:28', NULL),
(4, 'Fernando', '2012-11-13', 'Nao-aplicavel', 'Rio de Janeiro', 'Endereço Exemplo', 'Nao-aplicavel', 'abcd1234', 2, '2025-03-12 18:39:00', NULL),
(5, 'Joao', '2012-12-11', 'Nao-aplicavel', 'Rio de Janeiro', 'Rua 1', 'Nao-aplicavel', 'd41d8cd98f00b204e9800998ecf8427e', 2, '2025-03-14 16:21:19', NULL),
(6, 'Felipe', '2011-01-01', 'Zagueiro', 'felipe@atm.com', '(11) 22222-2222', 'Sub-09', 'abcd1234', 2, '2025-03-15 22:46:43', NULL),
(7, 'teste', '2011-01-01', 'Nao-aplicavel', 'teste@atm.com', '(21) 34435-3453', 'Nao-aplicavel', '123456', 1, '2025-03-16 17:32:07', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `registro_liberado` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `registro_liberado`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pse`
--

CREATE TABLE `pse` (
  `id` int(11) NOT NULL,
  `atleta_id` int(11) NOT NULL,
  `descricao` text NOT NULL,
  `nota_pse` int(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pse`
--

INSERT INTO `pse` (`id`, `atleta_id`, `descricao`, `nota_pse`, `created_at`) VALUES
(7, 1, 'fsdfsd', 10, '2025-03-14 18:29:48');

-- --------------------------------------------------------

--
-- Estrutura para tabela `qualidade_sono`
--

CREATE TABLE `qualidade_sono` (
  `id` int(11) NOT NULL,
  `atleta_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `avaliacao_psr` int(11) NOT NULL DEFAULT 5,
  `avaliacao_sono` int(11) NOT NULL DEFAULT 3,
  `acordou_durante_a_noite` enum('Sim','Não') NOT NULL DEFAULT 'Não',
  `hora_dormir` time NOT NULL,
  `hora_acordar` time NOT NULL,
  `intensidade_dor` enum('Sem dor','Dor leve','Dor moderada','Dor severa','Dor muito severa','Pior dor possivel') DEFAULT 'Sem dor',
  `dor_corpo` enum('Sim','Não') DEFAULT 'Não',
  `local_dor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `qualidade_sono`
--

INSERT INTO `qualidade_sono` (`id`, `atleta_id`, `created_at`, `avaliacao_psr`, `avaliacao_sono`, `acordou_durante_a_noite`, `hora_dormir`, `hora_acordar`, `intensidade_dor`, `dor_corpo`, `local_dor`) VALUES
(15, 4, '2025-03-13 20:24:32', 4, 3, 'Sim', '10:00:00', '12:00:00', 'Dor severa', 'Sim', 'fsdfdsfds'),
(17, 1, '2025-03-14 18:15:58', 1, 3, 'Sim', '10:00:00', '13:00:00', 'Dor leve', 'Sim', 'daasdasd'),
(20, 1, '2025-03-16 02:16:54', 0, 0, 'Não', '20:00:00', '08:00:00', NULL, 'Não', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `treinadores`
--

CREATE TABLE `treinadores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `especialidade` varchar(100) DEFAULT NULL,
  `data_contratacao` date DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nivel` enum('admin','treinador','aluno') DEFAULT 'aluno',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `atletas`
--
ALTER TABLE `atletas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pse`
--
ALTER TABLE `pse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atleta_id` (`atleta_id`);

--
-- Índices de tabela `qualidade_sono`
--
ALTER TABLE `qualidade_sono`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atleta_id` (`atleta_id`);

--
-- Índices de tabela `treinadores`
--
ALTER TABLE `treinadores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `atletas`
--
ALTER TABLE `atletas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pse`
--
ALTER TABLE `pse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `qualidade_sono`
--
ALTER TABLE `qualidade_sono`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `treinadores`
--
ALTER TABLE `treinadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `atletas`
--
ALTER TABLE `atletas`
  ADD CONSTRAINT `atletas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Restrições para tabelas `pse`
--
ALTER TABLE `pse`
  ADD CONSTRAINT `pse_ibfk_1` FOREIGN KEY (`atleta_id`) REFERENCES `atletas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `qualidade_sono`
--
ALTER TABLE `qualidade_sono`
  ADD CONSTRAINT `qualidade_sono_ibfk_1` FOREIGN KEY (`atleta_id`) REFERENCES `atletas` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `treinadores`
--
ALTER TABLE `treinadores`
  ADD CONSTRAINT `treinadores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
