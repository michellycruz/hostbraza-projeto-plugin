=== Hostbraza Avisos ===
Contributors: michellycruz
Tags: avisos, hospedagem, notificações, whatsapp, hostbraza
Requires at least: 6.5
Tested up to: 6.5
Requires PHP: 8.0
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Exibe avisos de hospedagem (domínio, conta, disco) no painel e no site, com notificação flutuante para administradores e link direto para o WhatsApp do suporte.

== Description ==

O Hostbraza Avisos centraliza comunicados de hospedagem importantes dentro do WordPress do cliente. Cada aviso é um Custom Post Type, permitindo o uso das telas nativas do WordPress para criação e edição.

Os avisos são exibidos em dois lugares:

* Um banner no topo do painel administrativo, colorido conforme a severidade.
* Uma notificação flutuante (toast) no canto inferior direito do site, visível apenas para administradores, com botão de acesso ao WhatsApp do suporte.

O plugin funciona com cadastro manual de avisos e possui uma camada preparada para integração com uma API externa da Hostbraza, que pode ser ativada quando disponível. As duas fontes coexistem.

Principais recursos:

* Cadastro manual de avisos via Custom Post Type.
* Campos de tipo, severidade, data de vencimento e percentual de uso de disco.
* Banner administrativo com cor por severidade.
* Toast flutuante restrito a administradores, com fechamento que persiste durante o dia.
* Botão "Vamos resolver" com link para o WhatsApp do suporte.
* Campos expostos na API REST.
* Camada de integração com API externa (desativada por padrão).

== Installation ==

1. Faça o upload da pasta `hostbraza-avisos` para o diretório `/wp-content/plugins/`.
2. Ative o plugin pelo menu "Plugins" no painel do WordPress.
3. Acesse o novo menu "Avisos" para cadastrar avisos.

== Frequently Asked Questions ==

= Os avisos aparecem para todos os usuários do site? =

Não. As notificações flutuantes no site são exibidas apenas para usuários com permissão de administrador (manage_options).

= Preciso da API da Hostbraza para usar o plugin? =

Não. O plugin funciona com cadastro manual desde a instalação. A integração com a API é opcional e desativada por padrão.

= Como ativo a integração com a API? =

Edite as constantes de configuração no arquivo includes/class-avisos-fonte.php, conforme descrito na documentação técnica (README.md).

== Changelog ==

= 0.1.0 =
* Versão inicial.
* Custom Post Type de avisos com campos personalizados.
* Banner administrativo e toast no site.
* Camada de integração com API externa (desativada por padrão).
