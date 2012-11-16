<?php 
get_header(); 
global $data;
?>

<!-- BEGIN MAIN PAGE CONTENT -->
<section class="mainpage">
<?php if ( $data['matrix_home_slider_toggle'] == 1 ) { ?>
	<!-- BEGIN TOGGLE CONTENT -->
	<div class="toggle-button"><span class="toggle-indicator">+</span></div>
    <div class="toggle-content close">
<?php } ?>
        <div class="flexslider mainslide">
        <ul class="slides">
        <?php
		$slides = $data['matrix_home_slider']; //get the slides array

		foreach ($slides as $slide) {
			echo '<li>';
			echo '<img src="'.$slide['url'].'" alt="'.$slide['title'].'" />';
			if ( $slide['link'] != '' ) {
			echo '<a href="'.$slide['link'].'">';
			}
			echo '<p class="flex-title">'.$slide['title'].'</p>';
			if ( $slide['link'] != '' ) {
			echo '</a>';
			}
			if ( $slide['description'] != '' ) {
			echo '<p class="flex-description">'.$slide['description'].'</p>';
			}
			echo '</li>';
		}
		?>
        </ul>
        </div><!-- end .flexslider -->
    
    <div class="quote-bg1"><div class="quote-w"><?php echo do_shortcode(stripslashes($data['matrix_home_highlighted_text'])); ?></div></div>
    
<?php if ( $data['matrix_home_slider_toggle'] == 1 ) { ?>
    </div><!-- end .toggle-content -->
    <!-- END TOGGLE CONTENT -->
<?php } ?>
</section><!-- end #mainpage -->

<!-- BEGIN TILE CONTENT -->
<div id="loader"></div>
<section id="mainpage-mos">
<section id="content-mos" class="centered clearfix">

			<?php if ( have_posts() ) : ?>

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
                
                	<?php get_template_part( 'content-tile' ); ?>
                    
				<?php endwhile; ?>

			<?php else : ?>

				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'matrix' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'matrix' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

<!-- END TILE CONTENT -->
</section><!-- end #content-mos -->
<?php if ( $data['matrix_ajax_pagination_front'] == 1 ) { ?>
<?php matrix_ajax_pagination(); ?>
<?php } ?>
</section><!-- end #mainpage-mos -->
<section class="mainpage">

<?php if ( $data['matrix_home_featured_content_toggle'] == 1 && $data['matrix_home_featured_content'] != '' ) { ?>
<!-- BEGIN TOGGLE CONTENT -->
<div class="toggle-button"><span class="toggle-indicator">+</span></div>
<div class="toggle-content close clearfix">
<?php } ?>
<?php
		$fcontents = $data['matrix_home_featured_content']; //get the slides array
		
		$i = 0;

		foreach ($fcontents as $fcontent) {
			$i++;
		if ( $i % 3 == 0 ){
			$lastcol = 'last';
		} else {
			$lastcol = '';
		}
			echo '<div class="fixed-medium '.$lastcol.'">';
			if ( $fcontent['link'] != '' ) {
			echo '<a href="'.$fcontent['link'].'">';
			}
			if ( $fcontent['url'] != '' ){
			echo '<div class="highlights">';
			echo '<img class="themecolor" src="'.$fcontent['url'].'" alt="'.$fcontent['title'].'" />';
			echo '</div>';
			}
			echo '<div class="highlights-txt">';
			echo '<h2>'.$fcontent['title'].'</h2>';
			if ( $fcontent['description'] != '' ) {
			echo '<p>'.$fcontent['description'].'</p>';
			}
			echo '</div>';
			if ( $fcontent['link'] != '' ) {
			echo '</a>';
			}
			echo '</div>';		
			
		}
?>
<?php if ( $data['matrix_home_featured_content_toggle'] == 1 && $data['matrix_home_featured_content'] != '' ) { ?>
</div><!-- end .toggle-content -->
<!-- END TOGGLE CONTENT -->
<?php } ?>

</section><!-- end .main-page -->
<!-- END MAIN PAGE CONTENT -->

<?php get_footer(); ?>