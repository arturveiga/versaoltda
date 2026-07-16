# Versao Ltda

## Rodando com Docker

Requisitos:

- Docker Desktop
- Docker Compose

Suba o ambiente:

```bash
docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build
```

O WordPress e construido em uma imagem local a cada `up`. O codigo e implantado
em um filesystem Linux temporario do Docker, sem o bind mount do projeto inteiro
no Windows. Banco e uploads continuam persistentes entre os redeploys.
Depois de alterar o codigo, faca o redeploy:

```bash
docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build
```

O primeiro build pode demorar por causa do WordPress e dos plugins. Os builds
seguintes reutilizam o cache do Docker, e a navegacao fica mais rapida porque
os arquivos PHP nao sao lidos do NTFS em tempo real.

Acesse:

- Site: http://localhost:8080
- phpMyAdmin: http://localhost:8081
- Caixa de e-mail de teste (Mailpit): http://localhost:8025

O formulario de contato envia para `arturveiga_@hotmail.com`, mas no ambiente
local o Mailpit captura a mensagem. Assim e possivel conferir destinatario,
assunto e conteudo sem enviar um e-mail real.

Credenciais do banco no Docker:

- Banco: `versaoltda`
- Usuario: `root`
- Senha: `root`
- Host interno: `db`

O dump `database/versao-ltda-dev.sql` e importado automaticamente na primeira criacao do volume do MySQL.

Para recriar o banco do zero e importar o dump novamente:

```bash
docker compose -f docker-compose.yml -f docker-compose.local.yml down -v
docker compose -f docker-compose.yml -f docker-compose.local.yml up -d --build
```

Atencao: a opcao `-v` remove tanto o banco quanto os uploads locais. Um
`docker compose down` normal preserva ambos.

## E-mail do formulario no Coolify

As configuracoes ficam em variaveis de ambiente, sem precisar alterar PHP.
Cadastre no Coolify:

```env
WORDPRESS_CONTACT_RECIPIENT=arturveiga_@hotmail.com
WORDPRESS_SMTP_HOST=smtp.seu-provedor.com
WORDPRESS_SMTP_PORT=587
WORDPRESS_SMTP_AUTH=true
WORDPRESS_SMTP_ENCRYPTION=tls
WORDPRESS_SMTP_USERNAME=seu-usuario
WORDPRESS_SMTP_PASSWORD=sua-senha
WORDPRESS_SMTP_FROM_EMAIL=contato@seu-dominio.com
WORDPRESS_SMTP_FROM_NAME=Versao LTDA
```

Use `WORDPRESS_SMTP_ENCRYPTION=ssl` normalmente com a porta `465`. Para trocar
o destinatario ou o SMTP depois, altere somente essas variaveis e faca um novo
deploy. O arquivo `.env.example` contem a mesma lista como referencia.

## Fluxo de branch

```bash
git switch develop
git pull
git switch -c feature/nome-da-feature
```
