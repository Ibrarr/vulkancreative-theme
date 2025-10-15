<?php
// Current page for pagination. Handles both 'paged' and 'page' vars.
$paged = max( 1, (int) get_query_var( 'paged' ), (int) get_query_var( 'page' ) );

// Current author object
$author = get_queried_object();
$author_id = isset( $author->ID ) ? (int) $author->ID : 0;

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

    <section class="heading">
        <div class="container px-4">
            <div class="content">
                <div class="author-avatar">
                    <?php echo get_avatar( $author_id, 100, '', esc_attr( $author->display_name ), [ 'class' => 'rounded-circle' ] ); ?>
                </div>

                <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>

                <h1>Posts By <?php echo esc_html( $author->display_name ); ?></h1>

                <?php
                $author_id  = isset( $author->ID ) ? (int) $author->ID : 0;
                $bio        = get_the_author_meta( 'description', $author_id );
                $job_title  = get_field( 'job_title', 'user_' . $author_id );

                if ( $bio || get_avatar( $author_id ) || $job_title ) : ?>
                    <div class="author-meta">
                        <div class="author-text">
<!--                            <p class="author-name mb-1">-->
<!--                                --><?php //if ( $job_title ) : ?>
<!--                                    <span class="author-job-title">--><?php //echo esc_html( $job_title ); ?><!--</span>-->
<!--                                --><?php //endif; ?>
<!--                            </p>-->

                            <?php
                            if ( $bio ) {
                                echo wpautop( esc_html( $bio ) );
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>


    <section class="posts">
        <div class="container px-4">
            <?php if ( $query->have_posts() ) : ?>
                <div class="row g-4">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <article <?php post_class( 'post-card col-lg-4 col-md-6 col-12' ); ?>>

                            <div class="img-cat-container">
                                <a class="post-thumb" href="<?php the_permalink(); ?>">
                                    <?php
                                    the_post_thumbnail( 'large', [
                                        'alt'   => esc_attr( get_the_title() ),
                                        'class' => 'img-fluid w-100',
                                    ] );
                                    ?>
                                </a>

                                <div class="post-primary-cat">
                                    <?php
                                    // Yoast Primary Category with fallback
                                    $primary_term = null;

                                    if ( class_exists( 'WPSEO_Primary_Term' ) ) {
                                        $wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_ID() );
                                        $primary_term_id    = $wpseo_primary_term->get_primary_term();

                                        if ( $primary_term_id && ! is_wp_error( $primary_term_id ) ) {
                                            $term = get_term( $primary_term_id );
                                            if ( $term && ! is_wp_error( $term ) ) {
                                                $primary_term = $term;
                                            }
                                        }
                                    }

                                    if ( ! $primary_term ) {
                                        $cats = get_the_category();
                                        if ( ! empty( $cats ) ) {
                                            $primary_term = $cats[0];
                                        }
                                    }

                                    if ( $primary_term ) {
                                        $term_link = get_term_link( $primary_term );
                                        if ( ! is_wp_error( $term_link ) ) {
                                            echo '<a class="post-cat badge" href="' . esc_url( $term_link ) . '">' . esc_html( $primary_term->name ) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <h3 class="post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>

                            <p class="post-excerpt">
                                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 32, '…' ) ); ?>
                            </p>

                        </article>
                    <?php endwhile; ?>
                </div>

                <nav class="pagination">
                    <?php
                    $big = 999999999;
                    echo paginate_links( [
                        'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format'    => '?paged=%#%',
                        'current'   => max( 1, $paged ),
                        'total'     => (int) $query->max_num_pages,
                        'mid_size'  => 1,
                        'prev_text' => '&lt;',
                        'next_text' => '&gt;',
                    ] );
                    ?>
                </nav>

            <?php else : ?>
                <p>No posts yet. Someone should write one.</p>
            <?php endif; ?>

        </div>
    </section>

<?php
wp_reset_postdata();
get_footer();