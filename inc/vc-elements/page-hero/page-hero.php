<?php
/**
 * Elemento WPBakery: Page Hero (slider full-width con curva roja inferior).
 * Shortcode: [fr_page_hero]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Hero (Slider)', 'funrios' ),
        'base'     => 'fr_page_hero',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-application-image',
        'params'   => [
            [
                'type'        => 'dropdown',
                'heading'     => __( 'Autoplay', 'funrios' ),
                'param_name'  => 'autoplay',
                'value'       => [
                    __( 'Sí', 'funrios' ) => 'yes',
                    __( 'No', 'funrios' ) => 'no',
                ],
                'std'         => 'yes',
            ],
            [
                'type'        => 'textfield',
                'heading'     => __( 'Intervalo de autoplay (segundos)', 'funrios' ),
                'param_name'  => 'interval',
                'value'       => '6',
                'dependency'  => [ 'element' => 'autoplay', 'value' => 'yes' ],
            ],
            [
                'type'        => 'dropdown',
                'heading'     => __( 'Curva roja inferior decorativa', 'funrios' ),
                'param_name'  => 'curve',
                'value'       => [
                    __( 'No (la imagen ya la trae)', 'funrios' ) => 'no',
                    __( 'Sí (añadir SVG encima)', 'funrios' )    => 'yes',
                ],
                'std'         => 'no',
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Slides', 'funrios' ),
                'param_name' => 'slides',
                'value'      => urlencode( wp_json_encode( [
                    [
                        'eyebrow'     => 'Ingeniería y Tecnología',
                        'title'       => 'A la Vanguardia de la Industria',
                        'description' => 'Fabricamos y comercializamos piezas fundidas en aleaciones de aceros comunes y especiales.',
                        'btn_label'   => 'Descarga el Brochure',
                    ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Imagen de fondo', 'funrios' ),
                        'param_name' => 'bg_image',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Eyebrow', 'funrios' ),
                        'param_name' => 'eyebrow',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Título', 'funrios' ),
                        'param_name' => 'title',
                    ],
                    [
                        'type'       => 'textarea',
                        'heading'    => __( 'Descripción', 'funrios' ),
                        'param_name' => 'description',
                    ],
                    [
                        'type'       => 'textfield',
                        'heading'    => __( 'Texto del botón', 'funrios' ),
                        'param_name' => 'btn_label',
                    ],
                    [
                        'type'       => 'vc_link',
                        'heading'    => __( 'Link del botón', 'funrios' ),
                        'param_name' => 'btn_link',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_page_hero', function ( $atts ) {
    $atts = shortcode_atts( [
        'autoplay' => 'yes',
        'interval' => '6',
        'curve'    => 'no',
        'slides'   => '',
    ], $atts, 'fr_page_hero' );

    $slides = [];
    if ( ! empty( $atts['slides'] ) ) {
        $decoded = json_decode( urldecode( $atts['slides'] ), true );
        if ( is_array( $decoded ) ) {
            $slides = array_values( array_filter( $decoded, function ( $s ) {
                return ! empty( $s['bg_image'] ) || ! empty( $s['title'] ) || ! empty( $s['eyebrow'] );
            } ) );
        }
    }
    if ( ! $slides ) {
        $slides = [
            [
                'eyebrow'     => 'Ingeniería y Tecnología',
                'title'       => 'A la Vanguardia de la Industria',
                'description' => 'Fabricamos y comercializamos piezas fundidas en aleaciones de aceros comunes y especiales.',
                'btn_label'   => 'Descarga el Brochure',
                'btn_link'    => '',
                'bg_image'    => '',
            ],
        ];
    }

    $hero_id   = 'fr-hero-' . wp_unique_id();
    $autoplay  = ( $atts['autoplay'] === 'yes' ) ? 1 : 0;
    $interval  = max( 2, intval( $atts['interval'] ) ) * 1000;
    $show_curve = ( $atts['curve'] === 'yes' );

    // El primer slide manda la altura (img relativa con height:auto). El resto se superpone.
    $first_slide = $slides[0];
    $first_bg_id = isset( $first_slide['bg_image'] ) ? absint( $first_slide['bg_image'] ) : 0;
    $first_bg_src = $first_bg_id ? wp_get_attachment_image_url( $first_bg_id, 'full' ) : '';

    ob_start(); ?>
    <section id="<?php echo esc_attr( $hero_id ); ?>" class="fr-hero" style="position:relative;width:100%;display:block;overflow:hidden;background:#000;color:#fff;box-sizing:border-box;" data-autoplay="<?php echo (int) $autoplay; ?>" data-interval="<?php echo (int) $interval; ?>">

        <?php foreach ( $slides as $i => $slide ) :
            $bg_id   = isset( $slide['bg_image'] ) ? absint( $slide['bg_image'] ) : 0;
            $bg_src  = $bg_id ? wp_get_attachment_image_url( $bg_id, 'full' ) : '';
            $btn_url = '';
            $btn_target = '';
            if ( ! empty( $slide['btn_link'] ) && function_exists( 'vc_build_link' ) ) {
                $parsed     = vc_build_link( $slide['btn_link'] );
                $btn_url    = ! empty( $parsed['url'] ) ? $parsed['url'] : '';
                $btn_target = ! empty( $parsed['target'] ) ? trim( $parsed['target'] ) : '';
            }
            $is_first = ( $i === 0 );
            // El primero relativo (dicta altura), los demás absolutos arriba.
            $pos = $is_first ? 'relative' : 'absolute;inset:0;';
            $op  = $is_first ? '1' : '0';
            // El primer slide usa height:auto (mantiene proporción y dicta altura).
            // Los demás llenan el contenedor con object-fit:cover.
            $img_style = $is_first
                ? 'width:100%;height:auto;display:block;'
                : 'width:100%;height:100%;object-fit:cover;display:block;';
            ?>
            <div class="fr-hero__slide<?php echo $is_first ? ' is-active' : ''; ?>" style="position:<?php echo $pos; ?>;opacity:<?php echo $op; ?>;transition:opacity .8s ease;">
                <?php if ( $bg_src ) : ?>
                    <img class="fr-hero__img" src="<?php echo esc_url( $bg_src ); ?>" alt="" style="<?php echo $img_style; ?>">
                <?php endif; ?>
                <div style="position:absolute;inset:0;background:linear-gradient(to bottom, rgba(0,0,0,.45) 0%, rgba(0,0,0,.25) 55%, rgba(0,0,0,.35) 100%);pointer-events:none;"></div>
                <div style="position:absolute;inset:0;z-index:2;display:flex;align-items:center;justify-content:center;padding:80px 24px 18%;text-align:center;box-sizing:border-box;">
                    <div style="max-width:780px;">
                        <?php if ( ! empty( $slide['eyebrow'] ) ) : ?>
                            <p style="color:#ffffff;font-size:14px;font-weight:500;letter-spacing:.06em;margin:0 0 16px;opacity:.95;"><?php echo esc_html( $slide['eyebrow'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['title'] ) ) : ?>
                            <h1 style="color:#ffffff;font-size:clamp(34px, 5vw, 56px);font-weight:600;margin:0 0 20px;line-height:1.1;"><?php echo esc_html( $slide['title'] ); ?></h1>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['description'] ) ) : ?>
                            <p style="color:#e9e9e9;font-size:15px;line-height:1.7;margin:0 auto 30px;max-width:560px;"><?php echo esc_html( $slide['description'] ); ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['btn_label'] ) ) : ?>
                            <a href="<?php echo $btn_url ? esc_url( $btn_url ) : '#'; ?>"<?php if ( $btn_target ) : ?> target="<?php echo esc_attr( $btn_target ); ?>" rel="noopener"<?php endif; ?> style="display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:#e21f26;color:#ffffff;font-size:13px;font-weight:500;border-radius:2px;text-decoration:none;transition:background .2s;">
                                <?php echo esc_html( $slide['btn_label'] ); ?>
                                <span aria-hidden="true">→</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ( count( $slides ) > 1 ) : ?>
            <div class="fr-hero__dots" style="position:absolute;left:0;right:0;bottom:6%;display:flex;justify-content:center;gap:10px;z-index:5;">
                <?php foreach ( $slides as $i => $slide ) : ?>
                    <button type="button" class="fr-hero__dot<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo (int) $i; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'funrios' ), $i + 1 ) ); ?>" style="width:10px;height:10px;border-radius:50%;border:0;padding:0;cursor:pointer;background:<?php echo $i === 0 ? '#e21f26' : 'rgba(255,255,255,.5)'; ?>;transition:background .2s, transform .2s;"></button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_curve ) : ?>
            <svg viewBox="0 0 1440 120" preserveAspectRatio="none" aria-hidden="true" style="position:absolute;left:0;right:0;bottom:-1px;width:100%;height:90px;display:block;z-index:3;pointer-events:none;">
                <path d="M0,80 Q360,0 720,60 T1440,40 L1440,120 L0,120 Z" fill="#e21f26"/>
            </svg>
        <?php endif; ?>
    </section>

    <script>
    (function(){
        var root = document.getElementById('<?php echo esc_js( $hero_id ); ?>');
        if(!root) return;
        var slides = root.querySelectorAll('.fr-hero__slide');
        var dots   = root.querySelectorAll('.fr-hero__dot');
        if(slides.length < 2) return;

        var current = 0;
        var timer = null;
        var autoplay = root.getAttribute('data-autoplay') === '1';
        var interval = parseInt(root.getAttribute('data-interval'), 10) || 6000;

        function goTo(i){
            i = ((i % slides.length) + slides.length) % slides.length;
            slides.forEach(function(s, idx){
                s.style.opacity = (idx === i) ? '1' : '0';
                s.classList.toggle('is-active', idx === i);
            });
            dots.forEach(function(d, idx){
                d.classList.toggle('is-active', idx === i);
                d.style.background = (idx === i) ? '#e21f26' : 'rgba(255,255,255,.5)';
            });
            current = i;
        }

        function start(){
            if(!autoplay) return;
            stop();
            timer = setInterval(function(){ goTo(current + 1); }, interval);
        }
        function stop(){ if(timer){ clearInterval(timer); timer = null; } }

        dots.forEach(function(d){
            d.addEventListener('click', function(){
                goTo(parseInt(d.getAttribute('data-index'), 10) || 0);
                stop(); start();
            });
        });

        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);

        start();
    })();
    </script>
    <?php
    return ob_get_clean();
} );
