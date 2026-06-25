<?php
/**
 * Plugin Name:       Hostbraza Avisos
 * Description:        Exibe avisos de hospedagem ao cliente e gera mensagens prontas de WhatsApp e email.
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

require_once plugin_dir_path( __FILE__ ) . 'includes/class-cpt.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-meta.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-avisos-fonte.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-admin-notice.php';