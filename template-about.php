<?php
/*
Template Name: About
*/
?>
<?php 
global $data;

get_header(); 
?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title"><?php the_title(); ?></div>

<!-- BEGIN PAGE -->
<section id="page">
<!-- BEGIN PAGE IMAGE -->
<img id="page-img" src="<?php echo $data['matrix_about_page_image']; ?>" alt="<?php the_title(); ?>" />
<!-- END PAGE IMAGE -->

<!-- BEGIN PAGE CONTENT -->
<div id="pg-content" class="clearfix">

<div id="page-excerpt">
<?php echo $data['matrix_about_page_excerpt']; ?>
</div>

<div class="page-sub-title">
<h1><?php echo $data['matrix_about_page_team_title']; ?></h1>
<div class="tagline"><?php echo $data['matrix_about_page_team_tagline']; ?></div>
</div>

<div id="about-container">
<?php
		$persons = $data['matrix_about_page_the_team']; //get the slides array
		
		$i = 0;

		foreach ($persons as $person) {
			$i++;
		if ( $i % 4 == 0 ){
			$lastcol = 'last';
		} else {
			$lastcol = '';
		}
			if ( $person['link'] != '' ) {
			echo '<a href="'.$person['link'].'" class="no-text-dec '.$lastcol.'">';
			} else {
			echo '<a href="#" class="no-text-dec '.$lastcol.'">';
			}
			echo '<div class="about-person">';
			if ( $person['url'] != '' ){
			echo '<img class="about-portrait" src="'.$person['url'].'" alt="'.$person['title'].'" />';
			}
			echo '<h3>'.$person['title'].'</h3>';
			echo '<p>'.$person['description'].'</p>';
			echo '</div>';
			echo '</a>';
			
		}
?>

<div class="page-sub-title">
<h1><?php echo $data['matrix_about_page_testimonial_title']; ?></h1>
<div class="tagline"><?php echo $data['matrix_about_page_testimonial_tagline']; ?></div>
</div>

<div id="about-testimonial" class="clearfix">
<?php
		$testimonials = $data['matrix_about_page_testimonials']; //get the slides array
		
		$i = 0;

		foreach ($testimonials as $testimonial) {
			
		if ( $i % 3 == 0 ){
			$lastcol = 'last';
		} else {
			$lastcol = '';
		}
		
		if ( $i == 0 ) {
			echo '<div class="testimonial-1">';
			echo '<div class="quote-w">'.$testimonial['description'].'</div>';
			if ( $testimonial['link'] != '' ) {
			echo '<a href="'.$testimonial['link'].'">';
			}
			echo '<span class="testimonial-author whitetxt">- '.$testimonial['title'].'</span>';
			if ( $testimonial['link'] != '' ) {
			echo '</a>';
			}
			echo '</div>';
		} else {
			echo '<div class="testimonial-s '.$lastcol.'">';
			echo '<p>'.$testimonial['description'].'</p>';
			if ( $testimonial['link'] != '' ) {
			echo '<a href="'.$testimonial['link'].'">';
			}
			echo '<span class="testimonial-author themecolortxt">- '.$testimonial['title'].'</span>';
			if ( $testimonial['link'] != '' ) {
			echo '</a>';
			}
			echo '</div>';
		}
		$i++;
		}
?>
</div><!-- end #about-testimonial -->
</div><!-- end #about-container -->

</div><!-- end #pg-content -->
<!-- END PAGE CONTENT -->
</section>
<!-- END PAGE -->
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>