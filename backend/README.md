# Backend PSA-CBG (DDD, SOLID, PHP 8.2)

## Instalação

1. Instale as dependências:
   ```
   composer install
   ```
2. Copie o arquivo de exemplo de ambiente:
   ```
   cp .env.example .env
   ```
3. Configure as variáveis do `.env` conforme seu ambiente.

## Rodando o servidor

```
php -S localhost:8000 -t public
```

## Documentação da API

- Anote os controllers com Swagger-PHP.
- Gere a documentação:
  ```
  ./vendor/bin/openapi --output swagger.yaml App/
  ```
- Acesse a documentação em `/swagger.yaml` ou use Swagger UI.

## Estrutura
- DDD: Domain, Application, Infrastructure, Presentation
- Autoload: PSR-4 via Composer
- Logs: Monolog em /logs
- Autenticação: JWT
- Banco: MySQL 