<?php
/**
 * Elemento WPBakery: Contact Bar (banda con teléfono y email).
 * Shortcode: [fr_contact_bar]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Banda de Contacto', 'funrios' ),
        'base'     => 'fr_contact_bar',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-ui-button',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Teléfono', 'funrios' ),
                'param_name' => 'phone',
                'value'      => '993 296 871',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Email', 'funrios' ),
                'param_name' => 'email',
                'value'      => 'andres.cuadros@funrios.pe',
            ],
        ],
    ] );
} );

add_shortcode( 'fr_contact_bar', function ( $atts ) {
    $atts = shortcode_atts( [
        'phone' => '993 296 871',
        'email' => 'andres.cuadros@funrios.pe',
    ], $atts, 'fr_contact_bar' );

    $phone_link = $atts['phone'] ? 'tel:' . preg_replace( '/\s+/', '', $atts['phone'] ) : '';

    ob_start(); ?>
    <section class="fr-contact-bar" style="background:#e21f26;color:#ffffff;padding:14px 20px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:30px 50px;">
            <?php if ( ! empty( $atts['phone'] ) ) : ?>
                <a href="<?php echo esc_attr( $phone_link ); ?>" style="display:inline-flex;align-items:center;gap:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:500;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.18);">
                        <svg viewBox="0 0 24 24" style="width:13px;height:13px;fill:#ffffff;display:block;"><path d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.4-.1-.8 0-1.1.3l-2.2 2.2c-2.8-1.4-5.1-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.3-1.1C8.7 6.4 8.5 5.2 8.5 4c0-.5-.5-1-1-1H4c-.5 0-1 .5-1 1 0 9.4 7.6 17 17 17 .5 0 1-.5 1-1v-3.5c0-.5-.5-1-1-1z"/></svg>
                    </span>
                    <?php echo esc_html( $atts['phone'] ); ?>
                </a>
            <?php endif; ?>
            <?php if ( ! empty( $atts['email'] ) ) : ?>
                <a href="mailto:<?php echo esc_attr( $atts['email'] ); ?>" style="display:inline-flex;align-items:center;gap:10px;color:#ffffff;text-decoration:none;font-size:14px;font-weight:500;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:rgba(255,255,255,.18);">
                        <svg viewBox="0 0 24 24" style="width:13px;height:13px;fill:#ffffff;display:block;"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                    </span>
                    <?php echo esc_html( $atts['email'] ); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );
