<?php
/**
 * Elemento WPBakery: Clients Carousel (logos de clientes con flechas).
 * Shortcode: [fr_clients_carousel]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Clientes (Carrusel)', 'funrios' ),
        'base'     => 'fr_clients_carousel',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-images-carousel',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Ellos confían en nosotros',
            ],
            [
                'type'       => 'dropdown',
                'heading'    => __( 'Logos visibles por slide (desktop)', 'funrios' ),
                'param_name' => 'per_slide',
                'value'      => [ '4' => '4', '3' => '3', '5' => '5' ],
                'std'        => '4',
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Logos', 'funrios' ),
                'param_name' => 'logos',
                'value'      => urlencode( wp_json_encode( [
                    [ 'image' => '', 'link' => '' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Logo', 'funrios' ),
                        'param_name' => 'image',
                    ],
                    [
                        'type'       => 'vc_link',
                        'heading'    => __( 'Link (opcional)', 'funrios' ),
                        'param_name' => 'link',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_clients_carousel', function ( $atts ) {
    $atts = shortcode_atts( [
        'title'     => 'Ellos confían en nosotros',
        'per_slide' => '4',
        'logos'     => '',
    ], $atts, 'fr_clients_carousel' );

    wp_enqueue_style( 'fr-clients-carousel' );
    wp_enqueue_script( 'fr-clients-carousel' );

    $logos = [];
    if ( ! empty( $atts['logos'] ) ) {
        $decoded = json_decode( urldecode( $atts['logos'] ), true );
        if ( is_array( $decoded ) ) {
            $logos = array_values( array_filter( $decoded, function ( $l ) {
                return ! empty( $l['image'] );
            } ) );
        }
    }

    $per = max( 1, absint( $atts['per_slide'] ) );

    ob_start(); ?>
    <section class="fr-clients">
        <div class="fr-container">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 class="fr-clients__title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <div class="fr-clients__carousel" data-per-slide="<?php echo esc_attr( $per ); ?>">
                <button class="fr-clients__nav fr-clients__nav--prev" type="button" aria-label="Anterior">‹</button>
                <div class="fr-clients__viewport">
                    <ul class="fr-clients__track">
                        <?php foreach ( $logos as $logo ) :
                            $img_id  = isset( $logo['image'] ) ? absint( $logo['image'] ) : 0;
                            if ( ! $img_id ) continue;
                            $img_src = wp_get_attachment_image_url( $img_id, 'medium' );
                            $url     = '';
                            $target  = '';
                            if ( ! empty( $logo['link'] ) && function_exists( 'vc_build_link' ) ) {
                                $parsed = vc_build_link( $logo['link'] );
                                $url    = ! empty( $parsed['url'] ) ? $parsed['url'] : '';
                                $target = ! empty( $parsed['target'] ) ? trim( $parsed['target'] ) : '';
                            }
                            ?>
                            <li class="fr-clients__item">
                                <?php if ( $url ) : ?>
                                    <a href="<?php echo esc_url( $url ); ?>"<?php if ( $target ) : ?> target="<?php echo esc_attr( $target ); ?>" rel="noopener"<?php endif; ?>>
                                        <img src="<?php echo esc_url( $img_src ); ?>" alt="">
                                    </a>
                                <?php else : ?>
                                    <img src="<?php echo esc_url( $img_src ); ?>" alt="">
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <button class="fr-clients__nav fr-clients__nav--next" type="button" aria-label="Siguiente">›</button>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-clients-carousel',
        get_template_directory_uri() . '/inc/vc-elements/clients-carousel/clients-carousel.css',
        [],
        '1.1'
    );
    wp_register_script(
        'fr-clients-carousel',
        get_template_directory_uri() . '/inc/vc-elements/clients-carousel/clients-carousel.js',
        [],
        '1.1',
        true
    );
} );
