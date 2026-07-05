<?php
/**
 * The template for displaying posts (forge redesign).
 */

$taxonomy = 'category';
$terms    = get_the_terms( get_the_ID(), $taxonomy );

// Primary category: Yoast's pick, else the first assigned term.
$primary_term_id = null;
if ( class_exists( 'WPSEO_Primary_Term' ) ) {
    $primary_term    = new WPSEO_Primary_Term( $taxonomy, get_the_ID() );
    $primary_term_id = $primary_term->get_primary_term();
}

$primary_term_object = null;
if ( $primary_term_id ) {
    $maybe = get_term( $primary_term_id );
    if ( $maybe && ! is_wp_error( $maybe ) ) {
        $primary_term_object = $maybe;
    }
}
if ( ! $primary_term_object && ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    $primary_term_object = $terms[0];
}
$term_name = $primary_term_object ? $primary_term_object->name : '';

// Intro + content, with anchor IDs injected into in-content h1/h2 for the TOC.
$intro        = get_field( 'intro_key_takeaways' );
$content      = get_field( 'content' );
$full_content = $intro . $content;

if ( $full_content ) {
    $full_content = preg_replace_callback(
        '/<h([1-2])>(.*?)<\/h\1>/',
        function ( $matches ) {
            $id = sanitize_title( $matches[2] );
            return '<h' . $matches[1] . ' id="' . esc_attr( $id ) . '">' . $matches[2] . '</h' . $matches[1] . '>';
        },
        $full_content
    );
}

// Table of contents from the in-content h1/h2 (computed once, reused).
preg_match_all( '/<h[1-2]>(.*?)<\/h[1-2]>/', $intro . $content, $toc_matches, PREG_SET_ORDER );

// Read time from the content field.
$read_words = str_word_count( strip_tags( (string) $content ) );
$read_time  = $read_words ? max( 1, (int) ceil( $read_words / 200 ) ) : 0;

$author_id   = (int) get_the_author_meta( 'ID' );
$author_bio  = get_the_author_meta( 'description', $author_id );
$author_role = get_field( 'job_title', 'user_' . $author_id );

$has_hero_image = has_post_thumbnail();

// Related posts: up to 3 from the same categories.
$related = new WP_Query( [
    'post_type'           => 'post',
    'posts_per_page'      => 3,
    'post__not_in'        => [ get_the_ID() ],
    'category__in'        => wp_get_post_categories( get_the_ID() ),
    'ignore_sticky_posts' => true,
    'no_found_rows'       => true,
] );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'insight-single' ); ?>>

    <div class="insight-hero<?php echo $has_hero_image ? ' has-image' : ''; ?>">
        <?php if ( $has_hero_image ) : ?>
            <div class="insight-hero-bg" aria-hidden="true">
                <?php
                the_post_thumbnail( 'large', [
                    'class'   => 'insight-hero-img',
                    'loading' => 'eager',
                    'alt'     => '',
                ] );
                ?>
                <span class="insight-hero-veil"></span>
            </div>
        <?php endif; ?>

        <div class="container px-4">
            <div class="insight-hero-inner">
                <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]'); ?></div>

                <h1 class="insight-hero-title"><?php the_title(); ?></h1>

                <?php $hero_excerpt = get_the_excerpt(); ?>
                <?php if ( $hero_excerpt ) : ?>
                    <p class="insight-hero-excerpt"><?php echo esc_html( $hero_excerpt ); ?></p>
                <?php endif; ?>

                <div class="insight-hero-meta">
                    <span class="meta-author">By <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a></span>
                    <?php if ( $primary_term_object ) : ?>
                        <span class="meta-dot" aria-hidden="true">·</span>
                        <a class="meta-cat" href="<?php echo esc_url( get_term_link( $primary_term_object ) ); ?>"><?php echo esc_html( $term_name ); ?></a>
                    <?php endif; ?>
                    <span class="meta-dot" aria-hidden="true">·</span>
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
                    <?php if ( $read_time ) : ?>
                        <span class="meta-dot" aria-hidden="true">·</span>
                        <span class="meta-readtime"><?php echo esc_html( $read_time ); ?> min read</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <section class="insight-body">
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-8 insight-content content-area">
                    <?php
                    if ( $full_content ) {
                        echo $full_content;
                    }
                    ?>

                    <?php if ( have_rows( 'faqs' ) ) : ?>
                        <section class="insight-faqs" aria-labelledby="faqs-heading">
                            <h2 id="faqs-heading">FAQs</h2>
                            <?php while ( have_rows( 'faqs' ) ) : the_row();
                                $question = get_sub_field( 'question' );
                                $answer   = get_sub_field( 'answer' );
                                ?>
                                <div class="insight-faq">
                                    <h3><?php echo esc_html( $question ); ?></h3>
                                    <div class="insight-faq-answer"><?php echo wp_kses_post( $answer ); ?></div>
                                </div>
                            <?php endwhile; ?>
                        </section>
                    <?php endif; ?>

                    <div class="insight-author-card">
                        <p class="insight-sidebar-label">Written by</p>
                        <div class="insight-author-head">
                            <span class="insight-author-avatar"><?php echo get_avatar( $author_id, 104 ); ?></span>
                            <span class="insight-author-info">
                                <a class="insight-author-name" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>"><?php the_author(); ?></a>
                                <?php if ( $author_role ) : ?>
                                    <span class="insight-author-role"><?php echo esc_html( $author_role ); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if ( $author_bio ) : ?>
                            <p class="insight-author-desc"><?php echo esc_html( $author_bio ); ?></p>
                            <a class="insight-author-more" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">Read More<span class="visually-hidden"> about <?php echo esc_html( get_the_author_meta( 'display_name', $author_id ) ); ?></span><span class="insight-author-more-arrow" aria-hidden="true">&rarr;</span></a>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="col-lg-4 insight-sidebar">
                    <div class="insight-sticky">
                        <?php if ( ! empty( $toc_matches ) ) : ?>
                            <nav class="insight-toc" aria-label="Table of contents">
                                <p class="insight-sidebar-label">On this page</p>
                                <ul>
                                    <?php
                                    foreach ( $toc_matches as $match ) {
                                        $heading = strip_tags( $match[1] );
                                        $anchor  = sanitize_title( $heading );
                                        echo '<li><a href="#' . esc_attr( $anchor ) . '">' . esc_html( $heading ) . '</a></li>';
                                    }
                                    ?>
                                </ul>
                            </nav>
                            <hr class="insight-rule">
                        <?php endif; ?>

                        <div class="insight-newsletter">
                            <p class="insight-sidebar-label">Subscribe to our newsletter</p>
                            <div class="form">
                                <?php vc_render_form( 3, 'options', 'newsletter_form_id' ); ?>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <?php if ( $related->have_posts() ) : ?>
        <section class="insight-related">
            <div class="container px-4">
                <h2 class="insight-related-heading">More <span>insights</span>.</h2>
                <div class="row g-4">
                    <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                        <?php get_template_part( 'template-parts/content', 'card' ); ?>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</article>

<?php
$faqs = get_field( 'faqs' );

if ( is_singular( 'post' ) && ! empty( $faqs ) && is_array( $faqs ) ) {
    $schema = [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => [],
    ];

    foreach ( $faqs as $row ) {
        if ( empty( $row['question'] ) || empty( $row['answer'] ) ) {
            continue;
        }

        $question    = wp_strip_all_tags( $row['question'] );
        $answer_html = wp_kses_post( $row['answer'] );

        $schema['mainEntity'][] = [
            '@type'          => 'Question',
            'name'           => $question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $answer_html,
            ],
        ];
    }

    if ( ! empty( $schema['mainEntity'] ) ) {
        echo '<script type="application/ld+json">'
            . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
            . '</script>';
    }
}
?>
