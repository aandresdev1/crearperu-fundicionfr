<?php
/**
 * Elemento WPBakery: Presence Map (Presencia internacional).
 * Shortcode: [fr_presence_map]
 *
 * El mapa se sube como imagen (PNG/SVG con los marcadores ya dibujados).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Presencia (Mapa)', 'funrios' ),
        'base'     => 'fr_presence_map',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-map-pin',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'Hacemos presencia en',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Presencia Internacional',
            ],
            [
                'type'       => 'textarea',
                'heading'    => __( 'Texto', 'funrios' ),
                'param_name' => 'text',
                'value'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen del mapa', 'funrios' ),
                'param_name' => 'map_image',
                'description'=> __( 'Sube la imagen del mapa con los marcadores ya dibujados (PNG o SVG).', 'funrios' ),
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Países (lista opcional bajo el texto)', 'funrios' ),
                'param_name' => 'countries',
                'value'      => urlencode( wp_json_encode( [
                    [ 'name' => 'Perú' ],
                    [ 'name' => 'Chile' ],
                    [ 'name' => 'Bolivia' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Nombre', 'funrios' ),
                        'param_name' => 'name',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_presence_map', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow'   => 'Hacemos presencia en',
        'title'     => 'Presencia Internacional',
        'text'      => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
        'map_image' => '',
        'countries' => '',
    ], $atts, 'fr_presence_map' );

    wp_enqueue_style( 'fr-presence-map' );

    $countries = [];
    if ( ! empty( $atts['countries'] ) ) {
        $decoded = json_decode( urldecode( $atts['countries'] ), true );
        if ( is_array( $decoded ) ) {
            // Filtrar entradas vacías que WPBakery puede generar
            $countries = array_values( array_filter( $decoded, function ( $c ) {
                return ! empty( $c['name'] );
            } ) );
        }
    }
    if ( ! $countries ) {
        $countries = [
            [ 'name' => 'Perú' ],
            [ 'name' => 'Chile' ],
            [ 'name' => 'Bolivia' ],
            [ 'name' => 'Ecuador' ],
            [ 'name' => 'Colombia' ],
        ];
    }

    $map_src = $atts['map_image'] ? wp_get_attachment_image_url( absint( $atts['map_image'] ), 'full' ) : '';

    ob_start(); ?>
    <section class="fr-presence" style="background:transparent;color:#e5e5e5;padding:70px 20px;display:block;width:100%;box-sizing:border-box;">
        <div class="fr-presence__inner" style="max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:40px;">
            <div class="fr-presence__text" style="flex:1 1 320px;min-width:0;">
                <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                    <p style="color:#e21f26;font-size:13px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin:0 0 10px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h2 style="color:#ffffff;font-size:30px;font-weight:600;margin:0 0 18px;line-height:1.25;"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $atts['text'] ) ) : ?>
                    <p style="color:#b8b8b8;font-size:14px;line-height:1.75;margin:0 0 18px;max-width:420px;"><?php echo esc_html( $atts['text'] ); ?></p>
                <?php endif; ?>
                <?php if ( $countries ) : ?>
                    <ul style="list-style:none;margin:0;padding:0;display:flex;flex-wrap:wrap;gap:6px 18px;">
                        <?php foreach ( $countries as $c ) :
                            if ( empty( $c['name'] ) ) continue; ?>
                            <li style="font-size:13px;color:#ffffff;position:relative;padding-left:14px;line-height:1.6;">
                                <span style="position:absolute;left:0;top:8px;width:8px;height:8px;border-radius:50%;background:#e21f26;display:block;"></span>
                                <?php echo esc_html( $c['name'] ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="fr-presence__map" style="flex:1 1 420px;min-width:0;">
                <?php if ( $map_src ) : ?>
                    <img src="<?php echo esc_url( $map_src ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>" style="width:100%;height:auto;display:block;">
                <?php else : ?>
                    <div style="aspect-ratio:16/9;background:#2a2a2a;border:1px dashed #444;display:flex;align-items:center;justify-content:center;color:#666;font-size:12px;">[ Sube una imagen de mapa en el elemento ]</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-presence-map',
        get_template_directory_uri() . '/inc/vc-elements/presence-map/presence-map.css',
        [],
        '1.2'
    );
} );
