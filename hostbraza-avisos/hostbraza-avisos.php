<?php
/**
 * Plugin Name:       Hostbraza Avisos
 * Description:       Exibe avisos de hospedagem (domínio, conta, disco) no painel e no site, com notificação para administradores e link direto para o WhatsApp do suporte.
 * Version:           0.1.0
 * Requires at least: 6.5
 * Requires PHP:      8.0
 * Author:            Michelly Cruz
 * Text Domain:       hostbraza-avisos
 */

// Impede acesso direto ao arquivo.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'includes/cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/avisos-fonte.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin-notice.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/meta-rest.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/toast.php';