<?php

// Current page for pagination. Handles both 'paged' and 'page' vars.
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

// Query 12 posts per page
$query = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
] );

get_header();
?>

<section class="insights-header">
	<div class="page-hero-glow" aria-hidden="true"></div>
    <div class="container px-4">
        <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>
        <h1 class="insights-title">News, Insights &amp; What We’re Building at <span>Vulkan Creative</span>.</h1>
        <p class="insights-standfirst">Sharp takes on brand, web and marketing: what we're learning, building and watching in the industry.</p>
    </div>
</section>

<section class="insights-grid">
    <div class="container px-4">
        <?php get_template_part( 'template-parts/insights-filter' ); ?>

        <h2 class="visually-hidden">Latest insights</h2>

        <div class="row g-4" data-insights-grid>
            <?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'card' ); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="insights-empty">No insights published yet.</p>
            <?php endif; ?>
        </div>

        <nav class="pagination" aria-label="Insights pages" data-insights-pagination>
            <?php
            if ( (int) $query->max_num_pages > 1 ) {
                $big = 999999999;
                echo paginate_links( [
                    'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format'    => '?paged=%#%',
                    'current'   => max( 1, $paged ),
                    'total'     => (int) $query->max_num_pages,
                    'mid_size'  => 1,
                    'prev_text' => '<span aria-hidden="true">&lsaquo;</span><span class="visually-hidden">Previous page</span>',
                    'next_text' => '<span aria-hidden="true">&rsaquo;</span><span class="visually-hidden">Next page</span>',
                ] );
            }
            ?>
        </nav>
    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
