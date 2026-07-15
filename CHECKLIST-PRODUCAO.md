# Checklist de testes e producao - Versao LTDA

Este documento organiza a validacao local, a homologacao das integracoes e a preparacao da loja para producao.

> Nunca use credenciais de producao, cartao real ou dados reais de clientes no ambiente local.

## 1. Ambiente local

- [ ] Iniciar os containers com `docker compose up -d`.
- [ ] Confirmar o site em `http://localhost:8080` e o phpMyAdmin em `http://localhost:8081`.
- [ ] Confirmar que `WORDPRESS_FS_METHOD=direct` existe somente no ambiente Docker local.
- [ ] Confirmar `memory_limit=512M` e limite de upload de 64 MB no PHP local.
- [ ] Em **Plugins > Adicionar plugin**, instalar e ativar o Mercado Pago Payments for WooCommerce.
- [ ] Baixar o ZIP da integracao WooCommerce dentro da conta SuperFrete.
- [ ] Em **Plugins > Adicionar plugin > Enviar plugin**, enviar o ZIP da SuperFrete e ativar.
- [ ] Nao versionar plugins baixados, tokens, senhas, chaves ou arquivos `.env` com segredos.

## 2. Expor o localhost para callbacks

Mercado Pago e SuperFrete podem precisar chamar webhooks ou validar URLs publicas. `localhost` nao pode ser acessado pelos servidores externos.

- [ ] Criar um tunel HTTPS temporario com Cloudflare Tunnel, ngrok ou ferramenta equivalente.
- [ ] Apontar o tunel para `http://localhost:8080`.
- [ ] Confirmar que home, carrinho, checkout e Minha Conta abrem pela URL HTTPS do tunel.
- [ ] Usar a URL HTTPS temporaria nos callbacks/webhooks durante os testes.
- [ ] Encerrar o tunel e remover URLs temporarias ao finalizar a homologacao.

## 3. Produtos e estoque

- [ ] Cadastrar SKU, nome, descricao curta e descricao completa de cada produto.
- [ ] Cadastrar imagem principal e galeria.
- [ ] Definir preco, estoque e limite de compra.
- [ ] Informar peso, comprimento, largura e altura reais de cada produto.
- [ ] Validar se as dimensoes consideram a embalagem final.
- [ ] Definir produtos de pre-venda e a data estimada de envio.
- [ ] Fazer um pedido com uma unidade e outro com mais de uma unidade.

## 4. SuperFrete

- [ ] Criar ou revisar a conta da empresa na SuperFrete.
- [ ] Conectar o plugin pelo fluxo OAuth **Conectar com SuperFrete > Autorizar**.
- [ ] Em **WooCommerce > Configuracoes > Geral**, preencher endereco de origem, numero, cidade, estado e CEP.
- [ ] Usar CEP somente com numeros quando a integracao exigir.
- [ ] Em **WooCommerce > Configuracoes > Entrega**, criar a zona `Brasil - SuperFrete`.
- [ ] Adicionar Brasil como regiao da zona.
- [ ] Adicionar os servicos desejados, como Correios, Jadlog e Loggi, quando disponiveis.
- [ ] Configurar dias adicionais, acrescimo de valor, seguro e regra de frete gratis.
- [ ] Calcular frete para CEP local, capital, interior e regiao distante.
- [ ] Testar produto sem dimensoes e confirmar que o erro e compreensivel.
- [ ] Confirmar que valor e prazo aparecem no carrinho e no checkout.
- [ ] Confirmar que o metodo escolhido fica gravado no pedido.
- [ ] Em **WooCommerce > Pedidos**, abrir o pedido e usar **Verificar etiqueta**.
- [ ] Antes de pagar, confirmar valor da etiqueta e saldo SuperFrete.
- [ ] Para um teste fisico completo, pagar uma etiqueta de baixo custo, imprimir, postar e acompanhar o codigo.
- [ ] Depois da postagem, confirmar que **Rastrear pedido** abre os eventos da SuperFrete.
- [ ] Confirmar se o cliente ve o rastreio em Minha Conta e nos e-mails; implementar essa exibicao no tema caso o plugin nao a forneca.
- [ ] Em caso de falha, consultar **WooCommerce > Logs SuperFrete**.

> A documentacao publica da SuperFrete descreve cotacao, compra de etiqueta e rastreio real, mas nao documenta um sandbox completo. Nao pague etiqueta apenas para testar layout. O fluxo de rastreamento ponta a ponta depende de postagem real e eventos da transportadora.

## 5. Mercado Pago em teste

- [ ] Criar uma aplicacao em **Mercado Pago Developers > Suas integracoes**.
- [ ] Criar duas contas de teste brasileiras distintas: uma vendedora e uma compradora.
- [ ] Nunca usar a mesma conta de teste nos dois lados da compra.
- [ ] Em janela anonima, entrar como vendedor de teste e vincular essa conta no plugin WooCommerce.
- [ ] Seguir o modo de venda indicado pelo assistente atual do plugin para contas de teste.
- [ ] Em outra janela anonima, entrar como comprador de teste.
- [ ] Usar somente dados e cartoes de teste fornecidos pelo Mercado Pago.
- [ ] Simular pagamento aprovado.
- [ ] Simular pagamento pendente.
- [ ] Simular pagamento recusado.
- [ ] Confirmar que o pedido nao e duplicado quando a pagina e recarregada.
- [ ] Confirmar a transicao correta dos status WooCommerce: pendente, aguardando, processando, concluido, falhou e reembolsado.
- [ ] Confirmar que notificacoes/webhooks chegam pela URL HTTPS publica.
- [ ] Conferir logs do WooCommerce e do Mercado Pago sem expor tokens.
- [ ] Testar cancelamento e reembolso no ambiente de teste.
- [ ] Ao terminar, desvincular a conta de teste antes de configurar producao.

## 6. Jornada completa de homologacao

- [ ] Criar um usuario cliente de teste.
- [ ] Abrir um produto e adiciona-lo a whitelist.
- [ ] Adicionar o produto ao carrinho.
- [ ] Informar um CEP valido e escolher um frete SuperFrete.
- [ ] Finalizar o checkout com uma conta compradora Mercado Pago de teste.
- [ ] Confirmar a pagina de pedido recebido.
- [ ] Confirmar o pedido em **Minha Conta > Pedidos e pre-vendas**.
- [ ] Confirmar o pedido no painel administrativo.
- [ ] Confirmar pagamento e mudanca automatica para `Processando`.
- [ ] Confirmar sincronizacao do pedido com a SuperFrete.
- [ ] Gerar etiqueta somente quando o teste fisico for intencional.
- [ ] Adicionar ou receber o codigo de rastreio.
- [ ] Confirmar rastreio no painel, Minha Conta e e-mail do cliente.
- [ ] Marcar o pedido como concluido depois da entrega.
- [ ] Testar tambem cancelamento, falha de pagamento e reembolso.

## 7. Infraestrutura de producao

- [ ] Contratar hospedagem Linux compativel com WordPress, WooCommerce e PHP 8.2.
- [ ] Usar MySQL 8 ou MariaDB compativel.
- [ ] Configurar `memory_limit` de pelo menos 256 MB; preferir 512 MB para Mercado Pago/WooCommerce.
- [ ] Garantir extensoes PHP exigidas, incluindo cURL, DOM, GD, iconv, PDO MySQL, SimpleXML e SOAP quando solicitado.
- [ ] Garantir acesso HTTPS de saida para APIs e webhooks.
- [ ] Configurar dominio definitivo e certificado TLS valido.
- [ ] Forcar HTTPS e revisar URLs do WordPress e WooCommerce.
- [ ] Configurar cron real do servidor para tarefas agendadas do WooCommerce.
- [ ] Criar ambiente de staging separado da producao.
- [ ] Configurar SMTP transacional para pedidos, pagamentos e rastreios.
- [ ] Configurar backup automatico de banco e `wp-content`, com teste de restauracao.
- [ ] Configurar monitoramento de disponibilidade, erros PHP, filas e webhooks.

## 8. Seguranca e acesso

- [ ] Gerar salts unicos de producao; nao reutilizar os salts locais atuais.
- [ ] Usar usuario e senha exclusivos para o banco, sem `root`.
- [ ] Guardar tokens e credenciais fora do Git e fora de arquivos publicos.
- [ ] Aplicar principio de menor privilegio a arquivos e usuarios administrativos.
- [ ] Em producao, permitir escrita direta apenas quando proprietario e permissoes estiverem corretos.
- [ ] Desativar o editor de arquivos do painel com `DISALLOW_FILE_EDIT`.
- [ ] Avaliar `DISALLOW_FILE_MODS` se atualizacoes forem feitas por deploy controlado.
- [ ] Ativar 2FA nas contas WordPress, Mercado Pago, SuperFrete, hospedagem e dominio.
- [ ] Limitar tentativas de login e proteger o painel administrativo.
- [ ] Atualizar WordPress, WooCommerce, tema e plugins primeiro em staging.
- [ ] Remover plugins e temas sem uso.
- [ ] Executar verificacao de malware e revisar logs de auditoria.

## 9. Configuracao comercial e legal

- [ ] Validar razao social, nome fantasia, CNPJ, endereco e contatos.
- [ ] Configurar moeda BRL, unidade de peso e unidade de dimensao.
- [ ] Definir impostos e emissao fiscal com contador.
- [ ] Publicar Termos de uso, Politica de privacidade e Politica de cookies.
- [ ] Publicar Politica de entrega, prazos, extravio e areas atendidas.
- [ ] Publicar Politica de troca, devolucao, cancelamento e reembolso.
- [ ] Revisar consentimentos e tratamento de dados conforme LGPD.
- [ ] Configurar canal de suporte ao cliente.

## 10. Mercado Pago em producao

- [ ] Concluir cadastro e verificacao da conta vendedora real.
- [ ] Ativar as credenciais de producao da aplicacao.
- [ ] Vincular a conta real no plugin e remover qualquer conta de teste.
- [ ] Configurar URL publica HTTPS para notificacoes.
- [ ] Revisar meios de pagamento, parcelas, juros, vencimentos e descritor da fatura.
- [ ] Fazer uma compra real de baixo valor.
- [ ] Confirmar recebimento da notificacao e mudanca de status no WooCommerce.
- [ ] Fazer um reembolso real e conferir conciliacao.
- [ ] Nunca registrar Access Token, dados completos de cartao ou dados pessoais em logs.

## 11. SuperFrete em producao

- [ ] Conectar a conta SuperFrete definitiva.
- [ ] Confirmar endereco de origem e dados do remetente.
- [ ] Confirmar saldo e forma de pagamento das etiquetas.
- [ ] Revisar todas as zonas e metodos de entrega.
- [ ] Fazer cotacoes com diferentes CEPs e produtos.
- [ ] Emitir uma etiqueta real de homologacao.
- [ ] Imprimir e validar codigo de barras, remetente, destinatario, peso e dimensoes.
- [ ] Postar o pacote e acompanhar os eventos ate a entrega.
- [ ] Validar notificacoes ao cliente e procedimento de extravio/devolucao.

## 12. Internacionalizacao

- [ ] Revisar todas as traducoes do tema em `languages/en_US.l10n.php`.
- [ ] Instalar uma solucao compativel com WooCommerce, como Polylang for WooCommerce ou WPML/WooCommerce Multilingual, para traduzir conteudo do banco.
- [ ] Criar versoes em ingles de paginas, produtos, descricoes, categorias, atributos e e-mails comerciais.
- [ ] Definir URLs internacionais e regras de SEO com `hreflang` e canonicals.
- [ ] Configurar moeda, impostos, fretes, prazos e politicas para cada pais atendido.
- [ ] Variar o cache de pagina pelo cookie `versao_ltda_locale` ou pela URL de idioma.
- [ ] Testar carrinho, checkout, pagamento e e-mails em cada idioma.

## 13. Go-live

- [ ] Congelar mudancas durante a publicacao.
- [ ] Fazer backup imediatamente antes do deploy.
- [ ] Migrar somente configuracoes e dados revisados; nao levar pedidos/clientes de teste.
- [ ] Limpar caches e regenerar links permanentes.
- [ ] Testar home, produto, whitelist, carrinho, checkout e Minha Conta.
- [ ] Fazer compra real controlada com frete real.
- [ ] Conferir e-mail, pagamento, pedido, etiqueta e rastreio.
- [ ] Monitorar logs e pedidos diariamente na primeira semana.
- [ ] Manter um procedimento documentado de rollback e contato dos fornecedores.

## Referencias oficiais

- WordPress Filesystem API: https://developer.wordpress.org/reference/functions/get_filesystem_method/
- Mercado Pago para WooCommerce - testes: https://www.mercadopago.com.br/developers/pt/docs/woocommerce/integration-test
- Mercado Pago para WooCommerce - requisitos: https://www.mercadopago.com.br/developers/pt/docs/woocommerce/previous-requirements
- SuperFrete - integracao WooCommerce: https://ajuda.superfrete.com/artigo/manual-de-integracao-woocommerce-e-superfrete/
- SuperFrete - pedidos, etiquetas e rastreio: https://ajuda.superfrete.com/artigo/manual-de-primeiro-uso-woocommerce-e-superfrete/
