<?php
get_header();

while ( have_posts() ) : the_post(); ?>

    <article class="default-page">
        <div class="page-header">
            <div class="container px-4">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="breadcrumbs"><?php echo do_shortcode( '[wpseo_breadcrumb]' ); ?></div>
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="page-body">
            <div class="container px-4">
                <div class="row">
                    <div class="col-lg-8 content-area">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </section>
    </article>

<?php endwhile;

get_footer();
?>
