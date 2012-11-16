<?php
$category = get_the_category();
$post_title = get_the_title();
?>
<div class="tile-pre">
    <article id="post-<?php the_ID(); ?>" class="lb-article">
    <div class="lb-quote">
    <?php the_excerpt(); ?> 
    <div class="quote-author">&mdash; <?php the_title(); ?></div>
    </div>
    </article>
</div>