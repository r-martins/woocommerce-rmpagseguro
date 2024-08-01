=== Módulo PagSeguro ===
Contributors: claudiosanches, Gabriel Reguly, martins56
Donate link: https://github.com/sponsors/r-martins
Tags: pagseguro,pagbank,payment,pagbank,pagamento,woocommerce
Requires at least: 4.0
Tested up to: 6.6
Stable tag: 3.16.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adiciona PagSeguro aos meios de pagamento WooCommerce com taxas especiais de parceiro (ex: 3,15% no crédito).

== Description ==

### Considere usar o novo plugin para vender mais! 🚀
👉 **A nova geração de nossa integração está [disponível aqui](https://wordpress.org/plugins/pagbank-connect/).** 👈

Ela pede menos informações ao consumidor, e tem recursos como PIX transparente, Autenticação 3Ds (aumenta a taxa de aprovação e reduz chargebacks), Recorrência, e muito mais.

Esta versão utiliza uma API do PagSeguro que será descontinuada no futuro por eles.

---

Veja o [passo a passo de instalação](https://pagseguro.ricardomartins.net.br/woocommerce/wizard.html) para WooCommerce

### Adicionar PagSeguro (PagBank) ao WooCommerce ###

Adicione o PagSeguro (PagBank) como método de pagamento em sua loja WooCommerce.

[PagSeguro](https://pagseguro.uol.com.br/) é um método de pagamento brasileiro oferecido pela UOL.

Este plugin foi desenvolvido a partir da [documentação oficial do PagSeguro](https://pagseguro.uol.com.br/v2/guia-de-integracao/visao-geral.html) e utiliza uma versão depreciada da API de pagamentos (legado).

Para usar as novas APIs, utilize a [Nova Geração de nossa integração](https://wordpress.org/plugins/pagbank-connect/).

### Descontos nas taxas ###

Graças a uma parceria com o PagSeguro, as transações realizadas por este módulo, possuem [condições especiais junto ao PagSeguro](https://pagseguro.ricardomartins.net.br/compare.html). 

Ao usar nossa integração, você não pagará mais a taxa de intermediação do PagSeguro (tipicamente R$0,40/pedido aprovado) e passará a pagar uma taxa de transação menor que a taxa oficial (que é 4,99% para recebimento em 14 dias ou 3,99% para recebimento em 30 dias).

Caso sua conta PagSeguro possua uma taxa ou condição negociada melhor que a nossa, e seu faturamento seja superior a R$20 mil/mês, [entre em contato](https://pagsegurotransparente.zendesk.com/hc/pt-br/requests/new) conosco antes de usar nossa integração.

Você não paga nada a mais por isso e ainda ajuda este e [outros projetos](https://pagsegurotransparente.zendesk.com/hc/pt-br/articles/208158763).



Estão disponíveis as seguintes modalidades de pagamento:

- **Redirect:** Cliente é redirecionado ao PagSeguro para concluir a compra.
- **Lightbox:** Uma janela do PagSeguro é aberta na finalização para o cliente fazer o pagamento.
- **Transparente:** O cliente faz o pagamento direto no seu site sem precisar ir ao site do PagSeguro.

Para usar a Sandbox do PagSeguro, consulte [nossa documentação](https://pagsegurotransparente.zendesk.com/hc/pt-br/).

= Compatibilidade =

Compatível com versões posteriores ao WooCommerce 3.0.

Este plugin também é compatível com o [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/), desta forma é possível enviar os campos de "CPF", "número do endereço" e "bairro" (para o Checkout Transparente é obrigatório o uso deste plugin).

Novas funcionalidades somente serão adicionadas à [nova geração da nossa integração](https://wordpress.org/plugins/pagbank-connect/), mas se encontrar problemas ou erros nesta, [entre em contato conosco](https://pagsegurotransparente.zendesk.com/hc/pt-br/requests/new).

= Instalação =

Confira o nosso [passo a passo de instalação e configuração](https://pagseguro.ricardomartins.net.br/woocommerce/wizard.html).

= Integração =

Este plugin funciona perfeitamente em conjunto com:

* [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/).
* [WooCommerce Multilingual](https://wordpress.org/plugins/woocommerce-multilingual/).

= Dúvidas? =

Você pode esclarecer suas dúvidas usando:

* A nossa sessão de [FAQ](https://wordpress.org/plugins/woo-pagseguro-rm/#faq).
* Na [Central de Ajuda](https://pagsegurotransparente.zendesk.com/hc/pt-br).
* Entrando em contato na Central de Ajuda no link acima.

= Colaborar =

Você pode contribuir com código-fonte em nossa página no [GitHub](https://github.com/r-martins/woocommerce-rmpagseguro/).

= Agradecimentos =

* [Leandro Matos](http://is-uz.com/) ajudou com o layout e os ícones do Checkout Transparente.

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to WooCommerce -> Settings -> Payment Gateways, choose PagSeguro and fill in your PagSeguro Email and Token.

### Instalação e configuração em Português: ###

= Instalação do plugin: =

* Envie os arquivos do plugin para a pasta wp-content/plugins, ou instale usando o instalador de plugins do WordPress.
* Ative o plugin.
* Lembre-se de [autorizar nossa aplicação](https://pagseguro.ricardomartins.net.br/woocommerce/wizard.html) junto ao PagSeguro para obter sua chave e configurar o módulo.

= Requerimentos: =

É necessário possuir uma conta no [PagSeguro](http://pagseguro.uol.com.br/) e ter instalado o [WooCommerce](http://wordpress.org/plugins/woocommerce/).

= Configurações no PagSeguro: =

No PagSeguro é necessário desativar a opção de "Pagamento via Formulário HTML", você pode fazer isso em "Preferências" > "[Integrações](https://pagseguro.uol.com.br/preferencias/integracoes.jhtml)".

Com isso já é possível receber os pagamentos e receber notificações do PagSeguro para atualizar seus pedidos automaticamente.

<blockquote>Atenção: Não é necessário configurar qualquer URL em "Página de redirecionamento" ou "Notificação de transação", pois o plugin é capaz de comunicar o PagSeguro pela API quais URLs devem ser utilizadas para cada situação.</blockquote>

= Configurações do Plugin: =

Com o plugin instalado acesse o admin do WordPress e entre em "WooCommerce" > "Configurações" > "Finalizar compra" > "PagSeguro".

Habilite o PagSeguro, adicione o seu e-mail e o token do PagSeguro. O token é utilizado para gerar os pagamentos e fazer o retorno de dados.

Você pode conseguir um token no PagSeguro em "Integrações" > "[Token de Segurança](https://pagseguro.uol.com.br/integracao/token-de-seguranca.jhtml)".

PagSeguro App Key: [Autorize nossa aplicação](https://pagseguro.ricardomartins.net.br/woocommerce/wizard.html) junto ao PagSeguro para obter sua chave e usufruir das novas taxas de recebimento e maior conversão.

É possível escolher entre três opções de pagamento que são:

- **Redirecionar (padrão):** O cliente e redirecionado para o site do PagSeguro onde pode [pagar com PIX](https://pagsegurotransparente.zendesk.com/hc/pt-br/articles/360056018451-Como-aceitar-PIX-com-PagSeguro-em-meu-site-), Saldo PagSeguro, e outros meios de pagamento.
- **Checkout em Lighbox:** O cliente permanece no seu site é aberto um Lightbox do PagSeguro onde o cliente fará o pagamento
- **Checkout Transparente:** O cliente faz o pagamento direto em seu site na página de finalizar pedido utilizando a API do PagSeguro.

Você ainda pode definir o comportamento da integração utilizando as opções:

- **Enviar apenas o total do pedido:** Permite enviar apenas o total do pedido no lugar da lista de itens, esta opção deve ser utilizada apenas quando o total do pedido no WooCommerce esta ficando diferente do total no PagSeguro.
- **Prefixo de pedido:** Esta opção é útil quando você esta utilizando a mesma conta do PagSeguro em várias lojas e com isso você pode diferenciar os pagamentos pelo prefixo.

= Checkout Transparente =

Para utilizar o checkout transparente é necessário utilizar o plugin [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/).

Com o **WooCommerce Extra Checkout Fields for Brazil** instalado e ativado você deve ir até "WooCommerce > Campos do Checkout" e configurar a opção "Exibir Tipo de Pessoa" como "Pessoa Física apenas".

Isto é necessário porque é obrigatório o envio de CPF para o PagSeguro, além de que o PagSeguro aceita apenas CPF para documento do titular do cartão.


Pronto, sua loja já pode receber pagamentos pelo PagSeguro.

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is needed to use this plugin? =

* WooCommerce version 3.0 or latter installed and active.
* Only one account on [PagSeguro](http://pagseguro.uol.com.br/ "PagSeguro").

### FAQ em Português: ###

= Qual é a licença do plugin? =

Este plugin esta licenciado como GPL.

= O que eu preciso para utilizar este plugin? =

* Ter instalado o plugin WooCommerce 3.0 ou mais recente.
* Possuir uma conta no PagSeguro.
* Gerar um token de segurança no PagSeguro (logo não será necessário).
* [Autorizar nossa integração](https://pagseguro.ricardomartins.net.br/woocommerce/wizard.html) junto ao PagSeguro
* Desativar a opção "Pagamento via Formulário HTML" em integrações na página do PagSeguro.

= PagSeguro recebe pagamentos de quais países? =

No momento o PagSeguro recebe pagamentos apenas do Brasil.

Configuramos o plugin para receber pagamentos apenas de usuários que selecionarem o Brasil nas informações de pagamento durante o checkout.

= Quais são os meios de pagamento que o plugin aceita? =

São aceitos todos os meios de pagamentos que o PagSeguro disponibiliza, entretanto você precisa ativa-los na sua conta.

Confira os [meios de pagamento e parcelamento](https://pagseguro.uol.com.br/para_voce/meios_de_pagamento_e_parcelamento.jhtml#rmcl).

= Como que plugin faz integração com PagSeguro? =

Fazemos a integração baseada na documentação oficial do PagSeguro que pode ser encontrada nos "[guias de integração](https://pagseguro.uol.com.br/receba-pagamentos.jhtml)" utilizando a última versão da API de pagamentos.

= Instalei o plugin, mas a opção de pagamento do PagSeguro some durante o checkout. O que fiz de errado? =

Você esqueceu de selecionar o Brasil durante o cadastro no checkout.

A opção de pagamento pelo PagSeguro funciona apenas com o Brasil.

= É possível enviar os dados de "Número", "Bairro" e "CPF" para o PagSeguro? =

Sim é possível, basta utilizar o plugin [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/).

= O pedido foi pago e ficou com o status de "processando" e não como "concluído", isto esta certo? =

Sim, esta certo e significa que o plugin esta trabalhando como deveria.

Todo gateway de pagamentos no WooCommerce deve mudar o status do pedido para "processando" no momento que é confirmado o pagamento e nunca deve ser alterado sozinho para "concluído", pois o pedido deve ir apenas para o status "concluído" após ele ter sido entregue.

Para produtos baixáveis a configuração padrão do WooCommerce é permitir o acesso apenas quando o pedido tem o status "concluído", entretanto nas configurações do WooCommerce na aba *Produtos* é possível ativar a opção **"Conceder acesso para download do produto após o pagamento"** e assim liberar o download quando o status do pedido esta como "processando".

= Ao tentar finalizar a compra aparece a mensagem "PagSeguro: Um erro ocorreu ao processar o seu pagamento, por favor, tente novamente ou entre em contato para obter ajuda." o que fazer? =

Esta mensagem geralmente aparece por que não foi configurado um **Token válido**.
Gere um novo Token no PagSeguro em "Preferências" > "[Integrações](https://pagseguro.uol.com.br/preferencias/integracoes.jhtml)" e adicione ele nas configurações do plugin.

Outro erro comum é gerar um token e cadastrar nas configurações do plugin um e-mail que não é o proprietário do token, então tenha certeza que estes dados estão realmente corretos!

Note que caso você esteja utilizando a opção de **sandbox** é necessário usar um e-mail e token de testes que podem ser encontrados em "[PagSeguro Sandbox > Dados de Teste](https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html)".

Se você tem certeza que o Token e Login estão corretos você deve acessar a página "WooCommerce > Status do Sistema" e verificar se **fsockopen** e **cURL** estão ativos. É necessário procurar ajuda do seu provedor de hospedagem caso você tenha o **fsockopen** e/ou o **cURL** desativados.

Para quem estiver utilizando o **Checkout Transparente** é obrigatório o uso do plugin [WooCommerce Extra Checkout Fields for Brazil](http://wordpress.org/plugins/woocommerce-extra-checkout-fields-for-brazil/) para enviar o CPF ao PagSeguro, caso o contrário será impossível de finalizar o pedido, veja no [guia de instalação](http://wordpress.org/plugins/woocommerce-pagseguro/installation/) como fazer isso.

Por último é possível ativar a opção de **Log de depuração** nas configurações do plugin e tentar novamente fechar um pedido (você deve tentar fechar um pedido para que o log será gerado e o erro gravado nele).
Com o log é possível saber exatamente o que esta dando de errado com a sua instalação.

Caso você não entenda o conteúdo do log não tem problema, você pode me abrir um [chamado na central de ajuda](https://pagsegurotransparente.zendesk.com/hc/pt-br/) com o link do log (utilize o [pastebin.com](http://pastebin.com) ou o [gist.github.com](http://gist.github.com) para salvar o conteúdo do log).

= O status do pedido não é alterado automaticamente? =

Sim, o status é alterado automaticamente usando a API de notificações de mudança de status do PagSeguro.

Caso os status dos seus pedidos não estiverem sendo alterados [clique aqui](https://pagsegurotransparente.zendesk.com/hc/pt-br/articles/209089326-Configurei-o-retorno-autom%C3%A1tico-mas-parece-n%C3%A3o-funcionar-) e veja algumas soluções para testar e corrigir o problema.

A seguir uma lista de ferramentas que podem estar bloqueando as notificações do PagSeguro:

* Site com CloudFlare, pois por padrão serão bloqueadas quaisquer comunicações de outros servidores com o seu. É possível resolver isso desbloqueando a lista de IPs do PagSeguro.
* Plugin de segurança como o "iThemes Security" com a opção para adicionar a lista do HackRepair.com no .htaccess do site. Acontece que o user-agent do PagSeguro esta no meio da lista e vai bloquear qualquer comunicação. Você pode remover isso da lista, basta encontrar onde bloquea o user-agent "jakarta" e deletar ou criar uma regra para aceitar os IPs do PagSeguro).
* `mod_security` habilitado, neste caso vai acontecer igual com o CloudFlare bloqueando qualquer comunicação de outros servidores com o seu. Como solução você pode desativar ou permitir os IPs do PagSeguro.

= Funciona com o Lightbox do PagSeguro? =

Sim, basta ativar esta nas opções do plugin.

= Funciona com o checkout transparente do PagSeguro? =

Sim, funciona. Você deve ativar nas opções do plugin.
Com a nossa integração não é necessário autorização para utilizar o mesmo.

= Funciona com o Sandbox do PagSeguro? =

Sim, funciona. O difícil é conseguir fazer a Sandbox do PagSeguro funcionar. Considere usar a nova versão da nossa integração, que usa a nova api.

= O total do pedido no WooCommerce é diferente do enviado para o PagSeguro, como eu resolvo isso? =

Caso você tenha este problema, basta marcar ativar a opção **Enviar apenas o total do pedido** na página de configurações do plugin.

= Quais URLs eu devo usar para configurar "Notificação de transação" e "Página de redirecionamento"? =

Não é necessário configurar qualquer URL para "Notificação de transação" ou para "Página de redirecionamento", o plugin já diz para o PagSeguro quais URLs serão utilizadas.

= Mais dúvidas relacionadas ao funcionamento do plugin? =

Por favor, caso você tenha algum problema com o funcionamento do plugin, [entre em contato conosco](https://pagsegurotransparente.zendesk.com/hc/pt-br/requests/new) com o link arquivo de log (ative ele nas opções do plugin e tente fazer uma compra, depois vá até WooCommerce > Status do Sistema, selecione o log do *pagseguro* e copie os dados, depois crie um link usando o [pastebin.com](http://pastebin.com) ou o [gist.github.com](http://gist.github.com)), desta forma fica mais rápido para fazer o diagnóstico.

== Screenshots ==

1. Configurações do plugin.
2. Método de pagamento na página de finalizar o pedido.
3. Exemplo do Lightbox funcionando com o Sandbox do PagSeguro.
4. Pagamento com cartão de crédito usando o Checkout Transparente.
5. Pagamento com debito online usando o Checkout Transparente.
6. Pagamento com boleto bancário usando o Checkout Transparente.

== Changelog ==

= 3.16.7 - 1/Aug/2024 =
* Corrigido Warnings de propriedades dinâmicas em versões mais novas do PHP / Troca do banner convidando para usar o plugin novo

= 3.16.5 - 11/Jan/2024 =
* Atualização do readme.txt para direcionar você para a nova geração da integração.

= 3.16.4 - 28/Nov/2023=
* Adicionado suporte WP 6.4.1

= 3.16.3 - 11/Out/2023 =
* Re-correção do problema com logotipo que aparecia muito grande. Desta vez redimensionamos o svg pra facilitar e precisar de menos ajustes. (por Leonardo)

= 3.16.2 - 10/Out/2023 =
* Corrigido problema com o logotipo que aparecia muito grande em alguns checkouts/temas (Reportado por Lucca).

= 3.16.1 - 09/Out/2023 =
* Adicionamos um pequeno banner na área de configurações convidando você a conhecer nosso novo plugin.

= 3.16.0 - 21/Set/2023 =
* Agora, ao usar o Redirect você pode especificar quantas vezes o link de pagamento poderá ser usado. Se configurado com valor 1 evitará que uma notificação atrasada de uma outra tentativa sem sucesso altere o status de um pedido em processamento erroneamente. (Sugerido por Fabio Oliveira)
* Troca do logo por um mais recente em melhor resolução.
* Testado com WooCommerce 8.2 e WordPress 6.3.

= 3.15.2 - 3/Ago/2023 =
* Correção/compatibilidade: em versões antigas do Wordpress, um erro de <Forbidden> era exibido na finalização do pedido, mesmo com as credenciais corretas. O problema ocorria por causa de um bug no wordpress, mas contornamos por aqui. Reportado por Paulo da IdeiaFocus.

= 3.15.1 - 31/Jul/2023 =
* Correção de erro fatal na instalação do plugin (classe RM_PagSeguro\Connect nao encontrada)

= 3.15.0 - 31/Jul/2023 =
* Melhoria: agora é possível escolher entre 3 opções diferentes de configuração de parcelamento:
1. Obedecer regras configuradas na conta/painel PagSeguro (promoções salvas)
2. Assumir até X parcelas sem juros (configurável)
3. Definir valor mínimo da parcela sem juros. Ou seja, dependendo do valor da parcela, o lojista assume os juros.
* Melhoria: agora é possível definir quais meios de pagamentoe estarão disponíveis se o cliente for redirecionado para o PagSeguro.

= 3.14.0 - 11/Jul/2023 =
* Melhoria: agora é possível definir o título do meio de pagamento "Outras" quando o modo transparente é habilitado em conjunto com o modo redirect. (Sugerido por Vagner)

= 3.13.1 - 27/Jun/2023 =
* Pequena mudança na validade do checkout, para permitir que o tempo selecionado seja em minutos e não em horas.

= 3.13.0 - 27/Jun/2023 =
* Agora é possível definir a validade do link de checkout quando usamos pagamento Redirect. (Sugerido por Fabio Oliveira - Kaizen Digital)

= 3.12.0 - 23/Mai/2023 =
* Adicionada opção para não exibir o PagSeguro caso o cliente seja de outro país
* Nos casos onde a loja possui algum recurso para não exibir o campo País o módulo não era exibido. Agora, por padrão o módulo será exibido em todos os países a menos que marcado o contrário.

= 3.11.2 - 17/Mai/2023 =
* Adicionado compatibilidade com Wordpress 6.2 e WooCommerce 7.7.

= 3.11.1 - 08/Fev/2023 =
* Melhoria: a palavra "Redirecionar" foi trocada por "Outros" quando o modo redirect é habilitado em conjunto com transparente. Pequena melhoria estética sugerida por José Guedes.


= 3.11.0 - 31/Jan/2023 =
* Melhoria: agora é possível habilitar o meio "Redirecionar" em conjunto com o checkout transparente. Desta forma você pode oferecer uma opção de PIX (e outros meios) em conjunto com seu checkout transparente. Sugerido por José Guedes.
* Melhoria em algumas traduções e textos padrão. 

= 3.10.1 - 15/Jan/2023 =
* Corrigido: Mensagem de Chave Publica inválida era exibida em alguns casos mesmo quando a chave estava correta.

= 3.10.0 - 26/Dez/2022 =
* Adicionada validação da chave pública, visto que muitos usuários informam o token desnecessariamente ao configurar o módulo, fazendo com que o módulo não funcione.

= 3.9.2 - 14/Nov/2022 =
* Adicionado suporte ao Woocommerce 7.1

= 3.9.1 - 02/Nov/2022 =
* Adicionado suporte ao Wordpress 6.1

= 3.9.0 - 31/Set/2022 =
* Melhoria: você não precisa mais informar seu token PagSeguro nas configurações do módulo. No modelo de aplicação nunca precisamos do seu token, mas ele era solicitado e enviado mesmo assim e eventualmente causava confusão na hora de configurar o módulo.

= 3.8.1 - 23/Ago/2022 =
* Correção importante: O erro "Um erro ocorreu ao processar seu pagamento. Por favor tente novamente ou entre em contato conosco para obter ajuda." era exibido valor de parcela minima é especificado e o valor das parcelas mínimas sem juros é superior a 18 (ex: valor da parcela minima = 20 / valor do pedido = 500 / total de parcelas sem juros = 25) fazia o PagSeguro gerar um erro interno ocasionando esta mensagem e impedindo a compra. 

= 3.8.0 - 14/Ago/2022 =
* Melhoria: ano da validade do cartão agora pede apenas 2 dígitos
* Correção: ao especificar 0 (zero) como 'valor de parcela minima sem juros' fazia com que em algumas situações um erro genérico fosse exibido impedindo a finalização da venda. Na verdade, um erro de valor da parcela inválido era gerado por conta de outro problema relacionado a esta configuração.

= 3.7.0 - 25/Jul/2022 =
* Agora o processo de autorização para obtenção da chave pública pode ser feito nas configurações do módulo. Após autorizar a aplicação, sua chave pública será preenchida automaticamente no lugar certo.
* Remoção de debug esquecido

= 3.6.1 - 21/Jul/2022 =
* Adicionando PIX e Cartao Emergencial Caixa na lista de pagamentos. Desta forma, quando um pagamento com um desses meios for realizado no Redirect, o 'Meio de Pagamento' não mais ficará como 'Desconhecido'. (Reportado por Achiles)

= 3.6.0 - 21/Jun/2022 =
* Agora exibimos mais informações sobre o meio de pagamento na tela do pedido. Para pedidos com cartão, agora é possível ver o NSU, TID, código de autorização, e outras informações. Para pagamentos com Redirect, agora é possível ver qual meio de pagamento foi escolhido no PagSeguro.

= 3.5.3 - 10/Mai/2022 =
* Testes e suporte a Wordpress 6.0 e 6.1 adicionados
* Atualização de instruções no arquivo principal

= 3.5.2 - 16/Fev/2022 =
* Correção: Ao habilitar a sandbox, o campo "PagSeguro App Key" era exibido novamente como "Token PagSeguro", confundindo quem realizasse a configuração.


= 3.5.1 - 15/Fev/2022 =
* Melhoria: em algumas situações, quando a loja falha em receber uma das notificações do PagSeguro e/ou quando um pedido tem seu status alterado para 'Disponível' sem passar pelo status 'Pago' o pedido não tinha seu status atualizado. Agora se o pedido estiver como 'pendente' ou 'em espera' ele será atualizado com sucesso. (Reportado por Rodrigo Roncaglio)


= 3.5.0 - 8/Fev/2022 =
* Melhoria: agora é possível definir o valor da parcela mínima sem juros. Saiba mais em https://bit.ly/34927gh.


= 3.4.0 - 3/Fev/2022 =
* Adicionado suporte a Sandbox
* Melhorias e correções nas traduções incluindo remoção de arquivos de tradução não utilizados


= 3.3.9 - 14/Jan/2022 =
* Correção: suporte a atualização de pedidos feitos com PIX não tinha sido aplicada corretamente. Agora sim, pedidos com PIX serão atualizados automaticamente quando forem pagos.


= 3.3.8 - 23/Dec/2021 =
* Correção: suporte a atualização de pedidos feitos com PIX. Antes pedidos com PIX não eram atualizados automaticamente quando pagos.


= 3.3.7 - 11/Dec/2021 =
* Melhoria: quando uma mensagem de erro que não temos tradução vier do PagSeguro, mostraremos ela junto com a mensagem de erro padrão a fim de facilitar a resolução


= 3.3.6 - 29/Sep/2021 =
* Correção da constante com o número de versão do módulo. (São muitos lugares pra se alterar a versão no WP e esquecemos deste)


= 3.3.5 - 29/Sep/2021 =
* Compatibilidade: apenas atualizando notas de versão após teste de compatibilidade com Wordpress 5.8.1 e WooCommerce 5.7.1
* Melhoria: alteração em mensagens de erro com linguagem muito informal.

= 3.3.4 - 12/Mai/2021 =
* Melhoria: criamos um espelho (Cloudfront) dos arquivos estáticos do PagSeguro a fim de reduzir indisponibilidade que fazia com que os meios de pagamento transparente nunca terminassem de carregar no checkout de algumas lojas.

= 3.3.3 - 18/Jan/2021 =
* Correção: Erro "Undefined index: method in class-wc-pagseguro.php" aparecia em alguns casos após desativar e reativar o módulo.

= 3.3.2 - 6/Out/2020 =
* Correção: URL de sucesso não era enviado quando meio "Redirect" era usado fazendo com que o usuário não retornasse para loja.
 
= 3.3.1 - 14/Set/2020 =

* Correção do senderIp sendo enviado vazio quando não conseguia obter o senderIP, causando erro na finalização de pedidos
* Correção de undefined index method que ocorria após instalação do módulo, quando não se tinha os valores de configuração preenchidos e salvos 


= 3.3.0 - 07/Set/2020 = 

* Adicionado URL para auto diagnóstico, exibindo principais configurações do módulo
* Melhoria: Agora o IP do cliente é enviado ao PagSegurom, reduzindo transações negadas por suspeita de fraude
* Compatibilidade com Wordpress 5.5.1 e WooCommerce 4.4.1 verificada


= 3.2.7 - 15/Jun/2020 =

* Correção: pedidos feitos informando CNPJ com meio de pagamento Boleto não eram finalizados  


= 3.2.6 - 17/Mai/2020 =

* Módulo duplicado corrigido - O módulo aparecia duplicado para quem fazia a instalação/download pelo site do wordpress ao invés do github.  


= 3.2.5 - 11/Mai/2020 =

* Corrige falha na atualização automática de pedidos, pois os pedidos estavam sendo enviados sem o código de referência, e desta forma não conseguiam ser encontrados quando o PagSeguro tentava atualizá-los.


= 3.2.4 - 5/Mai/2020 =

* Corrige problema de pedidos com mais de 1 produto no checkout transparente ou Redirect que só enviava 1 produto
* Testado compatibilidade com WordPress 5.4 e WooCommerce 4.0


= 3.2.3 - 21/Nov/2019 =

* Corrige problema quando produtos ou cliente possuí acento no nome
* Adiciona headers com informações sobre a plataforma e versão do módulo

= 3.2.2 - 11/Nov/2019 =

* Corrige problema na URL de notificação enviada ao PagSeguro. Este bug impedia o PagSeguro de atualizar o status dos pedidos na loja, visto que a url de notificação era ignorada.

= 3.2.1 - 30/10/2019 =

* Corrige problema com finalização de pedido quando valor do frete é zero.

= 3.2.0 - 30/10/2019 =

* Agora o link para pagamento de boleto/tef aparece na página de sucesso
* Melhoria no esquema de tradução (espera-se que o marketplace agora exiba que o módulo é traduzido para o portugues)
* Correção nos logs que não exibiam o parâmetros sendo enviados ao pagseguro (mostrava 'Array')

= 3.x - 09/2019 =

* Versão no modelo de aplicação (por Ricardo Martins), que adiciona descontos às taxas do PagSeguro

= 2.13.1 - 2018/08/03 =

* Corrigido alerta do PHP sobre variável inexistente.

= 2.13.0 - 2018/08/02 =

* Força o campo "Bairro" como obrigatório quando checkout transparente está ativado.
* Implementada baixa automática de estoque ao comprar por boleto (funciona apenas no WooCommerce 3 ou superior).
* Estoque é restaurado automaticamente quando alguma notificação do PagSeguro marca o pedido como reembolsado ou cancelado (funciona apenas no WooCommerce 3 ou superior).
* Corrigida a mudança de status do pedido de Cancelado para Processando (funciona apenas no WooCommerce 3 ou superior).
* Adicionadas informações sobre a taxa de intermédio do PagSeguro nos meta dados do pedido.

= 2.12.7 - 2018/06/21 =

* Removido métodos do Itaú e Banrisul em débito online no checkout transparente, ambos não são mais suportados pelo PagSeguro.

= 2.12.6 - 2018/05/09 =

* Adicionado valor total ao parcelamento no checkout transparente para tornar mais prático para o cliente a visualização do juros. Mais detalhes em [#75](https://github.com/claudiosanches/woocommerce-pagseguro/pull/75).

= 2.12.5 - 2017/05/11 =

* Corrigido valor total das parcelas do cartão de crédito no checkout transparente, o valor tinha parado de ser atualizado no WooCommerce 3.0.

= 2.12.4 - 2017/04/12 =

* Corrigido icones no checkout transparente.

= 2.12.3 - 2017/04/10 =

* Corrigido `ndash` que aparecia no nome dos itens listados no PagSeguro.

= 2.12.2 - 2017/04/07 =

* Adicionado suporte ao novo sistema de logs do WooCommerce 3.0, assim permitindo que seja utilizado sistema de logs personalizados.
* Adicionado validação e higienização no código de transação do PagSeguro antes de salvar.

= 2.12.1 - 2017/04/04 =

* Correção dos títulos dos campos personalizados salvos ao fazer um pedido.

= 2.12.0 - 2017/04/03 =

* Adicionado suporte ao WooCommerce 3.0.
* Alterado o tipo dos campos para `tel` no Checkout Transparente. (Possível com a ajuda de [Thiago Guimarães](https://github.com/thiagogsr)).
* Correção nas máscaras do campos devido a mudança do plugin no [woocommerce-extra-checkout-fields-for-brazil](https://github.com/claudiosanches/woocommerce-extra-checkout-fields-for-brazil/pull/49). (Possível com a ajuda de [Thiago Guimarães](https://github.com/thiagogsr)).

== Upgrade Notice ==

= 2.13.1 =

* Força o campo "Bairro" como obrigatório quando checkout transparente está ativado.
* Implementada baixa automática de estoque ao comprar por boleto (funciona apenas no WooCommerce 3 ou superior).
* Estoque é restaurado automaticamente quando alguma notificação do PagSeguro marca o pedido como reembolsado ou cancelado (funciona apenas no WooCommerce 3 ou superior).
* Corrigida a mudança de status do pedido de Cancelado para Processando (funciona apenas no WooCommerce 3 ou superior).
* Adicionadas informações sobre a taxa de intermédio do PagSeguro nos meta dados do pedido.
