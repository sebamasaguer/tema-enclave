<?php
/**
 * Generic page template.
 *
 * @package Enclave_Urbano
 */

get_header();
?>
<main id="main" class="eu-main eu-page-main">
    <div class="eu-container eu-page-shell">
        <?php while (have_posts()) : the_post(); ?>
            <header class="eu-page-header">
                <h1><?php the_title(); ?></h1>
            </header>
            <article class="eu-page-editor-content">
                <?php the_content(); ?>
            </article>
        <?php endwhile; ?>
    </div>
</main>
<?php get_footer(); ?>
