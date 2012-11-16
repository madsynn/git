<?php get_header(); ?>
<?php
global $data;

$tile_type = $data['matrix_archive_tile_type'];
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">

<!-- Title -->
<div id="content-title">
<?php printf( __( 'Author : %s', 'matrix' ), '<span class="vcard">' . $curauth->display_name . '</span>' ); ?>
</div>
<!-- BEGIN LEFT CONTENT -->
<section id="bloglist-left" class="clearfix">
<div id="authorinfo" class="authorpage"><?php echo get_avatar( $curauth->ID, 80 ); ?>
    <span class="author"><a href="<?php $curauth->user_url; ?>"><?php echo $curauth->display_name ?></a></span>
    <p><?php echo $curauth->description; ?></p>
</div>
<?php if ( have_posts() ) : ?>
<?php if ( $tile_type != 'list' ) { ?>
<div id="content-mos">
<?php } ?>
<?php while ( have_posts() ) : the_post(); ?>

<?php get_template_part('archive',$tile_type); ?>
    
    
<?php endwhile; ?>
<?php if ( $tile_type != 'list' ) { ?>
</div>
<?php } ?>

<?php else : ?>
<!-- Title -->
<div class="sbp-content">
<p><?php _e('Sorry, this author has not posted anything yet.', 'matrix') ?></p>
</div>

<?php endif; ?>

<!-- BEGIN PAGINATION -->
<?php matrix_pagination(); ?>
<!-- END PAGINATION -->

</section>
<!-- END LEFT CONTENT -->

<?php get_sidebar(); ?>
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>