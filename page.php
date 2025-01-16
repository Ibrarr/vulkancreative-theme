<?php
get_header();
?>

<section class="content">
    <div class="container px-4">
        <h1 class="title"><?php the_title(); ?></h1>
        <div class="content-area"><?php the_content(); ?></div>
    </div>
</section>

<?php
get_footer();
?>