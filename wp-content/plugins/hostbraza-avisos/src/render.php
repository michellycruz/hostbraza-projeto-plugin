<?php
/**
 * Renderização do bloco no front-end.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

if ( ! function_exists( 'hbav_get_avisos' ) ) {
	return;
}

$hbav_avisos = hbav_get_avisos();

if ( empty( $hbav_avisos ) ) {
	return;
}

$hbav_cores = array(
	'info'    => 'hbav-aviso--info',
	'atencao' => 'hbav-aviso--atencao',
	'urgente' => 'hbav-aviso--urgente',
);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<ul class="hbav-lista">
		<?php foreach ( $hbav_avisos as $hbav_aviso ) : ?>
			<?php
			$hbav_classe = isset( $hbav_cores[ $hbav_aviso['severidade'] ] )
				? $hbav_cores[ $hbav_aviso['severidade'] ]
				: 'hbav-aviso--info';

			// Define a cor da barra de disco conforme o percentual.
			$hbav_pct   = (int) $hbav_aviso['percentual'];
			$hbav_nivel = 'ok';
			if ( $hbav_pct >= 90 ) {
				$hbav_nivel = 'critico';
			} elseif ( $hbav_pct >= 70 ) {
				$hbav_nivel = 'alerta';
			}
			?>
			<li class="hbav-aviso <?php echo esc_attr( $hbav_classe ); ?>">
				<strong class="hbav-aviso__titulo">
					<?php echo esc_html( $hbav_aviso['titulo'] ); ?>
				</strong>
				<?php if ( ! empty( $hbav_aviso['mensagem'] ) ) : ?>
					<span class="hbav-aviso__mensagem">
						<?php echo esc_html( wp_strip_all_tags( $hbav_aviso['mensagem'] ) ); ?>
					</span>
				<?php endif; ?>

				<?php if ( 'disco' === $hbav_aviso['tipo'] && '' !== $hbav_aviso['percentual'] ) : ?>
					<div class="hbav-barra">
						<div class="hbav-barra__trilho">
							<div class="hbav-barra__preenchimento hbav-barra--<?php echo esc_attr( $hbav_nivel ); ?>"
								style="width: <?php echo esc_attr( $hbav_pct ); ?>%;"></div>
						</div>
						<span class="hbav-barra__rotulo"><?php echo esc_html( $hbav_pct ); ?>% usado</span>
					</div>
				<?php elseif ( ! empty( $hbav_aviso['vencimento'] ) ) : ?>
					<span class="hbav-aviso__vencimento">
						Vencimento: <?php echo esc_html( $hbav_aviso['vencimento'] ); ?>
					</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>
</div>