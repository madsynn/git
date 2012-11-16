<?php
global $data;
?>
<!-- BEGIN FOOTER -->
<footer class="clearfix">

<div id="footer-social">
<?php social_bartender(); ?>
<a href="#"><span class="behance-mini"></span></a>
<a href="#"><span class="twitter-mini"></span></a>
<a href="#"><span class="facebook-mini"></span></a>
<a href="#"><span class="linkedin-mini"></span></a>
<a href="#"><span class="pinterest-mini"></span></a>
<a href="#"><span class="dribbble-mini"></span></a>
</div><!-- end #footer-social -->

<small><?php echo $data['matrix_footer_text']; ?></small>
</footer>
<!-- END FOOTER -->

</section><!-- end #container -->
<?php if ( $data['matrix_background_pattern'] == 1 ){ ?>
</div><!-- end #bodypat -->
<?php } ?>

<?php wp_footer(); ?>

<?php echo $data['matrix_tracking_code']; ?>
</body>
</html>