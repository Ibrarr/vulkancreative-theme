<?php
// Current page for pagination. Handles both 'paged' and 'page' vars.
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

// Current author object
$author    = get_queried_object();
$author_id = isset( $author->ID ) ? (int) $author->ID : 0;

$bio       = get_the_author_meta( 'description', $author_id );
$job_title = get_field( 'job_title', 'user_' . $author_id );

// Query 12 posts per page for this author
$query = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
    'author'              => $author_id,
] );

get_header();
?>

<section class="insights-header insights-header--author">
    <div class="container px-4">
        <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>

        <div class="author-identity">
            <span class="author-avatar">
                <?php echo get_avatar( $author_id, 96, '', esc_attr( $author->display_name ), [ 'class' => 'author-avatar-img' ] ); ?>
            </span>
            <div class="author-identity-text">
                <p class="insights-eyebrow">Author</p>
                <h1 class="insights-title"><?php echo esc_html( $author->display_name ); ?></h1>
                <?php if ( $job_title ) : ?>
                    <p class="author-role"><?php echo esc_html( $job_title ); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <?php if ( $bio ) : ?>
            <div class="author-bio"><?php echo wpautop( esc_html( $bio ) ); ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="insights-grid">
    <div class="container px-4">
        <h2 class="visually-hidden">Posts by <?php echo esc_html( $author->display_name ); ?></h2>
        <?php if ( $query->have_posts() ) : ?>
            <div class="row g-4">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php get_template_part( 'template-parts/content', 'card' ); ?>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" aria-label="Insights pages">
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
            <p class="insights-empty">No posts by this author yet.</p>
        <?php endif; ?>

    </div>
</section>

<?php
wp_reset_postdata();
get_footer();
