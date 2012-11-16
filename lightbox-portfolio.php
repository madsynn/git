<?php
$category = get_the_category();
$post_title = get_the_title();
$post_id = get_the_ID();
$post_format = get_post_format();

$post_colour = get_post_meta( $post_id, '_post_colour', true );
$post_colour_pick = get_post_meta( $post_id, '_post_colour_pick', true );
$post_colour_picker_check = get_post_meta( $post_id, '_post_colour_picker_check', true );
if ( $post_colour_picker_check != 'on' ){
	if (!$post_colour && $post_colour_picker_check != 'on' ) { $post_colour = 'themecolor'; }
	$show_post_colour = $post_colour;
	} else {
		$show_post_colour = '';
}

?>
<div class="tile-pre">
    <article id="post-<?php the_ID(); ?>" class="lb-portfolio">
    <div class="portfolio-img">
    <?php 
    if ( has_post_thumbnail() ) {
		$thumb_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
		echo '<img class="portfolio-pre-img" src="'. $thumb_url[0] .'" alt="<?php echo '.$post_title.'; ?>" />';
	} elseif ( $post_format == 'gallery' ) {
	matrix_img_slider($post_id, 'full');	
	}
	?>
    </div>
    <div class="lb-port-cont">
        <h1 class="lb-project"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        <span class="projectcat <?php echo $show_post_colour; ?>txt" <?php if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; } ?>><?php if (!$category) { echo 'Uncategorized'; } else { echo $category[0]->cat_name; }?></span>
        <div class="lb-desc">
        	<?php the_excerpt(); ?> 
            <p><a class="exp-button" href="<?php the_permalink(); ?>">View More &#8594;</a></p>
        </div>
    </div>
    </article>
</div>