<?php
/**
 * Bộ icon SVG (nét 1.8, 24x24) dùng chung cho trang Dịch vụ, Trang chủ.
 * Dùng: echo saybencode_icon( 'search' );
 */
if ( ! defined( 'ABSPATH' ) ) exit;

function saybencode_icon( $name, $size = 24 ) {
    static $paths = null;
    if ( null === $paths ) {
        $paths = array(
            'search'    => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
            'pen'       => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>',
            'users'     => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20a6.5 6.5 0 0 1 13 0"/><path d="M16 4.5a3.5 3.5 0 0 1 0 7"/><path d="M18 14.5a6.5 6.5 0 0 1 3.5 5.5"/>',
            'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/>',
            'monitor'   => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
            'phone'     => '<rect x="7" y="2.5" width="10" height="19" rx="2"/><path d="M11 18.5h2"/>',
            'database'  => '<ellipse cx="12" cy="5.5" rx="8" ry="3"/><path d="M4 5.5v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/><path d="M4 11.5v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>',
            'zap'       => '<path d="M13 2L4 14h7l-1 8 9-12h-7z"/>',
            'video'     => '<rect x="2.5" y="6" width="13" height="12" rx="2"/><path d="M15.5 10.5l6-3.5v10l-6-3.5"/>',
            'camera'    => '<path d="M4 8h3l2-3h6l2 3h3a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/><circle cx="12" cy="13" r="3.5"/>',
            'palette'   => '<path d="M12 3a9 9 0 1 0 0 18c1.5 0 2-1 1.5-2.2-.6-1.3.2-2.3 1.5-2.3H17a4 4 0 0 0 4-4C21 6.7 17 3 12 3z"/><circle cx="7.5" cy="11" r="1"/><circle cx="10" cy="7" r="1"/><circle cx="15" cy="7.5" r="1"/>',
            'megaphone' => '<path d="M3 10v4a1 1 0 0 0 1 1h3l8 4V5L7 9H4a1 1 0 0 0-1 1z"/><path d="M19 9a4 4 0 0 1 0 6"/>',
            'bot'       => '<rect x="4" y="8" width="16" height="12" rx="3"/><path d="M12 8V4.5M9 13v1.5M15 13v1.5M8.5 17.5h7"/><circle cx="12" cy="3.5" r="1"/>',
            'workflow'  => '<rect x="3" y="3" width="6" height="6" rx="1"/><rect x="15" y="15" width="6" height="6" rx="1"/><path d="M9 6h3a3 3 0 0 1 3 3v6"/>',
            'file'      => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/>',
            'message'   => '<path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12z"/>',
            'image'     => '<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="1.6"/><path d="M21 16l-5-5-9 9"/>',
            'cart'      => '<circle cx="9" cy="20" r="1.3"/><circle cx="18" cy="20" r="1.3"/><path d="M2.5 3.5h3l2.5 12h11l2-8H6.5"/>',
            'chart'     => '<path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/>',
            'layout'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/>',
            'check'     => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
            'arrow'     => '<path d="M7 17L17 7M8 7h9v9"/>',
        );
    }
    if ( ! isset( $paths[ $name ] ) ) return '';
    return sprintf(
        '<svg class="icon icon-%1$s" viewBox="0 0 24 24" width="%2$d" height="%2$d" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
        esc_attr( $name ), (int) $size, $paths[ $name ]
    );
}
