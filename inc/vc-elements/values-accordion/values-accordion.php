<?php
/**
 * Elemento WPBakery: Values Accordion (Nuestros Valores y más).
 * Shortcode: [fr_values_accordion]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Valores (Acordeón)', 'funrios' ),
        'base'     => 'fr_values_accordion',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-toggle-small-expand',
        'params'   => [
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
                'value'      => 'Nuestros Valores y más...',
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen lateral', 'funrios' ),
                'param_name' => 'image',
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Items', 'funrios' ),
                'param_name' => 'items',
                'value'      => urlencode( wp_json_encode( [
                    [ 'title' => 'Visión',         'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
                    [ 'title' => 'Misión',         'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
                    [ 'title' => 'Nuestros Pilares','content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
                    [ 'title' => 'Código de ética','content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Título', 'funrios' ),
                        'param_name' => 'title',
                    ],
                    [
                        'type'        => 'textarea',
                        'heading'     => __( 'Contenido', 'funrios' ),
                        'param_name'  => 'content',
                        'description' => __( 'Texto plano. Los saltos de línea se convierten en párrafos. Se aceptan etiquetas HTML básicas (<strong>, <em>, <a>, <br>).', 'funrios' ),
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_values_accordion', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow' => 'Sobre nosotros',
        'title'   => 'Nuestros Valores y más...',
        'image'   => '',
        'items'   => '',
    ], $atts, 'fr_values_accordion' );

    wp_enqueue_style( 'fr-values-accordion' );
    wp_enqueue_script( 'fr-values-accordion' );

    $items = [];
    if ( ! empty( $atts['items'] ) ) {
        $decoded = json_decode( urldecode( $atts['items'] ), true );
        if ( is_array( $decoded ) ) {
            $items = array_values( array_filter( $decoded, function ( $i ) {
                return ! empty( $i['title'] ) || ! empty( $i['content'] );
            } ) );
        }
    }
    if ( ! $items ) {
        $items = [
            [ 'title' => 'Visión',          'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>' ],
            [ 'title' => 'Misión',          'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
            [ 'title' => 'Nuestros Pilares','content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
            [ 'title' => 'Código de ética', 'content' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' ],
        ];
    }

    $img_src = $atts['image'] ? wp_get_attachment_image_url( absint( $atts['image'] ), 'large' ) : '';

    ob_start(); ?>
    <section class="fr-values" style="background:transparent;display:block;width:100%;box-sizing:border-box;">
        <div class="fr-values__inner" style="display:flex;flex-wrap:wrap;align-items:stretch;">
            <div class="fr-values__media" style="flex:1 1 360px;display:flex;overflow:hidden;background-color:transparent;">
                <?php if ( $img_src ) : ?>
                    <img class="fr-values__img" src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>" style="width:100%;height:auto;display:block;object-fit:contain;">
                <?php endif; ?>
            </div>
            <div class="fr-values__content" style="flex:1 1 360px;display:flex;align-items:center;padding:60px 5%;background:transparent;box-sizing:border-box;">
                <div class="fr-values__content-inner" style="width:100%;max-width:520px;">
                    <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                        <p class="fr-values__eyebrow" style="color:#e21f26;font-size:13px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin:0 0 10px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
                    <?php endif; ?>
                    <?php if ( ! empty( $atts['title'] ) ) : ?>
                        <h2 class="fr-values__title" style="color:#1a1a1a;font-size:28px;font-weight:600;margin:0 0 28px;line-height:1.25;"><?php echo esc_html( $atts['title'] ); ?></h2>
                    <?php endif; ?>

                    <?php if ( $items ) : ?>
                        <ul class="fr-values__list" style="list-style:none;margin:0;padding:0;display:flex;flex-direction:column;gap:14px;">
                            <?php foreach ( $items as $i => $item ) :
                                $is_open  = ( $i === 0 );
                                $head_bg  = $is_open ? '#e21f26' : '#f3f3f3';
                                $head_col = $is_open ? '#ffffff' : '#1a1a1a';
                                $icon_rot = $is_open ? 'rotate(-180deg)' : 'rotate(0deg)'; ?>
                                <li class="fr-values__item<?php echo $is_open ? ' is-open' : ''; ?>" style="background:transparent;list-style:none;">
                                    <button class="fr-values__head" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" style="width:100%;background:<?php echo $head_bg; ?>;color:<?php echo $head_col; ?>;border:0;border-radius:999px;padding:14px 24px;display:flex;align-items:center;justify-content:space-between;font-family:inherit;font-size:14px;font-weight:500;cursor:pointer;text-align:left;line-height:1.2;transition:background .2s,color .2s;">
                                        <span class="fr-values__label" style="flex:1;"><?php echo esc_html( $item['title'] ?? '' ); ?></span>
                                        <span class="fr-values__icon" aria-hidden="true" style="display:inline-flex;align-items:center;justify-content:center;width:20px;height:20px;color:currentColor;transition:transform .25s;margin-left:12px;flex-shrink:0;transform:<?php echo $icon_rot; ?>;">
                                            <svg viewBox="0 0 16 16" style="width:14px;height:14px;display:block;"><path d="M4 6l4 4 4-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </span>
                                    </button>
                                    <div class="fr-values__panel" style="overflow:hidden;box-sizing:border-box;background:#ffffff;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,.06);border:1px solid #eee;margin-top:<?php echo $is_open ? '10px' : '0'; ?>;<?php echo $is_open ? '' : 'max-height:0;'; ?>transition:max-height .35s ease, margin-top .35s ease;">
                                        <div class="fr-values__panel-inner" style="padding:22px 26px;font-size:13.5px;color:#555;line-height:1.7;">
                                            <?php echo wp_kses_post( wpautop( $item['content'] ?? '' ) ); ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-values-accordion',
        get_template_directory_uri() . '/inc/vc-elements/values-accordion/values-accordion.css',
        [],
        '1.5'
    );
    wp_register_script(
        'fr-values-accordion',
        get_template_directory_uri() . '/inc/vc-elements/values-accordion/values-accordion.js',
        [],
        '1.5',
        true
    );
} );
