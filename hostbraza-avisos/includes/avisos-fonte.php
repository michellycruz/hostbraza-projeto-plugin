<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* =========================================================================
 * CONFIGURAÇÃO DA API DA HOSTBRAZA
 * -------------------------------------------------------------------------
 * Quando a API existir, preencha os valores abaixo e mude HBAV_API_ATIVA
 * para true. Enquanto estiver false, o plugin usa apenas o cadastro manual.
 * ========================================================================= */

if ( ! defined( 'HBAV_API_ATIVA' ) ) {
	define( 'HBAV_API_ATIVA', false ); // <-- Mude para true quando a API estiver pronta.
}

if ( ! defined( 'HBAV_API_URL' ) ) {
	define( 'HBAV_API_URL', 'https://api.hostbraza.com.br/avisos' ); // <-- CONFIGURE AQUI: endereço da API.
}

if ( ! defined( 'HBAV_API_TOKEN' ) ) {
	define( 'HBAV_API_TOKEN', '' ); // <-- CONFIGURE AQUI: chave/token de autenticação.
}

if ( ! defined( 'HBAV_API_CLIENTE_ID' ) ) {
	define( 'HBAV_API_CLIENTE_ID', '' ); // <-- CONFIGURE AQUI: identificador deste cliente na Hostbraza.
}


/**
 * FONTE PRINCIPAL: junta os avisos manuais e os da API.
 *
 * O resto do plugin chama SÓ esta função. Ela decide de onde vêm os dados.
 *
 * @return array Lista unificada de avisos.
 */
function hbav_get_avisos() {

	$manuais = hbav_get_avisos_manuais();
	$da_api  = array();

	if ( HBAV_API_ATIVA ) {
		$da_api = hbav_get_avisos_api();
	}

	// Junta as duas listas (manuais primeiro, depois os da API).
	return array_merge( $manuais, $da_api );
}


/**
 * FONTE 1: avisos cadastrados manualmente no WordPress (CPT).
 *
 * @return array
 */
function hbav_get_avisos_manuais() {

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
			'percentual' => get_post_meta( $post->ID, '_hbav_percentual', true ),
		);
	}

	return $avisos;
}


/**
 * FONTE 2: avisos vindos da API da Hostbraza.
 *
 * Esta função só roda quando HBAV_API_ATIVA é true. Ela faz a chamada HTTP,
 * trata erros e converte a resposta para o mesmo formato dos avisos manuais.
 *
 * @return array
 */
function hbav_get_avisos_api() {

	// 1. Tenta usar o cache primeiro (evita chamar a API a cada carregamento).
	$cache = get_transient( 'hbav_avisos_api' );
	if ( false !== $cache ) {
		return $cache;
	}

	// 2. Monta a requisição com autenticação.
	$resposta = wp_remote_get(
		add_query_arg( 'cliente', HBAV_API_CLIENTE_ID, HBAV_API_URL ),
		array(
			'timeout' => 10,
			'headers' => array(
				'Authorization' => 'Bearer ' . HBAV_API_TOKEN,
				'Accept'        => 'application/json',
			),
		)
	);

	// 3. Se deu erro de conexão, não quebra o site: retorna lista vazia.
	if ( is_wp_error( $resposta ) ) {
		return array();
	}

	// 4. Se a API não respondeu 200 (OK), também ignora.
	if ( 200 !== wp_remote_retrieve_response_code( $resposta ) ) {
		return array();
	}

	// 5. Lê e decodifica o JSON.
	$corpo = wp_remote_retrieve_body( $resposta );
	$dados = json_decode( $corpo, true );

	if ( ! is_array( $dados ) ) {
		return array();
	}

	/* ---------------------------------------------------------------------
	 * CONVERSÃO: adapte este trecho ao formato real que a API devolver.
	 *
	 * Aqui assumimos que a API retorna algo como:
	 * [
	 *   { "titulo": "...", "tipo": "disco", "severidade": "urgente",
	 *     "vencimento": "2026-06-30", "percentual": 94 },
	 *   ...
	 * ]
	 *
	 * Se os nomes dos campos da API forem diferentes, ajuste o mapeamento.
	 * ------------------------------------------------------------------- */
	$avisos = array();

	foreach ( $dados as $item ) {
		// Gera um ID estável: usa o id da API se houver; senão, cria uma
		// "impressão digital" do conteúdo para que o mesmo aviso tenha
		// sempre o mesmo ID (necessário para o "fechar pelo dia" do toast).
		if ( isset( $item['id'] ) ) {
			$id = 'api-' . $item['id'];
		} else {
			$assinatura = ( $item['titulo'] ?? '' ) . '|' . ( $item['tipo'] ?? '' ) . '|' . ( $item['vencimento'] ?? '' );
			$id         = 'api-' . md5( $assinatura );
		}

		$avisos[] = array(
			'id'         => $id,
			'titulo'     => $item['titulo'] ?? '',
			'mensagem'   => $item['mensagem'] ?? '',
			'tipo'       => $item['tipo'] ?? '',
			'severidade' => $item['severidade'] ?? 'info',
			'vencimento' => $item['vencimento'] ?? '',
			'percentual' => isset( $item['percentual'] ) ? (string) $item['percentual'] : '',
		);
	}

	// 6. Guarda no cache por 15 minutos.
	set_transient( 'hbav_avisos_api', $avisos, 15 * MINUTE_IN_SECONDS );

	return $avisos;
}