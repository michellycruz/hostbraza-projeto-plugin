<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Número do WhatsApp do suporte (só dígitos: país + DDD + número).
if ( ! defined( 'HBAV_WHATSAPP' ) ) {
	define( 'HBAV_WHATSAPP', '5527928346514' );
}

/**
 * Injeta os toasts de aviso no rodapé do site, apenas para administradores.
 */
function hbav_render_toasts() {

	// Só administradores do site veem os avisos.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$avisos = hbav_get_avisos();

	if ( empty( $avisos ) ) {
		return;
	}

	$cores = array(
		'info'    => '#2271b1',
		'atencao' => '#dba617',
		'urgente' => '#d63638',
	);
	?>
	<div id="hbav-toasts" class="hbav-toasts">
		<?php foreach ( $avisos as $aviso ) : ?>
			<?php
			$cor = isset( $cores[ $aviso['severidade'] ] ) ? $cores[ $aviso['severidade'] ] : '#2271b1';

			// Detalhe: % de disco ou vencimento.
			$detalhe = '';
			if ( 'disco' === $aviso['tipo'] && '' !== $aviso['percentual'] ) {
				$detalhe = (int) $aviso['percentual'] . '% de disco usado';
			} elseif ( ! empty( $aviso['vencimento'] ) ) {
				$detalhe = 'Vencimento: ' . $aviso['vencimento'];
			}

			// Link do WhatsApp com mensagem pré-preenchida.
			$mensagem = 'Olá! Preciso de ajuda com: ' . $aviso['titulo'];
			$link     = 'https://wa.me/' . HBAV_WHATSAPP . '?text=' . rawurlencode( $mensagem );
			?>
			<div class="hbav-toast" data-id="<?php echo esc_attr( $aviso['id'] ); ?>" style="border-left-color: <?php echo esc_attr( $cor ); ?>;">
				<button class="hbav-toast__fechar" aria-label="Fechar">&times;</button>
				<strong class="hbav-toast__titulo"><?php echo esc_html( $aviso['titulo'] ); ?></strong>
				<?php if ( $detalhe ) : ?>
					<span class="hbav-toast__detalhe"><?php echo esc_html( $detalhe ); ?></span>
				<?php endif; ?>
				<a class="hbav-toast__botao" href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener">
					Vamos resolver
				</a>
			</div>
		<?php endforeach; ?>
	</div>

	<style>
		.hbav-toasts {
			position: fixed;
			bottom: 20px;
			right: 20px;
			display: flex;
			flex-direction: column;
			gap: 12px;
			z-index: 99999;
			max-width: 320px;
		}
		.hbav-toast {
			position: relative;
			background: #fff;
			border-left: 4px solid #2271b1;
			border-radius: 8px;
			box-shadow: 0 4px 16px rgba(0,0,0,0.15);
			padding: 14px 16px;
			display: flex;
			flex-direction: column;
			gap: 6px;
			font-size: 14px;
			color: #1e1e1e;
		}
		.hbav-toast__fechar {
			position: absolute;
			top: 6px;
			right: 8px;
			border: none;
			background: none;
			font-size: 20px;
			line-height: 1;
			cursor: pointer;
			color: #777;
		}
		.hbav-toast__titulo {
			font-weight: 600;
			padding-right: 18px;
		}
		.hbav-toast__detalhe {
			color: #555;
			font-size: 13px;
		}
		.hbav-toast__botao {
			display: inline-block;
			margin-top: 4px;
			padding: 7px 12px;
			background: #25d366;
			color: #fff;
			text-decoration: none;
			border-radius: 6px;
			font-weight: 600;
			text-align: center;
			width: fit-content;
		}
	</style>

	<script>
		( function () {
			var hoje = new Date().toISOString().slice( 0, 10 ); // AAAA-MM-DD
			document.querySelectorAll( '.hbav-toast' ).forEach( function ( toast ) {
				var id    = toast.getAttribute( 'data-id' );
				var chave = 'hbav_fechado_' + id;
				// Se já foi fechado hoje, esconde.
				if ( localStorage.getItem( chave ) === hoje ) {
					toast.style.display = 'none';
				}
				// Ao clicar no X, esconde e lembra a data.
				toast.querySelector( '.hbav-toast__fechar' ).addEventListener( 'click', function () {
					toast.style.display = 'none';
					localStorage.setItem( chave, hoje );
				} );
			} );
		} )();
	</script>
	<?php
}
add_action( 'wp_footer', 'hbav_render_toasts' );