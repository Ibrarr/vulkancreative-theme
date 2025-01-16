<?php
/*
Template Name: Contact us
*/
get_header();

$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id );
?>

<section class="header">
    <div class="container px-4">

    </div>
</section>

<?php
get_footer();
?>