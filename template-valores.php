<?php
/**
 * Template Name: Valores
 */

get_header();
?>

<main class="eu-page">
    <section class="eu-section eu-section-values">
        <div class="eu-container">
            <h1 class="eu-section-title"><?php the_title(); ?></h1>

            <div class="eu-values-grid">
                <?php if(function_exists('eu_render_values')) { eu_render_values(); } ?>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
