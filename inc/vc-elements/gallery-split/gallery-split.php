<?php
/**
 * Elemento WPBakery: Gallery Split (texto arriba + 2 imágenes lado a lado).
 * Shortcode: [fr_gallery_split]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Galería Split', 'funrios' ),
        'base'     => 'fr_gallery_split',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-images-stack',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'Industria Minera',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => '"Fundimos soluciones e impulsamos la minería."',
            ],
            [
                'type'       => 'textarea',
                'heading'    => __( 'Descripción', 'funrios' ),
                'param_name' => 'description',
                'value'      => 'Cada pieza que producimos refleja nuestro compromiso con la calidad, eficiencia y el desarrollo sostenible de la minería.',
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen izquierda', 'funrios' ),
                'param_name' => 'image_left',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Etiqueta imagen izquierda (opcional)', 'funrios' ),
                'param_name' => 'label_left',
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen derecha', 'funrios' ),
                'param_name' => 'image_right',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Etiqueta imagen derecha (opcional)', 'funrios' ),
                'param_name' => 'label_right',
            ],
        ],
    ] );
} );

add_shortcode( 'fr_gallery_split', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow'     => 'Industria Minera',
        'title'       => '"Fundimos soluciones e impulsamos la minería."',
        'description' => 'Cada pieza que producimos refleja nuestro compromiso con la calidad, eficiencia y el desarrollo sostenible de la minería.',
        'image_left'  => '',
        'label_left'  => '',
        'image_right' => '',
        'label_right' => '',
    ], $atts, 'fr_gallery_split' );

    $left_src  = $atts['image_left']  ? wp_get_attachment_image_url( absint( $atts['image_left'] ),  'large' ) : '';
    $right_src = $atts['image_right'] ? wp_get_attachment_image_url( absint( $atts['image_right'] ), 'large' ) : '';

    ob_start(); ?>
    <section class="fr-gallery-split" style="background:transparent;padding:70px 20px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="text-align:center;margin-bottom:40px;">
                <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                    <p style="color:#e21f26;font-size:13px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin:0 0 12px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
                <?php endif; ?>
                <?php if ( ! empty( $atts['title'] ) ) : ?>
                    <h2 style="color:#1a1a1a;font-size:28px;font-weight:600;margin:0 0 14px;line-height:1.3;"><?php echo esc_html( $atts['title'] ); ?></h2>
                <?php endif; ?>
                <?php if ( ! empty( $atts['description'] ) ) : ?>
                    <p style="color:#555;font-size:14px;line-height:1.7;margin:0 auto;max-width:680px;"><?php echo esc_html( $atts['description'] ); ?></p>
                <?php endif; ?>
            </div>

            <div class="fr-gallery-split__grid" style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <?php
                $cells = [
                    [ 'src' => $left_src,  'label' => $atts['label_left']  ],
                    [ 'src' => $right_src, 'label' => $atts['label_right'] ],
                ];
                foreach ( $cells as $cell ) : ?>
                    <div style="position:relative;border-radius:10px;overflow:hidden;background:transparent;">
                        <?php if ( $cell['src'] ) : ?>
                            <img src="<?php echo esc_url( $cell['src'] ); ?>" alt="" style="width:100%;height:auto;display:block;">
                        <?php else : ?>
                            <div style="aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;color:#888;font-size:12px;border:1px dashed rgba(0,0,0,.15);border-radius:10px;">[ Sube una imagen ]</div>
                        <?php endif; ?>
                        <?php if ( ! empty( $cell['label'] ) ) : ?>
                            <span style="position:absolute;bottom:12px;right:12px;background:#e21f26;color:#fff;font-size:11px;font-weight:600;padding:6px 12px;border-radius:3px;letter-spacing:.04em;"><?php echo esc_html( $cell['label'] ); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <style>
        @media (max-width: 700px) {
            .fr-gallery-split__grid { grid-template-columns: 1fr !important; }
        }
        </style>
    </section>
    <?php
    return ob_get_clean();
} );
