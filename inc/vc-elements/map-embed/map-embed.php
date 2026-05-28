<?php
/**
 * Elemento WPBakery: Map Embed (Google Maps via iframe).
 * Shortcode: [fr_map_embed]
 *
 * El campo "src" usa textarea_raw_html (WPBakery lo guarda en base64),
 * así no se rompen los caracteres especiales del iframe / URL del mapa.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Mapa (Google)', 'funrios' ),
        'base'     => 'fr_map_embed',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-map-pin',
        'params'   => [
            [
                'type'        => 'textarea_raw_html',
                'heading'     => __( 'URL del embed o código <iframe>', 'funrios' ),
                'param_name'  => 'src',
                'description' => __( 'Pega la URL "src" del iframe de Google Maps (Compartir → Insertar mapa → copia el src del iframe) o el código <iframe>...</iframe> completo.', 'funrios' ),
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Altura (px)', 'funrios' ),
                'param_name' => 'height',
                'value'      => '420',
            ],
            [
                'type'       => 'dropdown',
                'heading'    => __( 'Bordes redondeados', 'funrios' ),
                'param_name' => 'rounded',
                'value'      => [
                    __( 'No', 'funrios' )  => 'no',
                    __( 'Sí', 'funrios' )  => 'yes',
                ],
                'std'        => 'no',
            ],
        ],
    ] );
} );

add_shortcode( 'fr_map_embed', function ( $atts ) {
    $atts = shortcode_atts( [
        'src'     => '',
        'height'  => '420',
        'rounded' => 'no',
    ], $atts, 'fr_map_embed' );

    // textarea_raw_html llega base64-encoded. Lo decodificamos con el helper de WPBakery
    // si está disponible; si no, hacemos el fallback manual (rawurldecode + base64_decode).
    $raw = (string) $atts['src'];
    if ( $raw ) {
        if ( function_exists( 'vc_value_from_safe' ) ) {
            $raw = vc_value_from_safe( $raw );
        } else {
            $decoded = base64_decode( $raw, true );
            if ( $decoded !== false ) {
                $raw = rawurldecode( $decoded );
            }
        }
    }
    $raw = trim( $raw );

    $height  = max( 200, absint( $atts['height'] ) );
    $radius  = ( $atts['rounded'] === 'yes' ) ? '12px' : '0';
    $iframe_html = '';

    if ( $raw ) {
        if ( stripos( $raw, '<iframe' ) !== false ) {
            // Iframe completo: lo aceptamos limpiando atributos peligrosos.
            $iframe_html = wp_kses( $raw, [
                'iframe' => [
                    'src'             => true,
                    'width'           => true,
                    'height'          => true,
                    'style'           => true,
                    'allowfullscreen' => true,
                    'loading'         => true,
                    'referrerpolicy'  => true,
                    'frameborder'     => true,
                    'allow'           => true,
                ],
            ] );
        } else {
            // Solo URL: construimos el iframe.
            $src = esc_url( $raw );
            $iframe_html = sprintf(
                '<iframe src="%s" width="100%%" height="%d" style="border:0;" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
                esc_attr( $src ),
                $height
            );
        }
    }

    ob_start(); ?>
    <section class="fr-map-embed" style="background:transparent;padding:20px 20px 60px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="position:relative;width:100%;overflow:hidden;border-radius:<?php echo esc_attr( $radius ); ?>;background:#e8e8e8;height:<?php echo (int) $height; ?>px;">
                <?php if ( $iframe_html ) : ?>
                    <div style="position:absolute;inset:0;">
                        <?php
                        // Forzar width/height al 100% sobre el iframe insertado para que llene el contenedor.
                        echo preg_replace(
                            [ '/\swidth="\d+"/i', '/\sheight="\d+"/i' ],
                            [ ' width="100%"',     ' height="100%"' ],
                            $iframe_html
                        );
                        ?>
                    </div>
                <?php else : ?>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#666;font-size:13px;">[ Pega el src del iframe de Google Maps en el elemento ]</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );
