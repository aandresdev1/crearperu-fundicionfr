<?php
/**
 * Header del tema Funrios.
 *
 * Editar redes sociales en el array $fr_social de abajo.
 */

$fr_social = [
    'facebook' => '#',
    'youtube'  => '#',
    'linkedin' => '#',
    'whatsapp' => '#',
];

$fr_social_icons = [
    'facebook' => '<svg viewBox="0 0 24 24"><path d="M13.5 21v-8h2.7l.4-3.2h-3.1V7.7c0-.9.3-1.6 1.6-1.6h1.7V3.2C16.4 3.1 15.4 3 14.3 3 11.9 3 10.3 4.5 10.3 7.2v2.6H7.7V13h2.6v8h3.2z"/></svg>',
    'youtube'  => '<svg viewBox="0 0 24 24"><path d="M21.6 7.2c-.2-.9-.9-1.6-1.8-1.8C18.2 5 12 5 12 5s-6.2 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.8 2 12 2 12s0 3.2.4 4.8c.2.9.9 1.6 1.8 1.8C5.8 19 12 19 12 19s6.2 0 7.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.6.4-4.8.4-4.8s0-3.2-.4-4.8zM10 15V9l5 3-5 3z"/></svg>',
    'linkedin' => '<svg viewBox="0 0 24 24"><path d="M4.98 3.5C4.98 4.88 3.88 6 2.5 6S0 4.88 0 3.5 1.12 1 2.5 1s2.48 1.12 2.48 2.5zM.22 8h4.56v14H.22V8zm7.5 0H12v2h.07c.6-1.13 2.08-2.32 4.28-2.32 4.58 0 5.42 3.02 5.42 6.95V22h-4.55v-6.49c0-1.55-.03-3.55-2.16-3.55-2.17 0-2.5 1.7-2.5 3.44V22H7.72V8z"/></svg>',
    'whatsapp' => '<svg viewBox="0 0 24 24"><path d="M17.5 14.4c-.3-.1-1.7-.8-2-.9-.3-.1-.5-.1-.7.1-.2.3-.7.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.5-2.3-1.4-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6.1-.1.3-.4.4-.5.1-.2.2-.3.3-.5.1-.2 0-.4 0-.5-.1-.1-.7-1.7-.9-2.3-.2-.6-.5-.5-.7-.5h-.6c-.2 0-.5.1-.7.4-.3.3-.9.9-.9 2.2 0 1.3 1 2.6 1.1 2.7.1.2 1.9 2.9 4.6 4.1.6.3 1.1.4 1.5.5.6.2 1.2.2 1.6.1.5-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.1-.3-.2-.6-.3zM12 2C6.5 2 2 6.5 2 12c0 1.8.5 3.5 1.3 5L2 22l5.2-1.4c1.5.8 3.1 1.2 4.8 1.2 5.5 0 10-4.5 10-10S17.5 2 12 2zm0 18.3c-1.5 0-3-.4-4.3-1.2l-.3-.2-3 .8.8-2.9-.2-.3c-.9-1.4-1.3-3-1.3-4.5 0-4.5 3.7-8.2 8.3-8.2S20.3 7.5 20.3 12c0 4.5-3.7 8.3-8.3 8.3z"/></svg>',
];
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="fr-site-header">
    <div class="fr-container fr-site-header__inner">
        <div class="fr-site-header__logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                <?php
                $fr_header_logo = function_exists( 'fr_get_header_logo_url' ) ? fr_get_header_logo_url() : '';
                if ( $fr_header_logo ) {
                    printf( '<img src="%s" alt="%s">', esc_url( $fr_header_logo ), esc_attr( get_bloginfo( 'name' ) ) );
                } else {
                    echo '<strong style="color:#fff;font-size:18px;letter-spacing:.06em;">' . esc_html( get_bloginfo( 'name' ) ) . '</strong>';
                }
                ?>
            </a>
        </div>

        <button class="fr-burger" type="button" aria-label="<?php esc_attr_e( 'Abrir menú', 'funrios' ); ?>" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="fr-site-header__nav" aria-label="<?php esc_attr_e( 'Menú principal', 'funrios' ); ?>">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => '',
                    'depth'          => 1,
                ] );
            } else {
                echo '<ul><li><a href="#">Sobre Funrios</a></li><li><a href="#">Lineas</a></li><li><a href="#">Contacto</a></li></ul>';
            }
            ?>
        </nav>
    </div>
</header>

<aside class="fr-social-rail" aria-label="<?php esc_attr_e( 'Redes sociales', 'funrios' ); ?>">
    <?php foreach ( $fr_social as $net => $url ) :
        if ( empty( $url ) ) continue; ?>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $net ) ); ?>">
            <?php echo $fr_social_icons[ $net ]; // SVG inline confiable ?>
        </a>
    <?php endforeach; ?>
</aside>

<div id="page" class="site">
    <main id="content" class="site-content">
        <?php if ( function_exists( 'fr_render_page_hero' ) ) { fr_render_page_hero(); } ?>

<script>
document.addEventListener('DOMContentLoaded',function(){
    var b=document.querySelector('.fr-burger'),n=document.querySelector('.fr-site-header__nav');
    if(b&&n){b.addEventListener('click',function(){var o=n.classList.toggle('is-open');b.setAttribute('aria-expanded',o);});}
});
</script>
