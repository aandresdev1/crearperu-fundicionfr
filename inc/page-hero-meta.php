<?php
/**
 * Hero opcional por página.
 *
 * Agrega un metabox al editor de páginas con:
 *  - Toggle "Mostrar hero"
 *  - Título
 *  - Subtítulo
 *  - Imagen de fondo
 *
 * El hero se renderiza desde header.php llamando a fr_render_page_hero().
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Registra el metabox en el editor de páginas.
 */
add_action( 'add_meta_boxes', function () {
    add_meta_box(
        'fr_page_hero',
        __( 'Hero de la página', 'funrios' ),
        'fr_page_hero_metabox_render',
        'page',
        'normal',
        'high'
    );
} );

/**
 * Carga el script del media uploader solo en el editor de páginas.
 */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) { return; }
    // Cargamos siempre en post.php/post-new.php — es barato y evita problemas de timing.
    wp_enqueue_media();
} );

/**
 * Render del metabox.
 */
function fr_page_hero_metabox_render( $post ) {
    wp_nonce_field( 'fr_page_hero_save', 'fr_page_hero_nonce' );

    $enabled  = get_post_meta( $post->ID, '_fr_hero_enabled', true );
    $title    = get_post_meta( $post->ID, '_fr_hero_title', true );
    $subtitle = get_post_meta( $post->ID, '_fr_hero_subtitle', true );
    $image_id = absint( get_post_meta( $post->ID, '_fr_hero_image', true ) );

    $image_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : '';
    ?>
    <style>
        .fr-hero-meta { display: grid; gap: 14px; }
        .fr-hero-meta label.fr-hm-label { display: block; font-weight: 600; margin-bottom: 4px; }
        .fr-hero-meta input[type="text"],
        .fr-hero-meta textarea { width: 100%; max-width: 560px; }
        .fr-hero-meta .fr-hm-preview { margin-top: 8px; max-width: 240px; }
        .fr-hero-meta .fr-hm-preview img { max-width: 100%; height: auto; display: block; border: 1px solid #ddd; }
    </style>
    <div class="fr-hero-meta">
        <p>
            <label>
                <input type="checkbox" name="fr_hero_enabled" value="1" <?php checked( $enabled, '1' ); ?>>
                <strong><?php esc_html_e( 'Mostrar hero al inicio de la página', 'funrios' ); ?></strong>
            </label>
        </p>

        <p>
            <label class="fr-hm-label" for="fr_hero_title"><?php esc_html_e( 'Título', 'funrios' ); ?></label>
            <input type="text" id="fr_hero_title" name="fr_hero_title" value="<?php echo esc_attr( $title ); ?>" placeholder="<?php esc_attr_e( 'Ej: Industria Minera', 'funrios' ); ?>">
        </p>

        <p>
            <label class="fr-hm-label" for="fr_hero_subtitle"><?php esc_html_e( 'Subtítulo', 'funrios' ); ?></label>
            <textarea id="fr_hero_subtitle" name="fr_hero_subtitle" rows="2" placeholder="<?php esc_attr_e( 'Texto corto debajo del título (opcional)', 'funrios' ); ?>"><?php echo esc_textarea( $subtitle ); ?></textarea>
        </p>

        <p>
            <label class="fr-hm-label"><?php esc_html_e( 'Imagen de fondo', 'funrios' ); ?></label>
            <input type="hidden" id="fr_hero_image" name="fr_hero_image" value="<?php echo esc_attr( $image_id ); ?>">
            <button type="button" class="button" id="fr_hero_image_pick"><?php esc_html_e( 'Seleccionar imagen', 'funrios' ); ?></button>
            <button type="button" class="button" id="fr_hero_image_clear" <?php echo $image_id ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'Quitar', 'funrios' ); ?></button>
            <span class="fr-hm-preview" id="fr_hero_image_preview">
                <?php if ( $image_src ) : ?>
                    <img src="<?php echo esc_url( $image_src ); ?>" alt="">
                <?php endif; ?>
            </span>
        </p>
    </div>

    <script>
    (function(){
        function ready(fn){
            if (document.readyState !== 'loading') { fn(); }
            else { document.addEventListener('DOMContentLoaded', fn); }
        }
        function waitForMedia(cb, tries){
            tries = tries || 0;
            if (typeof window.wp !== 'undefined' && window.wp.media) { cb(); return; }
            if (tries > 100) { console.warn('[fr-hero] wp.media no se cargó'); return; }
            setTimeout(function(){ waitForMedia(cb, tries + 1); }, 50);
        }
        ready(function(){
            var pickBtn  = document.getElementById('fr_hero_image_pick');
            var clearBtn = document.getElementById('fr_hero_image_clear');
            var input    = document.getElementById('fr_hero_image');
            var preview  = document.getElementById('fr_hero_image_preview');
            if (!pickBtn || !input) return;

            pickBtn.addEventListener('click', function(e){
                e.preventDefault();
                waitForMedia(function(){
                    var frame = window._frHeroFrame || (window._frHeroFrame = window.wp.media({
                        title: '<?php echo esc_js( __( 'Imagen de fondo del hero', 'funrios' ) ); ?>',
                        button: { text: '<?php echo esc_js( __( 'Usar esta imagen', 'funrios' ) ); ?>' },
                        multiple: false,
                        library: { type: 'image' }
                    }));
                    frame.off('select');
                    frame.on('select', function(){
                        var att = frame.state().get('selection').first().toJSON();
                        input.value = att.id;
                        var url = (att.sizes && att.sizes.medium) ? att.sizes.medium.url : att.url;
                        preview.innerHTML = '<img src="' + url + '" alt="">';
                        if (clearBtn) clearBtn.style.display = '';
                    });
                    frame.open();
                });
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function(e){
                    e.preventDefault();
                    input.value = '';
                    preview.innerHTML = '';
                    clearBtn.style.display = 'none';
                });
            }
        });
    })();
    </script>
    <?php
}

/**
 * Guarda los valores del metabox.
 */
add_action( 'save_post_page', function ( $post_id ) {
    if ( ! isset( $_POST['fr_page_hero_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['fr_page_hero_nonce'] ) ), 'fr_page_hero_save' ) ) { return; }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }

    update_post_meta( $post_id, '_fr_hero_enabled', isset( $_POST['fr_hero_enabled'] ) ? '1' : '' );
    update_post_meta( $post_id, '_fr_hero_title',    sanitize_text_field( wp_unslash( $_POST['fr_hero_title']    ?? '' ) ) );
    update_post_meta( $post_id, '_fr_hero_subtitle', sanitize_textarea_field( wp_unslash( $_POST['fr_hero_subtitle'] ?? '' ) ) );
    update_post_meta( $post_id, '_fr_hero_image',    absint( $_POST['fr_hero_image'] ?? 0 ) );
} );

/**
 * Render del hero — se llama desde header.php (después de <main>).
 */
function fr_render_page_hero() {
    if ( ! is_page() ) { return; }
    $post_id = get_queried_object_id();
    if ( ! $post_id ) { return; }
    if ( get_post_meta( $post_id, '_fr_hero_enabled', true ) !== '1' ) { return; }

    $title    = get_post_meta( $post_id, '_fr_hero_title', true );
    $subtitle = get_post_meta( $post_id, '_fr_hero_subtitle', true );
    $img_id   = absint( get_post_meta( $post_id, '_fr_hero_image', true ) );
    $img_src  = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';

    if ( ! $title && ! $subtitle && ! $img_src ) { return; }
    ?>
    <section class="fr-page-hero" style="position:relative;width:100%;min-height:360px;display:flex;align-items:center;justify-content:center;text-align:center;padding:140px 24px 80px;box-sizing:border-box;color:#fff;background:#1a1a1a;<?php if ( $img_src ) : ?>background-image:url('<?php echo esc_url( $img_src ); ?>');background-size:cover;background-position:center;<?php endif; ?>">
        <span style="position:absolute;inset:0;background:linear-gradient(to bottom, rgba(0,0,0,.5), rgba(0,0,0,.4));pointer-events:none;"></span>
        <div style="position:relative;z-index:1;max-width:780px;">
            <?php if ( $title ) : ?>
                <h1 style="color:#ffffff;font-size:clamp(28px, 4.5vw, 46px);font-weight:600;margin:0 0 12px;line-height:1.15;"><?php echo esc_html( $title ); ?></h1>
            <?php endif; ?>
            <?php if ( $subtitle ) : ?>
                <p style="color:#e9e9e9;font-size:15px;line-height:1.7;margin:0 auto;max-width:600px;"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
    </section>
    <?php
}
