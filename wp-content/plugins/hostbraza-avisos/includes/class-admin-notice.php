<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Exibe os avisos como tarjas no topo do painel.
 */
function hbav_render_admin_notices() {

	$avisos = hbav_get_avisos();

	if ( empty( $avisos ) ) {
		return;
	}

	// Mapeia a severidade para a classe de cor nativa do WordPress.
	$classes_severidade = array(
		'info'    => 'notice-info',
		'atencao' => 'notice-warning',
		'urgente' => 'notice-error',
	);

	foreach ( $avisos as $aviso ) {

		$classe = isset( $classes_severidade[ $aviso['severidade'] ] )
			? $classes_severidade[ $aviso['severidade'] ]
			: 'notice-info';

		?>
		<div class="notice <?php echo esc_attr( $classe ); ?>">
			<p>
				<strong><?php echo esc_html( $aviso['titulo'] ); ?></strong>
				<?php if ( ! empty( $aviso['mensagem'] ) ) : ?>
					<br><?php echo esc_html( wp_strip_all_tags( $aviso['mensagem'] ) ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $aviso['vencimento'] ) ) : ?>
					<br><em>Vencimento: <?php echo esc_html( $aviso['vencimento'] ); ?></em>
				<?php endif; ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'hbav_render_admin_notices' );