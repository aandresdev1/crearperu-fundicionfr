<?php
/**
 * Elemento WPBakery: History Timeline (Legado familiar).
 * Shortcode: [fr_history_timeline]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Historia (Timeline)', 'funrios' ),
        'base'     => 'fr_history_timeline',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-ui-clock',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Una empresa con legado familiar',
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Hitos', 'funrios' ),
                'param_name' => 'items',
                'value'      => urlencode( wp_json_encode( [
                    [ 'year' => '2010', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ],
                    [ 'year' => '2015', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ],
                    [ 'year' => '2020', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ],
                    [ 'year' => '2030', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Año', 'funrios' ),
                        'param_name' => 'year',
                    ],
                    [
                        'type'       => 'textarea',
                        'heading'    => __( 'Texto', 'funrios' ),
                        'param_name' => 'text',
                    ],
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Imagen inferior', 'funrios' ),
                        'param_name' => 'image',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_history_timeline', function ( $atts ) {
    $atts = shortcode_atts( [
        'title' => 'Una empresa con legado familiar',
        'items' => '',
    ], $atts, 'fr_history_timeline' );

    wp_enqueue_style( 'fr-history-timeline' );

    $items = [];
    if ( ! empty( $atts['items'] ) ) {
        $decoded = json_decode( urldecode( $atts['items'] ), true );
        if ( is_array( $decoded ) ) {
            $items = array_values( array_filter( $decoded, function ( $i ) {
                return ! empty( $i['year'] ) || ! empty( $i['text'] ) || ! empty( $i['image'] );
            } ) );
        }
    }
    if ( ! $items ) {
        $items = [
            [ 'year' => '2010', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'image' => '' ],
            [ 'year' => '2015', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'image' => '' ],
            [ 'year' => '2020', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'image' => '' ],
            [ 'year' => '2030', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'image' => '' ],
        ];
    }

    ob_start(); ?>
    <section class="fr-history">
        <div class="fr-container">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 class="fr-history__title"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>

            <?php if ( $items ) : ?>
                <div class="fr-history__grid" style="--fr-history-cols: <?php echo (int) max( 1, count( $items ) ); ?>;">
                    <?php foreach ( $items as $item ) :
                        $img_id  = isset( $item['image'] ) ? absint( $item['image'] ) : 0;
                        $img_src = $img_id ? wp_get_attachment_image_url( $img_id, 'medium_large' ) : ''; ?>
                        <article class="fr-history__item">
                            <div class="fr-history__copy">
                                <?php if ( ! empty( $item['year'] ) ) : ?>
                                    <span class="fr-history__year"><?php echo esc_html( $item['year'] ); ?></span>
                                <?php endif; ?>
                                <?php if ( ! empty( $item['text'] ) ) : ?>
                                    <p class="fr-history__text"><?php echo esc_html( $item['text'] ); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="fr-history__img" <?php if ( $img_src ) : ?>style="background-image:url('<?php echo esc_url( $img_src ); ?>');"<?php endif; ?>></div>
                        </article>
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
        'fr-history-timeline',
        get_template_directory_uri() . '/inc/vc-elements/history-timeline/history-timeline.css',
        [],
        '1.1'
    );
} );
