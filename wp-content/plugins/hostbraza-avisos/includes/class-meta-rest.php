<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Expõe os campos do aviso na API REST, sob nomes públicos sem underscore.
 */
function hbav_registrar_meta_rest() {

	// nome público => nome real no banco
	$campos = array(
		'hbav_tipo'       => '_hbav_tipo',
		'hbav_severidade' => '_hbav_severidade',
		'hbav_vencimento' => '_hbav_vencimento',
		'hbav_percentual' => '_hbav_percentual',
	);

	foreach ( $campos as $publico => $real ) {
		register_rest_field(
			'hbav_aviso',
			$publico,
			array(
				'get_callback' => function ( $post ) use ( $real ) {
					return get_post_meta( $post['id'], $real, true );
				},
				'schema'       => array(
					'type' => 'string',
				),
			)
		);
	}
}
add_action( 'rest_api_init', 'hbav_registrar_meta_rest' );