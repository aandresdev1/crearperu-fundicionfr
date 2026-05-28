<?php
/**
 * Elemento WPBakery: Process Flow (texto arriba + imagen debajo).
 * Shortcode: [fr_process_flow]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Proceso (Flujo)', 'funrios' ),
        'base'     => 'fr_process_flow',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-application-icon-large',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'Proceso productivo',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Calidad garantizada en cada paso',
            ],
            [
                'type'       => 'textarea',
                'heading'    => __( 'Descripción', 'funrios' ),
                'param_name' => 'description',
                'value'      => 'El proceso de fabricación se inicia con la concepción de la pieza a fundir, plasmada en los planos de la misma que luego de verificados se envían a modelaría para la fabricación del modelo; paralelamente nuestra área de ingeniería efectúa el diseño de colada.',
            ],
            [
                'type'        => 'attach_image',
                'heading'     => __( 'Imagen del diagrama', 'funrios' ),
                'param_name'  => 'image',
                'description' => __( 'Imagen del flujo de proceso (PNG/SVG con todos los pasos y flechas).', 'funrios' ),
            ],
        ],
    ] );
} );

add_shortcode( 'fr_process_flow', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow'     => 'Proceso productivo',
        'title'       => 'Calidad garantizada en cada paso',
        'description' => 'El proceso de fabricación se inicia con la concepción de la pieza a fundir, plasmada en los planos de la misma que luego de verificados se envían a modelaría para la fabricación del modelo; paralelamente nuestra área de ingeniería efectúa el diseño de colada.',
        'image'       => '',
    ], $atts, 'fr_process_flow' );

    $img_src = $atts['image'] ? wp_get_attachment_image_url( absint( $atts['image'] ), 'full' ) : '';

    ob_start(); ?>
    <section class="fr-process" style="background:transparent;padding:70px 20px 80px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:40px;">
                <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                    <p style="color:#e21f26;font-size:13px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin:0 0 12px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h2 style="color:#ffffff;font-size:30px;font-weight:600;margin:0 0 18px;line-height:1.25;"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p style="color:#b8b8b8;font-size:14px;line-height:1.7;margin:0 auto;max-width:780px;"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="fr-process__image" style="text-align:center;">
                <?php if ( $img_src ) : ?>
                    <img src="<?php echo esc_url( $img_src ); ?>" alt="<?php echo esc_attr( $atts['title'] ); ?>" style="max-width:100%;height:auto;display:inline-block;">
                <?php else : ?>
                    <div style="background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.18);border-radius:8px;padding:60px 20px;color:#666;font-size:13px;">[ Sube la imagen del diagrama de proceso ]</div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );
