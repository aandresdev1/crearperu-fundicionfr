<?php
/**
 * Elemento WPBakery: Video Feature (eyebrow + título + video + thumbnails).
 * Shortcode: [fr_video_feature]
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'vc_before_init', function () {
    vc_map( [
        'name'     => __( 'Video Feature', 'funrios' ),
        'base'     => 'fr_video_feature',
        'category' => 'Funrios',
        'icon'     => 'icon-wpb-film-youtube',
        'params'   => [
            [
                'type'       => 'textfield',
                'heading'    => __( 'Eyebrow', 'funrios' ),
                'param_name' => 'eyebrow',
                'value'      => 'Tu mejor aliado',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'Título', 'funrios' ),
                'param_name' => 'title',
                'value'      => 'Fabricación de piezas sometidas a alta exigencia',
            ],
            [
                'type'       => 'textarea',
                'heading'    => __( 'Descripción', 'funrios' ),
                'param_name' => 'description',
                'value'      => 'Manejamos todas las aleaciones utilizadas en la industria del cemento. Para asegurar la sanidad de las piezas utilizamos el software Magmasoft para la simulación de todos nuestros productos.',
            ],
            [
                'type'       => 'textfield',
                'heading'    => __( 'URL del video (YouTube o Vimeo)', 'funrios' ),
                'param_name' => 'video_url',
                'description'=> __( 'Pega la URL completa de YouTube/Vimeo. Si la dejas vacía, se mostrará solo el poster.', 'funrios' ),
            ],
            [
                'type'       => 'attach_image',
                'heading'    => __( 'Imagen poster (opcional)', 'funrios' ),
                'param_name' => 'poster',
                'description'=> __( 'Imagen previa al play. Si pones URL de YouTube y no defines poster, se usa el thumbnail de YouTube.', 'funrios' ),
            ],
            [
                'type'       => 'param_group',
                'heading'    => __( 'Thumbnails (debajo del video)', 'funrios' ),
                'param_name' => 'thumbs',
                'value'      => urlencode( wp_json_encode( [
                    [ 'image' => '' ],
                ] ) ),
                'params'     => [
                    [
                        'type'       => 'attach_image',
                        'heading'    => __( 'Imagen', 'funrios' ),
                        'param_name' => 'image',
                    ],
                ],
            ],
        ],
    ] );
} );

add_shortcode( 'fr_video_feature', function ( $atts ) {
    $atts = shortcode_atts( [
        'eyebrow'     => 'Tu mejor aliado',
        'title'       => 'Fabricación de piezas sometidas a alta exigencia',
        'description' => 'Manejamos todas las aleaciones utilizadas en la industria del cemento. Para asegurar la sanidad de las piezas utilizamos el software Magmasoft para la simulación de todos nuestros productos.',
        'video_url'   => '',
        'poster'      => '',
        'thumbs'      => '',
    ], $atts, 'fr_video_feature' );

    $thumbs = [];
    if ( ! empty( $atts['thumbs'] ) ) {
        $decoded = json_decode( urldecode( $atts['thumbs'] ), true );
        if ( is_array( $decoded ) ) {
            $thumbs = array_values( array_filter( $decoded, function ( $t ) {
                return ! empty( $t['image'] );
            } ) );
        }
    }

    $poster_src = $atts['poster'] ? wp_get_attachment_image_url( absint( $atts['poster'] ), 'large' ) : '';

    // Si no hay poster pero hay video de YouTube, usar thumbnail de YouTube.
    if ( ! $poster_src && ! empty( $atts['video_url'] ) ) {
        if ( preg_match( '#(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/))([\w-]{11})#', $atts['video_url'], $m ) ) {
            $poster_src = 'https://img.youtube.com/vi/' . $m[1] . '/maxresdefault.jpg';
        }
    }

    $video_embed = '';
    if ( ! empty( $atts['video_url'] ) ) {
        $video_embed = wp_oembed_get( $atts['video_url'], [ 'width' => 900 ] );
    }

    ob_start(); ?>
    <section class="fr-video-feature" style="background:transparent;padding:70px 20px;display:block;width:100%;box-sizing:border-box;">
        <div style="max-width:980px;margin:0 auto;text-align:center;">
            <?php if ( ! empty( $atts['eyebrow'] ) ) : ?>
                <p style="color:#e21f26;font-size:13px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;margin:0 0 12px;"><?php echo esc_html( $atts['eyebrow'] ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 style="color:#ffffff;font-size:30px;font-weight:600;margin:0 0 18px;line-height:1.25;"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>
            <?php if ( ! empty( $atts['description'] ) ) : ?>
                <p style="color:#c7c7c7;font-size:14px;line-height:1.7;margin:0 auto 36px;max-width:720px;"><?php echo esc_html( $atts['description'] ); ?></p>
            <?php endif; ?>

            <div class="fr-video-feature__player" style="position:relative;background:#111;border-radius:8px;overflow:hidden;aspect-ratio:16/9;">
                <?php if ( $video_embed ) : ?>
                    <div style="position:absolute;inset:0;" data-fr-video-embed="<?php echo esc_attr( wp_json_encode( $video_embed ) ); ?>">
                        <?php if ( $poster_src ) : ?>
                            <button type="button" class="fr-video-feature__play" style="position:absolute;inset:0;width:100%;height:100%;border:0;padding:0;background:url('<?php echo esc_url( $poster_src ); ?>') center/cover no-repeat;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                                <span style="width:64px;height:64px;border-radius:50%;background:rgba(0,0,0,.6);display:inline-flex;align-items:center;justify-content:center;">
                                    <svg viewBox="0 0 24 24" style="width:24px;height:24px;fill:#ffffff;margin-left:3px;"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </button>
                        <?php else : ?>
                            <?php echo $video_embed; // ya filtrado por oEmbed ?>
                        <?php endif; ?>
                    </div>
                <?php elseif ( $poster_src ) : ?>
                    <img src="<?php echo esc_url( $poster_src ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                <?php else : ?>
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#666;font-size:13px;">[ Sube un poster o pega una URL de video ]</div>
                <?php endif; ?>
            </div>

            <?php if ( $thumbs ) : ?>
                <ul class="fr-video-feature__thumbs" style="list-style:none;margin:30px 0 0;padding:0;display:flex;flex-wrap:wrap;justify-content:center;gap:12px;">
                    <?php foreach ( $thumbs as $t ) :
                        $tid  = absint( $t['image'] );
                        $tsrc = $tid ? wp_get_attachment_image_url( $tid, 'thumbnail' ) : '';
                        if ( ! $tsrc ) continue; ?>
                        <li style="width:100px;height:70px;background:#1c1c1c;border-radius:6px;overflow:hidden;flex-shrink:0;">
                            <img src="<?php echo esc_url( $tsrc ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;display:block;">
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>

    <script>
    (function(){
        document.querySelectorAll('.fr-video-feature__play').forEach(function(btn){
            btn.addEventListener('click', function(){
                var wrap = btn.parentNode;
                var embed = wrap.getAttribute('data-fr-video-embed');
                if(!embed) return;
                try { embed = JSON.parse(embed); } catch(e) { return; }
                wrap.innerHTML = embed;
                // Si el embed es un iframe sin autoplay, intentamos forzarlo
                var iframe = wrap.querySelector('iframe');
                if(iframe && iframe.src.indexOf('autoplay') === -1){
                    iframe.src += (iframe.src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1';
                }
            }, { once: true });
        });
    })();
    </script>
    <?php
    return ob_get_clean();
} );
