<?php get_header(); ?>
<?php
	$post_id = get_the_ID();
	$post_type = get_post_type();

?>

<?php while ( have_posts() ) : the_post(); ?>

    <!-- BEGIN CONTENT -->
    <section id="content" class="clearfix">
    <!-- Title --><div id="content-title"><?php if ( $post_type == 'matrix_portfolio' ) { _e('Portfolio','matrix'); } else { _e('Blog','matrix'); } ?></div>
    
    <!-- BEGIN SINGLE CONTENT -->
	<section id="single">
    
    <?php
    if ( $post_type == 'matrix_portfolio' ) {
    	get_template_part( 'content', 'portfolio' );
    } else {
    	get_template_part( 'content', 'single' );
	}
    ?>
    
    <?php comments_template( '', true ); ?>
    
    </section><!-- end #single -->
	<!-- END SINGLE CONTENT -->

<?php endwhile; // end of the loop. ?>
<?php get_sidebar(); ?>
    </section>
    <!-- END CONTENT -->


<?php get_footer(); ?>