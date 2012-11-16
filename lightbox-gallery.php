<?php
$category = get_the_category();
$post_title = get_the_title();
$post_id = get_the_ID();

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
    <article id="post-<?php the_ID(); ?>" class="lb-article">
    <div class="article-img">
    <?php matrix_img_slider($post_id, 'full'); ?>
    </div>
    <div class="article-date <?php echo $show_post_colour; ?>txt" <?php if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; } ?>><span class="date"><?php echo get_the_date( 'j' ); ?></span><span class="month"><?php echo get_the_date( 'M' ); ?></span></div>
    <h1 class="lb-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    <span class="postcat <?php echo $show_post_colour; ?>txt" <?php if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; } ?>><?php if (!$category) { echo 'Uncategorized'; } else { echo $category[0]->cat_name; }?></span>
    <div class="lb-excerpt">
        <?php the_excerpt(); ?> 
        <p><a class="exp-button" href="<?php the_permalink(); ?>">Read More &#8594;</a></p>
    </div>
    </article>
</div>