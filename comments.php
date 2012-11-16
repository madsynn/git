<!-- BEGIN COMMENTS -->
<section id="comments">
	<?php if ( post_password_required() ) : ?>
		<div class="section-title"><?php _e('Password Required', 'matrix'); ?></div>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'matrix' ); ?></p>
        </section><!-- end #comments -->
        <!-- END COMMENTS -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	
    //Begin comments
	if ( have_comments() ) : ?>
		<div class="section-title">
			<?php
				printf( _n( '1 Comment', '%1$s Comments', get_comments_number(), 'matrix' ), get_comments_number() );
			?>
		</div>

		<ol class="commentlist">
			<?php
				wp_list_comments( array( 'callback' => 'matrix_comment', 'avatar_size' => 80, ) );
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="clearfix">
			<div class="nav-previous"><?php previous_comments_link( __( '&laquo; Older Comments', 'matrix' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &raquo;', 'matrix' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<div class="section-title"><?php _e( 'Comments are closed.', 'matrix' ); ?></div>
        
    <?php
		elseif ( comments_open() && ! have_comments() ) :
	?>
    	<div class="section-title"><?php _e('No Comments', 'matrix'); ?></div>
        
	<?php endif;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$args = array(
		'id_submit' => 'comsubmit',
		'comment_notes_before' => '<div id="commentformleft"><p class="comment-notes">' . __( 'Your email address will not be published.', 'matrix' ) . ( $req ? ' Required fields are marked *' : '' ) . '</p>',
		'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p></div>',
		'logged_in_as' => '<div id="commentformleft"><p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>
		<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p></div>',
		'comment_field' => '<div id="commentformright"><p class="comment-form-comment"><textarea id="comment" class="placeholder" name="comment" aria-required="true" placeholder="' . __( 'Your comment', 'matrix' ) . '"></textarea></p></div>',
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' => '<p class="comment-form-author"><input id="author" class="placeholder" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' placeholder="' . __( 'Name', 'matrix' ) . '' . ( $req ? '*' : '' ) . '"/></p>',
			'email'  => '<p class="comment-form-email"><input id="email" class="placeholder" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '"' . $aria_req . ' placeholder="' . __( 'Email', 'matrix' ) . '' . ( $req ? '*' : '' ) . '"/></p>',
			'url'    => '<p class="comment-form-url"><input id="url" class="placeholder" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" placeholder="' . __( 'Website', 'matrix' ) . '" /></p></div>'
			)
		),
		'comment_notes_after' => ''
		
	
	);
	comment_form($args); ?>

</section><!-- end #comments -->
<!-- END COMMENTS -->