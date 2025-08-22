<?php
/**
 * The template for displaying posts
 *
 */

$post_type    = 'post';
$taxonomy     = 'category';
$terms = get_the_terms( get_the_ID(), $taxonomy );

// Check if Yoast SEO's primary category exists
$primary_term_id = null;
if ( class_exists( 'WPSEO_Primary_Term' ) ) {
    $primary_term = new WPSEO_Primary_Term( $taxonomy, get_the_ID() );
    $primary_term_id = $primary_term->get_primary_term();
}

// Set the term name based on the primary category or default to the first term
if ( $primary_term_id ) {
    $primary_term_object = get_term( $primary_term_id );
    $term_name = $primary_term_object->name;
} else {
    $term_name = $terms[0]->name;
}

$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <section class="heading" style="background: url('<?php the_post_thumbnail_url(); ?>');">
        <div class="container px-4 py-4 content">
            <h1 class="title"><?php the_title(); ?></h1>
            <p class="excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
            <div class="terms">
                <a href="<?php echo esc_url( get_term_link( $primary_term_object ) ); ?>" class="term link"><?php echo esc_html( $term_name ); ?></a>
                <?php
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    foreach ( $terms as $term ) {
                        if ( $term->term_id != $primary_term_object->term_id ) {
                            ?>
                            <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="term link">
                                <?php echo esc_html( $term->name ); ?>
                            </a>
                            <?php
                        }
                    }
                }
                ?>
                <span class="term"><?php echo ceil(str_word_count(strip_tags(get_field('content'))) / 200); ?> min read</span>
            </div>
        </div>

        <div class="overlay"></div>
    </section>

	<div class="container px-4">
        <div class="row content-area">
            <section class="col-lg-8 left">
                <section class="content">
                    <?php
                    $intro = get_field('intro_key_takeaways');
                    $content = get_field('content');

                    $between = '';

                    $full_content = $intro . $between . $content;

                    if ($full_content) {
                        $full_content = preg_replace_callback(
                            '/<h([1-2])>(.*?)<\/h\1>/',
                            function ($matches) {
                                $id = sanitize_title($matches[2]);
                                return '<h' . $matches[1] . ' id="' . esc_attr($id) . '">' . $matches[2] . '</h' . $matches[1] . '>';
                            },
                            $full_content
                        );
                        echo $full_content;
                    }
                    ?>

                </section>

                <?php
                if( have_rows('faqs') ):
                    ?><section class="faqs"> <h2>FAQs</h2><?php
                    while( have_rows('faqs') ) : the_row();
                        $question = get_sub_field('question');
                        $answer = get_sub_field('answer');
                        ?>
                        <div class="faq">
                            <h3><?php echo $question ?></h3>
                            <p><?php echo $answer ?></p>
                        </div>
                    <?php
                    endwhile;
                    ?></section><?php
                endif;
                ?>
            </section>
            <aside class="col-lg-4 sidebar right">
                <div class="sticky-container">
                    <p class="heading-label table-of-contents">Table of contents</p>
                    <div class="table-of-contents">
                        <ul>
                            <?php
                            $full_content = get_field('intro_key_takeaways') . get_field('content');

                            preg_match_all('/<h[1-2]>(.*?)<\/h[1-2]>/', $full_content, $matches, PREG_SET_ORDER);

                            foreach ($matches as $match) {
                                $heading = strip_tags($match[1]);
                                $anchor = sanitize_title($heading);
                                echo '<li><a href="#' . esc_attr($anchor) . '">' . esc_html($heading) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <hr>
                    <p class="heading-label">Written by</p>
                    <div class="author-box">
                        <div class="author-avatar">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 54 ); ?>
                        </div>
                        <div class="author-info">
                            <p class="author-name">
                                <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </p>
                            <p class="author-title">
                                <?php echo get_field( 'job_title', 'user_' . get_the_author_meta( 'ID' ) ); ?>
                            </p>
                        </div>
                    </div>
                    <hr>
                    <p class="heading-label">Subscribe to our newsletter</p>
                    <div class="form">
                        <?php echo do_shortcode( '[gravityform id="3" title="false" description="false" ajax="true"]' ); ?>
                    </div>
                </div>
            </aside>
	    </div>
	</div>
</article>

<!--<aside class="related-content">-->
<!--    <div class="container px-4">-->
<!--        <h3>More --><?php //echo $term_name; ?><!-- Content</h3>-->
<!--        <div class="row mb-3">-->
<!--            --><?php
//            $related_posts = new WP_Query(array(
//                'post_type' => $post_type,
//                'posts_per_page' => 4,
//                'post__not_in' => array(get_the_ID()),
//                'tax_query' => array(
//                    array(
//                        'taxonomy' => $taxonomy,
//                        'field' => 'name',
//                        'terms' => $term_name,
//                    ),
//                ),
//            ));
//
//            if ($related_posts->have_posts()) :
//                while ($related_posts->have_posts()) : $related_posts->the_post();
//                    require('standard-article-card.php');
//                endwhile;
//                wp_reset_postdata();
//            else :
//                echo '<p>No related posts available at the moment.</p>';
//            endif;
//            ?>
<!--        </div>-->
<!--    </div>-->
<!--</aside>-->