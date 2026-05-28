<?php
/**
 * Funrios theme — funciones base.
 *
 * Carga el soporte mínimo del tema y autoregistra los elementos WPBakery
 * que vivan en inc/vc-elements/<slug>/<slug>.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'FUNRIOS_THEME_DIR', get_template_directory() );
define( 'FUNRIOS_THEME_URI', get_template_directory_uri() );
define( 'FUNRIOS_VERSION', '1.0.0' );

/**
 * Soporte básico del tema.
 */
add_action( 'after_setup_theme', function () {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [ 'height' => 80, 'width' => 240, 'flex-height' => true, 'flex-width' => true ] );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( [
        'primary' => __( 'Menú principal', 'funrios' ),
        'footer'  => __( 'Menú footer (Empresa)', 'funrios' ),
    ] );
} );

/**
 * Estilo principal del tema (header obligatorio).
 */
add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'funrios-montserrat',
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
        [],
        null
    );
    wp_enqueue_style( 'funrios-style', get_stylesheet_uri(), [ 'funrios-montserrat' ], FUNRIOS_VERSION );
} );

/**
 * Autoload de elementos WPBakery.
 *
 * Cada elemento vive en inc/vc-elements/<slug>/<slug>.php y se registra a sí mismo
 * (vc_map + add_shortcode). Para agregar un elemento nuevo solo hay que crear la carpeta
 * con su <slug>.php — este loader lo incluye automáticamente.
 */
add_action( 'init', function () {
    if ( ! defined( 'WPB_VC_VERSION' ) ) {
        // WPBakery no está activo: no intentamos registrar nada.
        return;
    }

    $elements_dir = FUNRIOS_THEME_DIR . '/inc/vc-elements';
    if ( ! is_dir( $elements_dir ) ) {
        return;
    }

    foreach ( glob( $elements_dir . '/*', GLOB_ONLYDIR ) as $element_path ) {
        $slug = basename( $element_path );
        $file = $element_path . '/' . $slug . '.php';
        if ( file_exists( $file ) ) {
            require_once $file;
        }
    }
}, 5 );

/**
 * Hero opcional por página (metabox + render).
 */
require_once FUNRIOS_THEME_DIR . '/inc/page-hero-meta.php';

/**
 * Personalizador del tema: logo del header.
 */
add_action( 'customize_register', function ( $wp_customize ) {
    $wp_customize->add_section( 'funrios_identity', [
        'title'       => __( 'Funrios — Identidad', 'funrios' ),
        'description' => __( 'Logos y elementos visuales del tema Funrios.', 'funrios' ),
        'priority'    => 25,
    ] );

    $wp_customize->add_setting( 'funrios_header_logo', [
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'funrios_header_logo_ctrl', [
        'label'       => __( 'Logo del header', 'funrios' ),
        'description' => __( 'Aparece en la barra superior del sitio. Si lo dejas vacío se usa el logo de "Identidad del sitio", y si tampoco hay, el nombre del sitio como texto.', 'funrios' ),
        'section'     => 'funrios_identity',
        'settings'    => 'funrios_header_logo',
    ] ) );
} );

/**
 * Helper: devuelve la URL del logo del header (con fallback al custom_logo nativo).
 * Si no hay logo, devuelve '' y el caller renderiza el nombre del sitio.
 */
function fr_get_header_logo_url() {
    $url = get_theme_mod( 'funrios_header_logo', '' );
    if ( $url ) { return $url; }
    if ( has_custom_logo() ) {
        $logo_id = get_theme_mod( 'custom_logo' );
        $logo    = wp_get_attachment_image_src( $logo_id, 'full' );
        if ( $logo ) { return $logo[0]; }
    }
    return '';
}

/**
 * Aviso en el admin si WPBakery no está activo.
 */
add_action( 'admin_notices', function () {
    if ( defined( 'WPB_VC_VERSION' ) ) {
        return;
    }
    echo '<div class="notice notice-warning"><p><strong>Funrios:</strong> WPBakery Page Builder no está activo. Los elementos personalizados del tema no se cargarán.</p></div>';
} );
