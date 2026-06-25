import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useEntityRecords } from '@wordpress/core-data';
import './editor.scss';

export default function Edit() {
	const blockProps = useBlockProps();

	const { records, isResolving } = useEntityRecords(
		'postType',
		'hbav_aviso',
		{ per_page: -1, status: 'publish' }
	);

	const cores = {
		info: 'hbav-aviso--info',
		atencao: 'hbav-aviso--atencao',
		urgente: 'hbav-aviso--urgente',
	};

	// Decide a cor da barra de disco conforme o percentual.
	const nivelBarra = ( pct ) => {
		if ( pct >= 90 ) {
			return 'hbav-barra--critico';
		}
		if ( pct >= 70 ) {
			return 'hbav-barra--alerta';
		}
		return 'hbav-barra--ok';
	};

	if ( isResolving ) {
		return (
			<div { ...blockProps }>
				<p>{ __( 'Carregando avisos…', 'hostbraza-avisos' ) }</p>
			</div>
		);
	}

	if ( ! records || records.length === 0 ) {
		return (
			<div { ...blockProps }>
				<p>{ __( 'Nenhum aviso cadastrado ainda.', 'hostbraza-avisos' ) }</p>
			</div>
		);
	}

	return (
		<div { ...blockProps }>
			<ul className="hbav-lista">
				{ records.map( ( aviso ) => {
					const severidade = aviso.hbav_severidade || 'info';
					const classe = cores[ severidade ] || 'hbav-aviso--info';
					const ehDisco =
						aviso.hbav_tipo === 'disco' &&
						aviso.hbav_percentual !== '';
					const pct = parseInt( aviso.hbav_percentual, 10 ) || 0;

					return (
						<li key={ aviso.id } className={ `hbav-aviso ${ classe }` }>
							<strong className="hbav-aviso__titulo">
								{ aviso.title.rendered }
							</strong>

							{ ehDisco ? (
								<div className="hbav-barra">
									<div className="hbav-barra__trilho">
										<div
											className={ `hbav-barra__preenchimento ${ nivelBarra( pct ) }` }
											style={ { width: `${ pct }%` } }
										></div>
									</div>
									<span className="hbav-barra__rotulo">
										{ pct }% { __( 'usado', 'hostbraza-avisos' ) }
									</span>
								</div>
							) : (
								aviso.hbav_vencimento && (
									<span className="hbav-aviso__vencimento">
										{ __( 'Vencimento:', 'hostbraza-avisos' ) }{ ' ' }
										{ aviso.hbav_vencimento }
									</span>
								)
							) }
						</li>
					);
				} ) }
			</ul>
		</div>
	);
}