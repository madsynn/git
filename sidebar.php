<section id="sidebar">

<?php if( is_single() ) { 
$comments_number = get_comments_number();
$templateURL = get_template_directory_uri();
?>
<div id="post-meta" class="widget">

<div class="tile-sidebar">
    <div class="meta1">
    <img src="<?php echo $templateURL ?>/images/sidebar-comm.png" alt="Comments" />
    <div class="count"><?php echo $comments_number; ?></div>
    <div class="comment-widget">
    <a href="#comments"><div class="comment-count"><?php echo $comments_number; ?></div></a>
    <a href="#respond"><div class="comment-quick-reply">Reply</div></a>
    </div>
    </div><!-- end .meta1 -->
</div><!-- end .tile-sidebar -->

<div class="tile-sidebar" style="background:#3b5998">
    <div class="meta2">
    <img src="<?php echo $templateURL ?>/images/sidebar-fb.png" alt="Facebook" />
    <div class="count"></div>    <div id="fb-root"></div>
    <script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
    <div class="fb-like" data-send="false" data-layout="box_count" data-width="450" data-show-faces="false" data-colorscheme="dark" data-font="segoe ui"></div>
    </div><!-- end .meta2 -->
</div><!-- end .tile-sidebar -->

<div class="tile-sidebar" style="background:#3cf">
    <div class="meta3">
    <img src="<?php echo $templateURL ?>/images/sidebar-twtr.png" alt="Twitter" />
    <div class="count"></div>
    <a href="https://twitter.com/share" class="twitter-share-button" data-count="vertical">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div><!-- end .meta3 -->
</div><!-- end .tile-sidebar -->

</div><!-- end post-meta -->
<?php } elseif ( !is_single() && !is_404() ) {
get_search_form();
} ?>
<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('matrix_sidebar')) : ?>
<?php endif ?>

</section>