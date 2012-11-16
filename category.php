<?php get_header(); ?>
<?php
global $data;

$tile_type = $data['matrix_archive_tile_type'];
?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title"><?php single_cat_title( '', true ) ?></div>

<!-- BEGIN LEFT CONTENT -->
<section id="bloglist-left" class="clearfix">
<?php if ( $tile_type != 'list' ) { ?>
<div id="content-mos">
<?php } ?>
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part('archive',$tile_type); ?>
    
    
<?php endwhile; ?>
<?php else : ?>
<div class="sbp-content">
<p><?php _e('Sorry, no results were found for the requested category.', 'matrix') ?></p>
</div>

<?php endif; ?>
<?php if ( $tile_type != 'list' ) { ?>
</div>
<?php } ?>

<!-- BEGIN PAGINATION -->
<?php matrix_pagination(); ?>
<!-- END PAGINATION -->

</section>
<!-- END LEFT CONTENT -->

<?php get_sidebar(); ?>
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>