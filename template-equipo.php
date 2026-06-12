<?php
/**
 * Template Name: Equipo
 *
 * @package Enclave_Urbano
 */

get_header();
?>

<main id="main" class="eu-main">

<section id="equipo" class="eu-section eu-section-team">
    <div class="eu-container eu-team-layout">

        <aside class="eu-team-intro">
            <h1 class="eu-section-title"><?php the_title(); ?></h1>

            <h3><?php echo esc_html(eu_get_option('team_intro_title')); ?></h3>

            <?php
            echo wpautop(
                eu_kses_content(
                    eu_get_option('team_intro_text')
                )
            );
            ?>

            <div class="eu-team-note">
                <?php
                echo wpautop(
                    eu_kses_content(
                        eu_get_option('team_note')
                    )
                );
                ?>

                <span class="eu-team-note__icon" style="position:absolute; right:-15px; bottom:-10px; width:50px; height:50px; display:flex; align-items:center; justify-content:center;">
                    <?php echo eu_inline_icon('agenda'); ?>
                </span>
            </div>
        </aside>

        <div class="eu-team-symbol" aria-hidden="true">
            <?php echo eu_inline_icon('neural'); ?>
        </div>

        <div class="eu-team-content">

            <p class="eu-team-kicker">
                <?php esc_html_e('Cada proyecto, cliente y sitio es único.', 'enclave-urbano'); ?>
            </p>

            <h2>
                <?php esc_html_e('Arquitectos', 'enclave-urbano'); ?>
            </h2>

            <div class="eu-team-cards">
                <?php eu_render_team_cards(); ?>
            </div>

            <p class="eu-team-closing">
                <?php esc_html_e('Diseña, crea y construye.', 'enclave-urbano'); ?>
            </p>

        </div>

    </div>
</section>

</main>

<?php get_footer(); ?>
