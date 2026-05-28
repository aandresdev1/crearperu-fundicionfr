<?php
/**
 * Elemento WPBakery: Contact Header (eyebrow + título + dirección).
 * Shortcode: [fr_contact_header]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Contacto: Header', 'funrios' ),
        'base'     => 'fr_contact_header',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-ui-text-loading',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'De Lunes a Viernes | 9AM - 7.00PM',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Comunícate con nosotros',
            ],
            [
                'type'       => 'textarea',
                'heading'    => __( 'Dirección', 'funrios' ),
                'param_name' => 'address',
                'value'      => 'Calle Los Productores 161 Urbanización Pro Industrial / San Martín de Porres / Lima 31 - Perú',
            ],
        ],
    ] );
} );

add_shortcode( 'fr_contact_header', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow' => 'De Lunes a Viernes | 9AM - 7.00PM',
        'title'   => 'Comunícate con nosotros',
        'address' => 'Calle Los Productores 161 Urbanización Pro Industrial / San Martín de Porres / Lima 31 - Perú',
    ], $atts, 'fr_contact_header' );

    ob_start(); ?>
    <section class="fr-contact-header" style="background:transparent;padding:60px 20px 20px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:780px;margin:0 auto;text-align:center;">
            <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                <p style="color:#e21f26;font-size:14px;font-weight:600;letter-spacing:.04em;margin:0 0 14px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 style="color:#1a1a1a;font-size:30px;font-weight:600;margin:0 0 16px;line-height:1.25;"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>
            <?php if ( ! empty( $atts['address'] ) ) : ?>
                <p style="color:#555;font-size:14px;line-height:1.7;margin:0;"><?php echo esc_html( $atts['address'] ); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );
