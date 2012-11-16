<?php get_header(); ?>
<?php
global $data;

$tile_type = $data['matrix_archive_tile_type'];

global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();

foreach($query_args as $key => $string) {
	$query_split = explode("=", $string);
	$search_query[$query_split[0]] = urldecode($query_split[1]);
} // foreach

$search = new WP_Query($search_query);

global $wp_query;
$total_results = $wp_query->found_posts;
$search_term = get_search_query();
?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title"><?php echo $total_results; _e(' Results for ','matrix'); echo $search_term;?></div>

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
<p><?php _e('Sorry, no results were found for the requested query.', 'matrix') ?></p>
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