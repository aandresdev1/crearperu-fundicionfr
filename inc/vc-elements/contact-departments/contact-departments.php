<?php
/**
 * Elemento WPBakery: Contact Departments (grid de departamentos con teléfono y email).
 * Shortcode: [fr_contact_departments]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Contacto: Departamentos', 'funrios' ),
        'base'     => 'fr_contact_departments',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-ui-tab',
        'params'   => [
            [
                'type'       => 'param_group',
                'heading'    => __( 'Departamentos', 'funrios' ),
                'param_name' => 'items',
                'value'      => urlencode( wp_json_encode( [
                    [ 'name' => 'Industria Cementera',       'phone' => '987 096 237', 'email' => 'oscar.guerra@funrios.pe' ],
                    [ 'name' => 'Industria Gran Minería',    'phone' => '993 296 871', 'email' => 'andres.cuadros@funrios.pe' ],
                    [ 'name' => 'Industria Mediana Minería', 'phone' => '937 722 884', 'email' => 'nombre.linea@funrios.pe' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Nombre', 'funrios' ),
                        'param_name' => 'name',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Teléfono', 'funrios' ),
                        'param_name' => 'phone',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Email', 'funrios' ),
                        'param_name' => 'email',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_contact_departments', function ( $atts ) {
    $atts = shortcode_atts( [
        'items' => '',
    ], $atts, 'fr_contact_departments' );

    $items = [];
    if ( ! empty( $atts['items'] ) ) {
        $decoded = json_decode( urldecode( $atts['items'] ), true );
        if ( is_array( $decoded ) ) {
            $items = array_values( array_filter( $decoded, function ( $i ) {
                return ! empty( $i['name'] ) || ! empty( $i['phone'] ) || ! empty( $i['email'] );
            } ) );
        }
    }
    if ( ! $items ) {
        $items = [
            [ 'name' => 'Industria Cementera',       'phone' => '987 096 237', 'email' => 'oscar.guerra@funrios.pe' ],
            [ 'name' => 'Industria Gran Minería',    'phone' => '993 296 871', 'email' => 'andres.cuadros@funrios.pe' ],
            [ 'name' => 'Industria Mediana Minería', 'phone' => '937 722 884', 'email' => 'nombre.linea@funrios.pe' ],
        ];
    }

    $cols = max( 1, count( $items ) );

    ob_start(); ?>
    <section class="fr-contact-depts" style="background:transparent;padding:30px 20px 70px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:1100px;margin:0 auto;">
            <ul class="fr-contact-depts__grid" style="list-style:none;margin:0;padding:0;display:grid;grid-template-columns:repeat(<?php echo (int) $cols; ?>, 1fr);gap:0;">
                <?php foreach ( $items as $i => $item ) :
                    $phone_link = ! empty( $item['phone'] ) ? 'tel:' . preg_replace( '/\s+/', '', $item['phone'] ) : ''; ?>
                    <li class="fr-contact-depts__item" style="position:relative;padding:18px 24px;text-align:center;<?php if ( $i > 0 ) : ?>border-left:1px solid #e3e3e3;<?php endif; ?>">
                        <?php if ( ! empty( $item['name'] ) ) : ?>
                            <h3 style="color:#1a1a1a;font-size:15px;font-weight:600;margin:0 0 14px;letter-spacing:.01em;"><?php echo esc_html( $item['name'] ); ?></h3>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['phone'] ) ) : ?>
                            <p style="margin:0 0 8px;">
                                <a href="<?php echo esc_attr( $phone_link ); ?>" style="display:inline-flex;align-items:center;gap:8px;color:#444;text-decoration:none;font-size:13.5px;">
                                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#1a1a1a;flex-shrink:0;"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.3 7 13 7 13s7-7.7 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5 14.5 7.6 14.5 9 13.4 11.5 12 11.5z"/></svg>
                                    <?php echo esc_html( $item['phone'] ); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['email'] ) ) : ?>
                            <p style="margin:0;">
                                <a href="mailto:<?php echo esc_attr( $item['email'] ); ?>" style="display:inline-flex;align-items:center;gap:8px;color:#444;text-decoration:none;font-size:13.5px;">
                                    <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:#1a1a1a;flex-shrink:0;"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                                    <?php echo esc_html( $item['email'] ); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <style>
        @media (max-width: 760px) {
            .fr-contact-depts__grid { grid-template-columns: 1fr !important; }
            .fr-contact-depts__item { border-left: 0 !important; border-top: 1px solid #e3e3e3; }
            .fr-contact-depts__item:first-child { border-top: 0; }
        }
        </style>
    </section>
    <?php
    return ob_get_clean();
} );
