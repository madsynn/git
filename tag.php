<?php get_header(); ?>
<?php
global $data;

$tile_type = $data['matrix_archive_tile_type'];
$tagname = single_tag_title( '', false );

?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title -->
<div id="content-title">
<?php _e( 'Tag : ', 'matrix' ); echo $tagname;?>
</div>

<!-- BEGIN LEFT CONTENT -->
<section id="bloglist-left" class="clearfix">
<?php if ( $tile_type != 'list' ) { ?>
<div id="content-mos">
<?php } ?>
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part('archive',$tile_type); ?>
    
<!-- BEGIN PAGINATION -->
<?php matrix_pagination(); ?>
<!-- END PAGINATION -->
    
<?php endwhile; ?>
<?php else : ?>
<div class="sbp-content">
<p><?php _e('Sorry, no results were found for the requested tag.', 'matrix') ?></p>
</div>

<?php endif; ?>
<?php if ( $tile_type != 'list' ) { ?>
</div>
<?php } ?>
</section>
<!-- END LEFT CONTENT -->

<?php get_sidebar(); ?>
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>