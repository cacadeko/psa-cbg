-- Script para atualizar a tabela treinadores
-- Execute este script no seu banco de dados MySQL

-- Adicionar coluna nivel à tabela treinadores
ALTER TABLE `treinadores` 
ADD COLUMN `nivel` enum('admin','treinador','auxiliar','estagiario') DEFAULT 'treinador' AFTER `observacoes`;

-- Adicionar colunas de timestamp se não existirem
ALTER TABLE `treinadores` 
ADD COLUMN `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `ativo`,
ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `created_at`;

-- Atualizar a tabela usuarios para ter a coluna senha_hash em vez de senha
ALTER TABLE `usuarios` 
CHANGE COLUMN `senha` `senha_hash` varchar(255) NOT NULL;

-- Verificar se as colunas foram adicionadas corretamente
DESCRIBE `treinadores`;
DESCRIBE `usuarios`; 