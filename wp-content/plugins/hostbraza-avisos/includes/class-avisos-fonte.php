<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Busca todos os avisos ativos.
 *
 * Hoje lê do banco local (CPT). No futuro, esta é a única função
 * que precisará mudar para ler de uma API externa.
 *
 * @return array Lista de avisos, cada um como array associativo.
 */
function hbav_get_avisos() {

	$query = new WP_Query(
		array(
			'post_type'      => 'hbav_aviso',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		)
	);

	$avisos = array();

	foreach ( $query->posts as $post ) {
		$avisos[] = array(
			'id'         => $post->ID,
			'titulo'     => $post->post_title,
			'mensagem'   => $post->post_content,
			'tipo'       => get_post_meta( $post->ID, '_hbav_tipo', true ),
			'severidade' => get_post_meta( $post->ID, '_hbav_severidade', true ),
			'vencimento' => get_post_meta( $post->ID, '_hbav_vencimento', true ),
		);
	}

	return $avisos;
}