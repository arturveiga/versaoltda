# Versao Ltda

## Rodando com Docker

Requisitos:

- Docker Desktop
- Docker Compose

Suba o ambiente:

```bash
docker compose up -d
```

Acesse:

- Site: http://localhost:8080
- phpMyAdmin: http://localhost:8081

Credenciais do banco no Docker:

- Banco: `versaoltda`
- Usuario: `root`
- Senha: `root`
- Host interno: `db`

O dump `database/versao-ltda-dev.sql` e importado automaticamente na primeira criacao do volume do MySQL.

Para recriar o banco do zero e importar o dump novamente:

```bash
docker compose down -v
docker compose up -d
```

## Fluxo de branch

```bash
git switch develop
git pull
git switch -c feature/nome-da-feature
```
