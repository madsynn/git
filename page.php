<?php get_header(); ?>

<!-- BEGIN CONTENT -->
<section id="content" class="clearfix">
<!-- Title --><div id="content-title"><?php the_title(); ?></div>

<!-- BEGIN PAGE -->
<section id="page">
<!-- BEGIN PAGE CONTENT -->
<div id="pg-content" class="clearfix">
<?php the_post(); ?>
<?php the_content(); ?>
</div><!-- end #pg-content -->
<!-- END PAGE CONTENT -->
</section>
<!-- END PAGE -->
</section>
<!-- END CONTENT -->

<?php get_footer(); ?>