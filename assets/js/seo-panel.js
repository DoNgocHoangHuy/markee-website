/* Panel "SEO" trong thanh bên của trình soạn thảo khối: Tiêu đề SEO + Mô tả meta + xem trước Google. */
( function ( wp, cfg ) {
	if ( ! wp || ! wp.plugins || ! cfg ) return;
	var el = wp.element.createElement;
	var Panel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || ( wp.editPost && wp.editPost.PluginDocumentSettingPanel );
	if ( ! Panel ) return;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;

	function counter( len, min, max ) {
		var color = len === 0 ? '#757575' : ( len > max ? '#cc1818' : ( len < min ? '#b26200' : '#007017' ) );
		return el( 'span', { style: { color: color, fontWeight: 600 } }, len + ' ký tự (nên ' + min + '–' + max + ')' );
	}

	function SeoPanel() {
		var data = wp.data.useSelect( function ( select ) {
			var ed = select( 'core/editor' );
			return {
				meta: ed.getEditedPostAttribute( 'meta' ) || {},
				title: ed.getEditedPostAttribute( 'title' ) || '',
				excerpt: ed.getEditedPostAttribute( 'excerpt' ) || '',
				slug: ed.getEditedPostAttribute( 'slug' ) || ''
			};
		}, [] );
		var editPost = wp.data.useDispatch( 'core/editor' ).editPost;
		function set( key, value ) {
			var m = {}; m[ key ] = value; editPost( { meta: m } );
		}
		var seoTitle = data.meta[ cfg.titleK ] || '';
		var seoDesc = data.meta[ cfg.descK ] || '';
		var shownTitle = ( seoTitle || data.title || 'Tiêu đề trang' ) ;
		var shownDesc = seoDesc || data.excerpt || 'Chưa có mô tả meta. Google sẽ tự lấy một đoạn trong bài.';

		return el( Panel, { name: 'saybencode-seo', title: 'SEO — Tiêu đề & Mô tả meta', className: 'saybencode-seo-panel' },
			el( 'div', { style: { border: '1px solid #dcdcde', borderRadius: 6, padding: 10, marginBottom: 12, background: '#fff' } },
				el( 'div', { style: { color: '#4d5156', fontSize: 11 } }, cfg.host + ' › ' + ( data.slug || '…' ) ),
				el( 'div', { style: { color: '#1a0dab', fontSize: 16, lineHeight: 1.3, margin: '2px 0' } }, shownTitle.slice( 0, 65 ) + ( shownTitle.length > 65 ? '…' : '' ) ),
				el( 'div', { style: { color: '#4d5156', fontSize: 12, lineHeight: 1.4 } }, shownDesc.slice( 0, 160 ) + ( shownDesc.length > 160 ? '…' : '' ) )
			),
			el( TextControl, {
				label: 'Tiêu đề SEO',
				value: seoTitle,
				placeholder: data.title,
				onChange: function ( v ) { set( cfg.titleK, v ); },
				help: counter( seoTitle.length, 30, 60 ),
				__nextHasNoMarginBottom: true
			} ),
			el( 'div', { style: { height: 12 } } ),
			el( TextareaControl, {
				label: 'Mô tả meta (Meta Description)',
				value: seoDesc,
				rows: 4,
				onChange: function ( v ) { set( cfg.descK, v ); },
				help: counter( seoDesc.length, 120, 160 ),
				__nextHasNoMarginBottom: true
			} )
		);
	}

	wp.plugins.registerPlugin( 'saybencode-seo', { render: SeoPanel, icon: 'search' } );
} )( window.wp, window.saybSeo );
