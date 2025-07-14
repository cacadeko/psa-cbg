# 🚀 Quick Start - PSA-CBG

Guia rápido para executar o projeto PSA-CBG em desenvolvimento.

## ⚡ Setup Rápido

### 1. Backend (API)
```bash
# Instalar dependências
cd backend
composer install

# Configurar banco (crie um arquivo .env)
echo "DB_HOST=localhost DB_DATABASE=psa_cbg DB_USERNAME=root DB_PASSWORD=JWT_SECRET=Influx@3099" > .env

# Executar servidor
cd public
php -S localhost:8080
```

### 2. Frontend (Dashboard)
```bash
# Instalar dependências
cd frontend/frontend
npm install

# Executar servidor
npm run dev
```

### 3. Acessar o Sistema
- **Dashboard:** http://localhost:5173
- **API Docs:** http://localhost:8080/swagger.yaml
- **Login:** admin / admin

## 📋 Checklist de Verificação

- [ ] PHP 8.0+ instalado
- [ ] MySQL/MariaDB rodando
- [ ] Node.js 16+ instalado
- [ ] Composer instalado
- [ ] Banco de dados criado
- [ ] Script `pretreino.sql` executado
- [ ] Backend rodando na porta 8080
- [ ] Frontend rodando na porta 5173
- [ ] Login funcionando

## 🔧 Troubleshooting

### Erro de conexão com banco
- Verifique se o MySQL está rodando
- Confirme as credenciais no arquivo `.env`
- Execute `php -m | grep pdo` para verificar extensão

### Erro no frontend
- Verifique se o Node.js está instalado: `node --version`
- Limpe cache: `npm cache clean --force`
- Reinstale dependências: `rm -rf node_modules && npm install`

### CORS errors
- Verifique se a URL da API está correta em `src/services/api.ts`
- Confirme se o backend está rodando na porta 8080

## 📞 Suporte

Para dúvidas ou problemas, consulte o README.md completo ou entre em contato com a equipe de desenvolvimento. 