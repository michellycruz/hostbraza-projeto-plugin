<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// 1. Registra a meta box na tela de edição do aviso.
function hbav_add_meta_boxes() {
	add_meta_box(
		'hbav_detalhes',
		'Detalhes do aviso',
		'hbav_render_meta_box',
		'hbav_aviso',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'hbav_add_meta_boxes' );
// 2. Desenha o conteúdo da meta box (os campos).
function hbav_render_meta_box( $post ) {
	wp_nonce_field( 'hbav_salvar_detalhes', 'hbav_nonce' );
	$tipo       = get_post_meta( $post->ID, '_hbav_tipo', true );
	$severidade = get_post_meta( $post->ID, '_hbav_severidade', true );
	$vencimento = get_post_meta( $post->ID, '_hbav_vencimento', true );
	$percentual = get_post_meta( $post->ID, '_hbav_percentual', true );
	$tipos = array(
		'dominio' => 'Domínio expirando',
		'conta'   => 'Conta a vencer',
		'disco'   => 'Disco cheio',
	);
	$severidades = array(
		'info'     => 'Informativo',
		'atencao'  => 'Atenção',
		'urgente'  => 'Urgente',
	);
	?>
	<p>
		<label for="hbav_tipo"><strong>Tipo do aviso</strong></label><br>
		<select name="hbav_tipo" id="hbav_tipo">
			<?php foreach ( $tipos as $valor => $rotulo ) : ?>
				<option value="<?php echo esc_attr( $valor ); ?>" <?php selected( $tipo, $valor ); ?>>
					<?php echo esc_html( $rotulo ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="hbav_severidade"><strong>Severidade</strong></label><br>
		<select name="hbav_severidade" id="hbav_severidade">
			<?php foreach ( $severidades as $valor => $rotulo ) : ?>
				<option value="<?php echo esc_attr( $valor ); ?>" <?php selected( $severidade, $valor ); ?>>
					<?php echo esc_html( $rotulo ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</p>
	<p>
		<label for="hbav_vencimento"><strong>Data de vencimento</strong></label><br>
		<input type="date" name="hbav_vencimento" id="hbav_vencimento" value="<?php echo esc_attr( $vencimento ); ?>">
	</p>
	<p>
		<label for="hbav_percentual"><strong>Uso de disco (%)</strong></label><br>
		<input type="number" name="hbav_percentual" id="hbav_percentual"
			min="0" max="100" step="1"
			value="<?php echo esc_attr( $percentual ); ?>">
		<br><small>Preencha apenas para avisos do tipo "Disco cheio".</small>
	</p>
	<?php
}
// 3. Salva os dados com segurança.
function hbav_salvar_meta( $post_id ) {
	// Verifica o nonce.
	if ( ! isset( $_POST['hbav_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['hbav_nonce'] ), 'hbav_salvar_detalhes' ) ) {
		return;
	}
	// Não salva durante autosave.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Confere permissão do usuário.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// Tipo: sanitiza e só aceita valores da lista permitida.
	$tipos_validos = array( 'dominio', 'conta', 'disco' );
	if ( isset( $_POST['hbav_tipo'] ) ) {
		$tipo = sanitize_text_field( wp_unslash( $_POST['hbav_tipo'] ) );
		if ( in_array( $tipo, $tipos_validos, true ) ) {
			update_post_meta( $post_id, '_hbav_tipo', $tipo );
		}
	}
	// Severidade: idem.
	$sev_validas = array( 'info', 'atencao', 'urgente' );
	if ( isset( $_POST['hbav_severidade'] ) ) {
		$severidade = sanitize_text_field( wp_unslash( $_POST['hbav_severidade'] ) );
		if ( in_array( $severidade, $sev_validas, true ) ) {
			update_post_meta( $post_id, '_hbav_severidade', $severidade );
		}
	}
	// Vencimento: sanitiza como texto simples.
	if ( isset( $_POST['hbav_vencimento'] ) ) {
		update_post_meta( $post_id, '_hbav_vencimento', sanitize_text_field( wp_unslash( $_POST['hbav_vencimento'] ) ) );
	}
	// Percentual: garante número inteiro entre 0 e 100.
	if ( isset( $_POST['hbav_percentual'] ) && '' !== $_POST['hbav_percentual'] ) {
		$percentual = absint( wp_unslash( $_POST['hbav_percentual'] ) );
		$percentual = min( 100, $percentual );
		update_post_meta( $post_id, '_hbav_percentual', $percentual );
	} else {
		delete_post_meta( $post_id, '_hbav_percentual' );
	}
}
add_action( 'save_post_hbav_aviso', 'hbav_salvar_meta' );