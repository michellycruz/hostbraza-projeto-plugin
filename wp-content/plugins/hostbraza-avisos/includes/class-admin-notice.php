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

				<?php if ( 'disco' === $aviso['tipo'] && '' !== $aviso['percentual'] ) : ?>
					<?php
					$pct = (int) $aviso['percentual'];
					if ( $pct >= 90 ) {
						$cor = '#d63638';
					} elseif ( $pct >= 70 ) {
						$cor = '#dba617';
					} else {
						$cor = '#00a32a';
					}
					?>
					<div style="max-width:300px;margin-top:6px;">
						<div style="width:100%;height:5px;background:#e0e0e0;border-radius:5px;overflow:hidden;">
							<div style="width:<?php echo esc_attr( $pct ); ?>%;height:100%;background:<?php echo esc_attr( $cor ); ?>;border-radius:5px;"></div>
						</div>
						<span style="font-size:13px;color:#555;"><?php echo esc_html( $pct ); ?>% usado</span>
					</div>
				<?php elseif ( ! empty( $aviso['vencimento'] ) ) : ?>
					<br><em>Vencimento: <?php echo esc_html( $aviso['vencimento'] ); ?></em>
				<?php endif; ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'hbav_render_admin_notices' );