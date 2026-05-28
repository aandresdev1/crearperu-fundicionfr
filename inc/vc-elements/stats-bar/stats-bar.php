<?php
/**
 * Elemento WPBakery: Stats Bar (banda de estadísticas).
 * Shortcode: [fr_stats_bar]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Banda de Stats', 'funrios' ),
        'base'     => 'fr_stats_bar',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-ui-separator',
        'params'   => [
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen de fondo (opcional)', 'funrios' ),
                'param_name' => 'bg_image',
                'description'=> __( 'Si la dejas vacía, se usa el rojo de marca.', 'funrios' ),
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Stats', 'funrios' ),
                'param_name' => 'items',
                'value'      => urlencode( wp_json_encode( [
                    [ 'number' => '200', 'label' => 'Colaboradores',       'description' => 'en el grupo Funrios' ],
                    [ 'number' => '22',  'label' => 'Años de experiencia', 'description' => 'al servicio de la industria' ],
                    [ 'number' => '1500','label' => 'Proyectos exitosos',  'description' => 'en el sector minero e industrial' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Icono', 'funrios' ),
                        'param_name' => 'icon',
                        'description'=> __( 'Imagen pequeña (PNG/SVG) que aparece a la izquierda del número.', 'funrios' ),
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Número', 'funrios' ),
                        'param_name' => 'number',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Etiqueta', 'funrios' ),
                        'param_name' => 'label',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Descripción', 'funrios' ),
                        'param_name' => 'description',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_stats_bar', function ( $atts ) {
    $atts = shortcode_atts( [
        'bg_image' => '',
        'items'    => '',
    ], $atts, 'fr_stats_bar' );

    wp_enqueue_style( 'fr-stats-bar' );

    $items = [];
    if ( ! empty( $atts['items'] ) ) {
        $decoded = json_decode( urldecode( $atts['items'] ), true );
        if ( is_array( $decoded ) ) {
            $items = array_values( array_filter( $decoded, function ( $i ) {
                return ! empty( $i['number'] ) || ! empty( $i['label'] );
            } ) );
        }
    }
    if ( ! $items ) {
        $items = [
            [ 'number' => '200',  'label' => 'Colaboradores',       'description' => 'en el grupo Funrios' ],
            [ 'number' => '22',   'label' => 'Años de experiencia', 'description' => 'al servicio de la industria' ],
            [ 'number' => '1500', 'label' => 'Proyectos exitosos',  'description' => 'en el sector minero e industrial' ],
        ];
    }

    $bg = $atts['bg_image'] ? wp_get_attachment_image_url( absint( $atts['bg_image'] ), 'full' ) : '';

    ob_start(); ?>
    <section class="fr-stats-bar<?php echo $bg ? ' has-bg' : ''; ?>" <?php if ( $bg ) : ?>style="background-image:url('<?php echo esc_url( $bg ); ?>');"<?php endif; ?>>
        <div class="fr-container">
            <ul class="fr-stats-bar__grid" style="--fr-stats-cols: <?php echo (int) max( 1, count( $items ) ); ?>;">
                <?php foreach ( $items as $item ) :
                    $icon_id  = isset( $item['icon'] ) ? absint( $item['icon'] ) : 0;
                    $icon_src = $icon_id ? wp_get_attachment_image_url( $icon_id, 'thumbnail' ) : '';
                    $has_icon = $icon_src ? ' has-icon' : ''; ?>
                    <li class="fr-stats-bar__item<?php echo $has_icon; ?>">
                        <?php if ( $icon_src ) : ?>
                            <span class="fr-stats-bar__icon"><img src="<?php echo esc_url( $icon_src ); ?>" alt=""></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['number'] ) ) : ?>
                            <span class="fr-stats-bar__number"><?php echo esc_html( $item['number'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['label'] ) ) : ?>
                            <span class="fr-stats-bar__label"><?php echo esc_html( $item['label'] ); ?></span>
                        <?php endif; ?>
                        <?php if ( ! empty( $item['description'] ) ) : ?>
                            <span class="fr-stats-bar__desc"><?php echo esc_html( $item['description'] ); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <?php
    return ob_get_clean();
} );

add_action( 'wp_enqueue_scripts', function () {
    wp_register_style(
        'fr-stats-bar',
        get_template_directory_uri() . '/inc/vc-elements/stats-bar/stats-bar.css',
        [],
        '1.2'
    );
} );
