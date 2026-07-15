# Versao Ltda

## Rodando com Docker

Requisitos:

- Docker Desktop
- Docker Compose

Suba o ambiente:

```bash
docker compose up -d
```

O WordPress e construido em uma imagem local a cada `up`. O codigo e implantado
em um filesystem Linux temporario do Docker, sem o bind mount do projeto inteiro
no Windows. Banco e uploads continuam persistentes entre os redeploys.
Depois de alterar o codigo, faca o redeploy:

```bash
docker compose down
docker compose up -d
```

O primeiro build pode demorar por causa do WordPress e dos plugins. Os builds
seguintes reutilizam o cache do Docker, e a navegacao fica mais rapida porque
os arquivos PHP nao sao lidos do NTFS em tempo real.

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

Atencao: a opcao `-v` remove tanto o banco quanto os uploads locais. Um
`docker compose down` normal preserva ambos.

## Fluxo de branch

```bash
git switch develop
git pull
git switch -c feature/nome-da-feature
```
