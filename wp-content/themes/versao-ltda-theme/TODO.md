# TODO - Modo Manutenção (Coming Soon) - Versão LTDA

## Planejado
- [x] Criar `inc/maintenance.php` (lógica completa: hooks, Settings API, interceptação via `template_redirect`).

- [x] Criar `templates/maintenance.php` (HTML completo com `wp_head()`, `wp_footer()`, `wp_body_open()`, `language_attributes()`, `body_class()`).

- [x] Criar `assets/css/maintenance.css` (estilos sem CSS inline).

- [x] Criar `assets/js/maintenance.js` (JS da página de manutenção).

- [x] Criar pasta `assets/images/maintenance/` com assets padrão (logo e background baseados nos existentes, se necessário).

- [x] Atualizar `functions.php` adicionando somente o include `inc/maintenance.php`.

- [x] Atualizar `inc/enqueue.php` para carregar `maintenance.css` e `maintenance.js` **somente** quando o modo manutenção estiver ativo.


## Testes pós-implementação
- [ ] Visitante não logado: receber 503 + visualização do template.
- [x] Administrador logado: navega normalmente (incluindo WooCommerce).
- [x] Verificar que `wp_head/wp_footer` e hooks do WooCommerce não quebram.

