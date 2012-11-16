<?php get_header(); ?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title">404</div>

<!-- BEGIN LEFT CONTENT -->
<section id="single">
<div class="sbp-article">

    <div class="sbp-content">
    <p><?php _e('Sorry, but the page is not found here. Perhaps you can try using the search box to search for it.', 'matrix') ?></p>
    
    <div id="searchbox">
	<?php get_search_form(); ?>
    </div>
	</div>
    
</div>

</section>
<!-- END LEFT CONTENT -->

<?php get_sidebar(); ?>
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>