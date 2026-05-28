<?php
/**
 * Footer del tema Funrios.
 *
 * Editar datos de contacto y certificación abajo en los arrays.
 */

$fr_footer_about   = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor tan ut labore.';
$fr_footer_contact = [
    'address' => 'CalleSSS Los Productores 161, Urbanización Pro Industrial San Martín de Porres Lima 31 - Perú',
    'email'   => 'funrios@funrios.pe',
];
$fr_footer_cert = [
    'image' => '', // URL absoluta del logo de certificación (Magma)
    'label' => 'HOMOLOGADO',
];

$year = date_i18n( 'Y' );
?>
    </main>

    <footer class="fr-site-footer">
        <div class="fr-container fr-site-footer__top">

            <div class="fr-site-footer__col">
                <div class="fr-site-footer__logo">
                    <?php if ( has_custom_logo() ) {
                        $logo_id = get_theme_mod( 'custom_logo' );
                        $logo    = wp_get_attachment_image_src( $logo_id, 'full' );
                        if ( $logo ) {
                            printf( '<img src="%s" alt="%s">', esc_url( $logo[0] ), esc_attr( get_bloginfo( 'name' ) ) );
                        }
                    } else {
                        echo '<strong style="color:#fff;font-size:18px;letter-spacing:.06em;">' . esc_html( get_bloginfo( 'name' ) ) . '</strong>';
                    } ?>
                </div>
                <p><?php echo esc_html( $fr_footer_about ); ?></p>
            </div>

            <div class="fr-site-footer__col">
                <h4><?php esc_html_e( 'Empresa', 'funrios' ); ?></h4>
                <?php
                if ( has_nav_menu( 'footer' ) ) {
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => '',
                        'depth'          => 1,
                    ] );
                } else {
                    echo '<ul>'
                        . '<li><a href="#">Sobre Funrios</a></li>'
                        . '<li><a href="#">Industria Cementera</a></li>'
                        . '<li><a href="#">Industria Gran Minería</a></li>'
                        . '<li><a href="#">Industria Mediana Minería</a></li>'
                        . '</ul>';
                } ?>
            </div>

            <div class="fr-site-footer__col">
                <h4><?php esc_html_e( 'Contacto', 'funrios' ); ?></h4>
                <?php if ( ! empty( $fr_footer_contact['address'] ) ) : ?>
                    <div class="fr-site-footer__contact-item">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.3 7 13 7 13s7-7.7 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5 14.5 7.6 14.5 9 13.4 11.5 12 11.5z"/></svg>
                        <span><?php echo esc_html( $fr_footer_contact['address'] ); ?></span>
                    </div>
                <?php endif; ?>
                <?php if ( ! empty( $fr_footer_contact['email'] ) ) : ?>
                    <div class="fr-site-footer__contact-item">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        <a href="mailto:<?php echo esc_attr( $fr_footer_contact['email'] ); ?>"><?php echo esc_html( $fr_footer_contact['email'] ); ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="fr-site-footer__col fr-site-footer__cert">
                <?php if ( ! empty( $fr_footer_cert['image'] ) ) : ?>
                    <img src="<?php echo esc_url( $fr_footer_cert['image'] ); ?>" alt="<?php echo esc_attr( $fr_footer_cert['label'] ); ?>">
                <?php endif; ?>
            </div>

        </div>

        <div class="fr-site-footer__bottom">
            <div class="fr-container fr-site-footer__bottom-inner">
                <span>© <?php echo esc_html( $year ); ?> Funrios S.A.C. &nbsp;|&nbsp; Todos los derechos reservados &nbsp;|&nbsp; <a href="#">Libro de reclamaciones</a></span>
                <span>Developer: <a href="#">Crearperu</a></span>
            </div>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
<script>
(function(){
    var body = document.body;
    if (!body) return;

    // Si el usuario prefiere menos animación, no interceptamos nada.
    var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (reduced) return;

    // bfcache: al volver con back/forward removemos la clase para que el fade-in vuelva a correr.
    window.addEventListener('pageshow', function(e){
        body.classList.remove('is-leaving');
        if (e.persisted) {
            // re-disparar animación CSS
            body.style.animation = 'none';
            void body.offsetHeight;
            body.style.animation = '';
        }
    });

    document.addEventListener('click', function(e){
        var a = e.target.closest && e.target.closest('a');
        if (!a) return;
        var href = a.getAttribute('href');
        if (!href) return;
        if (a.target === '_blank') return;
        if (a.hasAttribute('download')) return;
        if (a.hasAttribute('data-no-transition')) return;
        if (e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button !== 0) return;
        if (href.charAt(0) === '#') return;
        if (/^(mailto:|tel:|javascript:)/i.test(href)) return;
        try {
            var url = new URL(a.href, window.location.href);
            if (url.origin !== window.location.origin) return;
            // mismo path + hash → es solo anchor, no navegamos
            if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) return;
        } catch (err) { return; }

        e.preventDefault();
        body.classList.add('is-leaving');
        setTimeout(function(){ window.location.href = a.href; }, 250);
    });
})();
</script>
</body>
</html>
