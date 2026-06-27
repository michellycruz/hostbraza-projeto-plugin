<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hbav_register_aviso_cpt() {

	$labels = array(
		'name'          => 'Avisos',
		'singular_name' => 'Aviso',
		'add_new_item'  => 'Adicionar novo aviso',
		'edit_item'     => 'Editar aviso',
		'all_items'     => 'Todos os avisos',
		'menu_name'     => 'Avisos',
	);

	$args = array(
		'labels'       => $labels,
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-megaphone',
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
		'has_archive'  => false,
		'publicly_queryable' => false,
		'exclude_from_search'=> true,
	);

	register_post_type( 'hbav_aviso', $args );
}
add_action( 'init', 'hbav_register_aviso_cpt' );