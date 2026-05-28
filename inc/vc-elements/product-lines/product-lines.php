<?php
/**
 * Elemento WPBakery: Product Lines (Líneas de producción).
 * Shortcode: [fr_product_lines]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Líneas de Producción', 'funrios' ),
        'base'     => 'fr_product_lines',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-images-stack',
        'params'   => [
            [
                'type'        => 'textfield',
                'heading'     => __( 'Eyebrow', 'funrios' ),
                'param_name'  => 'eyebrow',
                'value'       => 'NUESTROS PRODUCTOS',
            ],
            [
                'type'        => 'textfield',
                'heading'     => __( 'Título', 'funrios' ),
                'param_name'  => 'title',
                'value'       => 'Lineas de Producción para la Industria',
            ],
            [
                'type'        => 'param_group',
                'heading'     => __( 'Cards', 'funrios' ),
                'param_name'  => 'cards',
                'value'       => urlencode( wp_json_encode( [
                    [ 'label' => 'INDUSTRIA CEMENTERA', 'link' => '#' ],
                    [ 'label' => 'INDUSTRIA GRAN MINERÍA', 'link' => '#' ],
                    [ 'label' => 'INDUSTRIA MEDIANA MINERÍA', 'link' => '#' ],
                ] ) ),
                'params'      => [
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Imagen', 'funrios' ),
                        'param_name' => 'image',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Etiqueta', 'funrios' ),
                        'param_name' => 'label',
                    ],
                    [
                        'type'       => 'vc_link',
                        'heading'    => __( 'Link', 'funrios' ),
                        'param_name' => 'link',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_product_lines', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow' => 'NUESTROS PRODUCTOS',
        'title'   => 'Lineas de Producción para la Industria',
        'cards'   => '',
    ], $atts, 'fr_product_lines' );

    wp_enqueue_style( 'fr-product-lines' );

    $cards = [];
    if ( ! empty( $atts['cards'] ) ) {
        $decoded = json_decode( urldecode( $atts['cards'] ), true );
        if ( is_array( $decoded ) ) {
            $cards = array_values( array_filter( $decoded, function ( $c ) {
                return ! empty( $c['label'] ) || ! empty( $c['image'] );
            } ) );
        }
    }
    if ( ! $cards ) {
        $cards = [
            [ 'image' => '', 'label' => 'INDUSTRIA CEMENTERA',       'link' => '' ],
            [ 'image' => '', 'label' => 'INDUSTRIA GRAN MINERÍA',    'link' => '' ],
            [ 'image' => '', 'label' => 'INDUSTRIA MEDIANA MINERÍA', 'link' => '' ],
        ];
    }

    ob_start(); ?>
    <section class="fr-product-lines">
        <div class="fr-container">
            <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                <p class="fr-product-lines__eyebrow"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 class="fr-product-lines__title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <?php if ( $cards ) : ?>
                <div class="fr-product-lines__grid">
                    <?php foreach ( $cards as $card ) :
                        $img_id  = isset( $card['image'] ) ? absint( $card['image'] ) : 0;
                        $img_src = $img_id ? wp_get_attachment_image_url( $img_id, 'large' ) : '';
                        $link    = '';
                        $target  = '';
                        if ( ! empty( $card['link'] ) ) {
                            $parsed = function_exists( 'vc_build_link' ) ? vc_build_link( $card['link'] ) : [];
                            if ( ! empty( $parsed['url'] ) ) {
                                $link   = $parsed['url'];
                                $target = ! empty( $parsed['target'] ) ? trim( $parsed['target'] ) : '';
                            }
                        }
                        $tag = $link ? 'a' : 'div';
                        ?>
                        <<?php echo $tag; ?> class="fr-product-lines__card"<?php if ( $link ) : ?> href="<?php echo esc_url( $link ); ?>"<?php if ( $target ) : ?> target="<?php echo esc_attr( $target ); ?>" rel="noopener"<?php endif; endif; ?>>
                            <div class="fr-product-lines__img" <?php if ( $img_src ) : ?>style="background-image:url('<?php echo esc_url( $img_src ); ?>');"<?php endif; ?>></div>
                            <?php if ( ! empty( $card['label'] ) ) : ?>
                                <span class="fr-product-lines__label"><?php echo esc_html( $card['label'] ); ?></span>
                            <?php endif; ?>
                        </<?php echo $tag; ?>>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-product-lines',
        get_template_directory_uri() . '/inc/vc-elements/product-lines/product-lines.css',
        [],
        '1.1'
    );
} );
