<?php
/**
 * Plantilla mínima de respaldo. El contenido del sitio se arma con páginas
 * de WordPress y elementos WPBakery insertados manualmente.
 */

get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        the_content();
    }
}

get_footer();
