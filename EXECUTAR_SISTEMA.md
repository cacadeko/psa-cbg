# 🚀 Como Executar o Sistema de Treinadores

## ⚡ Passos Rápidos

### 1. Atualizar Banco de Dados
```sql
-- Execute no seu MySQL/phpMyAdmin
ALTER TABLE `treinadores` 
ADD COLUMN `nivel` enum('admin','treinador','auxiliar','estagiario') DEFAULT 'treinador' AFTER `observacoes`;

ALTER TABLE `treinadores` 
ADD COLUMN `created_at` timestamp NOT NULL DEFAULT current_timestamp() AFTER `ativo`,
ADD COLUMN `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() AFTER `created_at`;

ALTER TABLE `usuarios` 
CHANGE COLUMN `senha` `senha_hash` varchar(255) NOT NULL;
```

### 2. Iniciar Backend
```bash
cd backend
php -S localhost:8000 -t public
```

### 3. Iniciar Frontend
```bash
cd frontend
npm install
npm run dev
```

### 4. Acessar Sistema
- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000/api
- **Swagger UI:** http://localhost:8000/swagger.html

---

## 🧪 Teste Rápido

1. **Acesse:** http://localhost:3000
2. **Clique em:** "Novo Treinador"
3. **Preencha:**
   - Nome: "João Silva"
   - Email: "joao@exemplo.com"
   - Senha: "123456"
   - Nível: "Treinador"
4. **Clique em:** "Salvar"
5. **Verifique:** Se o treinador aparece na lista

---

## 🔧 Solução de Problemas

### Erro de Conexão com Backend
- Verifique se o backend está rodando na porta 8000
- Confirme se a URL da API está correta em `frontend/src/services/api.ts`

### Erro de Banco de Dados
- Execute os comandos SQL acima
- Verifique se o MySQL está rodando
- Confirme as credenciais no arquivo `.env`

### Erro de Dependências Frontend
```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
```

---

## 📞 Suporte

Se encontrar problemas:
1. Verifique os logs do backend no terminal
2. Abra o DevTools do navegador (F12) e veja a aba Console
3. Teste a API diretamente via Swagger UI

**Sistema pronto para uso! 🎉** 