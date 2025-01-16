<?php
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$categories = get_categories();

get_header();
?>

<section class="blog-heading">
    <div class="container px-4">
        <div class="breadcrumbs"><?php echo do_shortcode('[wpseo_breadcrumb]') ?></div>
        <h1>Posts</h1>
        <p>Posts description.</p>
    </div>
</section>

<section class="posts">
    <div class="container px-4">

    </div>
</section>

<?php
get_footer();
?>