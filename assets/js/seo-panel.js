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

	function norm( str ) {
		return ( str || '' ).toLowerCase().normalize( 'NFD' ).replace( /[\u0300-\u036f]/g, '' ).replace( /đ/g, 'd' ).replace( /\s+/g, ' ' ).trim();
	}
	function check( ok, label ) {
		return el( 'li', { style: { margin: '2px 0', color: ok ? '#007017' : '#b26200' } }, ( ok ? '✓ ' : '✗ ' ) + label );
	}

	function SeoPanel() {
		var data = wp.data.useSelect( function ( select ) {
			var ed = select( 'core/editor' );
			return {
				meta: ed.getEditedPostAttribute( 'meta' ) || {},
				title: ed.getEditedPostAttribute( 'title' ) || '',
				excerpt: ed.getEditedPostAttribute( 'excerpt' ) || '',
				content: ed.getEditedPostAttribute( 'content' ) || '',
				slug: ed.getEditedPostAttribute( 'slug' ) || ''
			};
		}, [] );
		var editPost = wp.data.useDispatch( 'core/editor' ).editPost;
		function set( key, value ) {
			var m = {}; m[ key ] = value; editPost( { meta: m } );
		}
		var seoTitle = data.meta[ cfg.titleK ] || '';
		var seoDesc = data.meta[ cfg.descK ] || '';
		var kw = data.meta[ cfg.kwK ] || '';
		var nk = norm( kw );
		var text = norm( ( data.content || '' ).replace( /<[^>]+>/g, ' ' ) ).split( ' ' ).slice( 0, 100 ).join( ' ' );
		var slugText = norm( ( data.slug || '' ).replace( /-/g, ' ' ) );
		var checks = nk ? el( 'ul', { style: { margin: '8px 0 0', padding: 0, listStyle: 'none', fontSize: 12 } },
			check( norm( seoTitle || data.title ).indexOf( nk ) !== -1, 'Từ khóa có trong tiêu đề' ),
			check( norm( seoDesc ).indexOf( nk ) !== -1, 'Từ khóa có trong mô tả meta' ),
			check( slugText.indexOf( nk ) !== -1, 'Từ khóa có trong đường dẫn (slug)' ),
			check( text.indexOf( nk ) !== -1, 'Từ khóa có trong 100 từ đầu của bài' )
		) : null;
		var shownTitle = ( seoTitle || data.title || 'Tiêu đề trang' ) ;
		var shownDesc = seoDesc || data.excerpt || 'Chưa có mô tả meta. Google sẽ tự lấy một đoạn trong bài.';

		return el( Panel, { name: 'saybencode-seo', title: 'SEO — Tiêu đề & Mô tả meta', className: 'saybencode-seo-panel' },
			el( 'div', { style: { border: '1px solid #dcdcde', borderRadius: 6, padding: 10, marginBottom: 12, background: '#fff' } },
				el( 'div', { style: { color: '#4d5156', fontSize: 11 } }, cfg.host + ' › ' + ( data.slug || '…' ) ),
				el( 'div', { style: { color: '#1a0dab', fontSize: 16, lineHeight: 1.3, margin: '2px 0' } }, shownTitle.slice( 0, 65 ) + ( shownTitle.length > 65 ? '…' : '' ) ),
				el( 'div', { style: { color: '#4d5156', fontSize: 12, lineHeight: 1.4 } }, shownDesc.slice( 0, 160 ) + ( shownDesc.length > 160 ? '…' : '' ) )
			),
			el( TextControl, {
				label: 'Từ khóa chính',
				value: kw,
				placeholder: 'vd: dịch vụ viết content',
				onChange: function ( v ) { set( cfg.kwK, v ); },
				help: checks || 'Nhập 1 cụm từ khóa để xem checklist.',
				__nextHasNoMarginBottom: true
			} ),
			el( 'div', { style: { height: 12 } } ),
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
