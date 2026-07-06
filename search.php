<?php
get_header();

$term  = get_search_query();
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

$query = new WP_Query( [
    's'              => $term,
    'posts_per_page' => 8,
    'paged'          => $paged,
    'post_type'      => [ 'post', 'video' ],
] );
?>

<section class="insights-header">
	<div class="page-hero-glow" aria-hidden="true"></div>
    <div class="container px-4">
        <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>
        <h1 class="insights-title">Results for &ldquo;<?php echo esc_html( $term ); ?>&rdquo;</h1>
    </div>
</section>

<section class="insights-grid">
    <div class="container px-4">
        <?php
        // Give the results page a real search box (pre-filled with the current
        // query) so a visitor can correct or refine without editing the URL.
        // The grid here has no [data-insights-grid] hook, so the shared filter
        // JS leaves the form as a plain GET resubmit to /?s=.
        get_template_part( 'template-parts/insights-filter' );
        ?>
        <h2 class="visually-hidden">Search results</h2>
        <?php if ( $query->have_posts() ) : ?>
            <div class="row g-4">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" aria-label="Search result pages">
                <?php
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
                ?>
            </nav>

        <?php else : ?>
            <p class="insights-empty">No results for that search. Try a different phrase above, or explore:</p>
            <p class="insights-empty-links">
                <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Insights</a>
                <a href="<?php echo esc_url( home_url( '/work/' ) ); ?>">Our Work</a>
                <a href="<?php echo esc_url( home_url( '/case-studies/' ) ); ?>">Case Studies</a>
            </p>
        <?php endif; ?>

    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
