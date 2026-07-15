# TODO Visual: Aproximar Home ao design/versao-final.jpeg

## Status

- [x] Confirmar que o site local renderiza a home do tema em `http://localhost:8080/`.
- [x] Confirmar que o WooCommerce Coming Soon nao esta bloqueando a home.
- [x] Criar este TODO na raiz do projeto.
- [x] Fazer comparacao visual inicial contra `design/versao-final.jpeg`.

## Ambiente

- [ ] Se o banco for recriado, desativar o Coming Soon:

```bash
docker compose exec -T db mysql -uroot -proot versaoltda -e "UPDATE wp_options SET option_value='no' WHERE option_name='woocommerce_coming_soon';"
```

- [x] Validar que a home mostra o tema customizado, nao o bloco `woocommerce/coming-soon`.
- [x] Conferir que nao ha PHP fatal/warnings visiveis.

## Header

- [x] Corrigir textos com encoding quebrado.
- [x] Ajustar altura, logo, menu e acoes para se aproximar do topo do layout.
- [x] Incluir icone de idioma antes das acoes.
- [ ] Validar menu real do WordPress se houver menu configurado no admin.

## Hero

- [x] Ajustar altura, overlay, posicao do background e bloco de copy.
- [x] Refinar release, logo, descricao e botao rosa.
- [x] Validar no browser contra o topo do JPEG em desktop largo.

## Intro / Gameplay

- [x] Ajustar screenshots, grid de texto e reviews.
- [x] Refinar tipografia, pesos, espacamento e cores.
- [ ] Validar proporcoes das screenshots em mobile.

## Detalhes da Edicao

- [x] Trocar kicker para `Conteudo da Edicao`.
- [x] Ajustar imagem, legenda, dots e setas visuais.
- [x] Refinar grid de features em 3 colunas.

## Cartucho

- [x] Atualizar copy para a versao de pre-venda.
- [x] Ajustar grid, CTA, codigo do produto e imagem com sombra.
- [ ] Confirmar se o cartucho final deve ser cromado/prata ou se o asset atual e placeholder.

## Produtos

- [x] Adicionar heading `Outros lancamentos`.
- [x] Ajustar cards, labels, precos e estados dos botoes.
- [ ] Substituir imagens se houver artes especificas para Earthion e Daemon Claw.

## Footer

- [x] Usar fundo escuro com textura/imagem.
- [x] Ajustar logo, slogan, menus, newsletter e creditos.
- [ ] Validar contraste no browser.

## Responsividade

- [ ] Desktop largo.
- [ ] 1280px.
- [ ] Tablet.
- [ ] Mobile.
- [ ] Conferir hero, cards, feature grid, footer e menu mobile.
