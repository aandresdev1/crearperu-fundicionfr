<?php
/**
 * Elemento WPBakery: About Split (imagen izquierda + bloque oscuro derecha).
 * Shortcode: [fr_about_split]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Sobre nosotros (Split)', 'funrios' ),
        'base'     => 'fr_about_split',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-single-image',
        'params'   => [
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen lateral', 'funrios' ),
                'param_name' => 'image',
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Logo superpuesto sobre la imagen (opcional)', 'funrios' ),
                'param_name' => 'overlay_logo',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'Sobre nosotros',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Fundición Ríos',
            ],
            [
                'type'       => 'textarea_html',
                'heading'    => __( 'Contenido', 'funrios' ),
                'param_name' => 'content',
                'value'      => '<p>Fundición Ríos S.A.C. es una empresa con más de 23 años de experiencia, especializada en la fabricación y comercialización de piezas fundidas en aleaciones de aceros comunes y especiales; así como, servicios relacionados.</p>',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Texto del botón', 'funrios' ),
                'param_name' => 'btn_label',
                'value'      => 'Ver más',
            ],
            [
                'type'       => 'vc_link',
                'heading'    => __( 'Link del botón', 'funrios' ),
                'param_name' => 'btn_link',
            ],
            [
                'type'        => 'dropdown',
                'heading'     => __( 'Posición de la imagen', 'funrios' ),
                'param_name'  => 'image_side',
                'value'       => [
                    __( 'Izquierda (imagen | texto)', 'funrios' ) => 'left',
                    __( 'Derecha (texto | imagen)', 'funrios' )   => 'right',
                ],
                'std'         => 'left',
            ],
            [
                'type'        => 'dropdown',
                'heading'     => __( 'Ancho de la sección', 'funrios' ),
                'param_name'  => 'width',
                'value'       => [
                    __( 'Completo (full-width al borde)', 'funrios' ) => 'full',
                    __( 'Contenido (centrado, max 1200px)', 'funrios' ) => 'contained',
                ],
                'std'         => 'full',
            ],
            [
                'type'        => 'dropdown',
                'heading'     => __( 'Fondo del bloque de texto', 'funrios' ),
                'param_name'  => 'theme',
                'value'       => [
                    __( 'Oscuro', 'funrios' )   => 'dark',
                    __( 'Claro', 'funrios' )    => 'light',
                ],
                'std'         => 'dark',
            ],
        ],
    ] );
} );

add_shortcode( 'fr_about_split', function ( $atts, $content = null ) {
    $atts = shortcode_atts( [
        'image'        => '',
        'overlay_logo' => '',
        'eyebrow'      => 'Sobre nosotros',
        'title'        => 'Fundición Ríos',
        'btn_label'    => 'Ver más',
        'btn_link'     => '',
        'reverse'      => '',     // legado (checkbox antiguo)
        'image_side'   => 'left',
        'width'        => 'full',
        'theme'        => 'dark',
    ], $atts, 'fr_about_split' );

    // Compatibilidad: el checkbox antiguo "reverse=yes" equivale a image_side=right.
    if ( $atts['reverse'] === 'yes' ) {
        $atts['image_side'] = 'right';
    }

    if ( empty( trim( wp_strip_all_tags( (string) $content ) ) ) ) {
        $content = '<p>Fundición Ríos S.A.C. es una empresa con más de 23 años de experiencia, especializada en la fabricación y comercialización de piezas fundidas en aleaciones de aceros comunes y especiales; así como, servicios relacionados.</p>';
    }

    wp_enqueue_style( 'fr-about-split' );

    $img_src     = $atts['image'] ? wp_get_attachment_image_url( absint( $atts['image'] ), 'large' ) : '';
    $overlay_src = $atts['overlay_logo'] ? wp_get_attachment_image_url( absint( $atts['overlay_logo'] ), 'medium' ) : '';

    $btn_url = '';
    $btn_target = '';
    if ( ! empty( $atts['btn_link'] ) && function_exists( 'vc_build_link' ) ) {
        $parsed     = vc_build_link( $atts['btn_link'] );
        $btn_url    = ! empty( $parsed['url'] ) ? $parsed['url'] : '';
        $btn_target = ! empty( $parsed['target'] ) ? trim( $parsed['target'] ) : '';
    }

    $classes = [ 'fr-about-split' ];
    if ( $atts['image_side'] === 'right' ) { $classes[] = 'fr-about-split--reverse'; }
    if ( $atts['theme'] === 'light' )      { $classes[] = 'fr-about-split--light'; }
    if ( $atts['width'] === 'contained' )  { $classes[] = 'fr-about-split--contained'; }
    $btn_class = $atts['theme'] === 'light' ? 'fr-btn--outline-dark' : 'fr-btn--outline-light';

    $is_contained = ( $atts['width'] === 'contained' );

    ob_start(); ?>
    <?php if ( $is_contained ) : ?><div class="fr-about-split-wrap" style="max-width:1200px;margin:0 auto;padding:0 20px;box-sizing:border-box;"><?php endif; ?>
    <section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
        <div class="fr-about-split__media">
            <?php if ( $img_src ) : ?>
                <img class="fr-about-split__img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>">
            <?php endif; ?>
            <?php if ( $overlay_src ) : ?>
                <img class="fr-about-split__overlay-logo" src="<?php echo esc_url( $overlay_src ); ?>" alt="">
            <?php endif; ?>
        </div>
        <div class="fr-about-split__content">
            <div class="fr-about-split__inner">
                <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                    <p class="fr-about-split__eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h2 class="fr-about-split__title"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php endif; ?>
                <div class="fr-about-split__text">
                    <?php echo wp_kses_post( wpb_js_remove_wpautop( $content, true ) ); ?>
                </div>
                <?php if ( ! empty( $atts['btn_label'] ) && $btn_url ) : ?>
                    <a class="fr-btn <?php echo esc_attr( $btn_class ); ?> fr-about-split__btn" href="<?php echo esc_url( $btn_url ); ?>"<?php if ( $btn_target ) : ?> target="<?php echo esc_attr( $btn_target ); ?>" rel="noopener"<?php endif; ?>>
                        <?php echo esc_html( $atts['btn_label'] ); ?> <span aria-hidden="true">→</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php if ( $is_contained ) : ?></div><?php endif; ?>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-about-split',
        get_template_directory_uri() . '/inc/vc-elements/about-split/about-split.css',
        [],
        '1.3'
    );
} );
