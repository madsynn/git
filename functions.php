<?php
/*
 * Load back-end stuffs
 */

/* Load all javascript files into the theme */
function matrix_load_scripts() {
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script( 'masonry',
						get_template_directory_uri() .  '/scripts/jquery.masonry.min.js' );
	wp_enqueue_script( 'easing',
						get_template_directory_uri() .  '/scripts/jquery.easing.1.3.js' );
	wp_enqueue_script( 'metrojs',
						get_template_directory_uri() .  '/scripts/MetroJs.lt.js' );
	wp_enqueue_script( 'fancybox',
						get_template_directory_uri() .  '/scripts/jquery.fancybox-1.3.4.js' );
	wp_enqueue_script( 'flexslider',
						get_template_directory_uri() .  '/scripts/jquery.flexslider-min.js' );
	wp_enqueue_script( 'hoverintent',
						get_template_directory_uri() .  '/scripts/hoverintent.js' );
	wp_enqueue_script( 'organictabs',
						get_template_directory_uri() .  '/scripts/organictabs.jquery.js' );
	wp_enqueue_script( 'jplayer',
						get_template_directory_uri() .  '/scripts/jquery.jplayer.min.js' );
	wp_enqueue_script( 'cssmediaqueries',
						get_template_directory_uri() .  '/scripts/css3-mediaqueries.js' );
	wp_enqueue_script( 'themescript',
						get_template_directory_uri() .  '/scripts/javascript.js' );
	wp_enqueue_script( 'validate',
						get_template_directory_uri() .  '/scripts/jquery.validate.min.js' );
}    
 
add_action('wp_enqueue_scripts', 'matrix_load_scripts');

/* Load translation */
add_action('after_setup_theme', 'matrix_theme_setup');
function matrix_theme_setup(){
    load_theme_textdomain('matrix', get_template_directory() . '/languages');
}

/* Load options css file */
function matrix_add_init() {
$file_dir=get_template_directory_uri();  
wp_enqueue_style("functions", $file_dir."/functionscss.css", false, "1.0", "all");
}
add_action('admin_init', 'matrix_add_init');

/* Load update notification */
include("update_notifier.php");

/*
 * Enable front-end stuffs
 */

/* Load the navigation menu */
function register_matrix_menu() {
  register_nav_menus(
    array( 'header-menu' => __( 'Header Menu', 'matrix' ) )
  );
}
add_action( 'init', 'register_matrix_menu' );

/* Add post formats */
add_theme_support( 'post-formats', array( 'gallery', 'link', 'quote', 'video', 'audio' ) );

/* Add thumbnails */
add_theme_support( 'post-thumbnails' );

/* Add feed links */
add_theme_support( 'automatic-feed-links' );

/* Add sidebar */
function matrix_widgets_init() {
register_sidebar(array(
	'name'          => __( 'Right Sidebar', 'matrix' ),
	'id'            => 'matrix_sidebar',
	'description'   => __( 'Sidebar located at the right of Blog / Portfolio pages', 'matrix'),
        'class'         => '',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h5>',
	'after_title'   => '</h5>' )
	);
}
add_action( 'widgets_init', 'matrix_widgets_init' );

/* Set maximum image / video size */
if ( ! isset( $content_width ) ) $content_width = 640;

/*
 * Custom widgets
 */

/* Add Matrix widgets */
add_action( 'widgets_init', 'register_matrix_widget' );
function register_matrix_widget() {
    register_widget( 'Matrix_Widget_Recent_Posts' );
	register_widget("Matrix_Widget_Twitter");
}
/* Matrix Recent Posts */
class Matrix_Widget_Recent_Posts extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'matrix_widget_recent_entries', 'description' => __( "The most recent posts on your site, displayed in 'Matrix' style", 'matrix') );
		parent::__construct('matrix-recent-posts', __('Matrix Latest Posts', 'matrix'), $widget_ops);
		$this->alt_option_name = 'matrix_widget_recent_entries';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('widget_recent_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Latest Articles', 'matrix') : $instance['title'], $instance, $this->id_base);
		if ( ! $number = absint( $instance['number'] ) )
 			$number = 10;

		$r = new WP_Query(array('posts_per_page' => $number, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true));
		if ($r->have_posts()) :
?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>
		<ul class="articles-widget">
		<?php  while ($r->have_posts()) : $r->the_post(); ?>
        <?php
		$post_title = get_the_title();
		$post_id = get_the_ID();
		$category = get_the_category($post_id);
		if (!$category) { $getcategory = 'Uncategorized'; } else { $getcategory = $category[0]->cat_name; }
		$url = get_template_directory_uri();
		$tile_img_front = get_post_meta( $post_id, '_tile_img_front', true );
		$tile_img_back = get_post_meta( $post_id, '_tile_img_back', true );
		$tile_size = get_post_meta( $post_id, '_tile_size', true );
		$tile_icon_sel = get_post_meta( $post_id, '_tile_icon', true );
		$tile_icon_check = get_post_meta( $post_id, '_tile_icon_check', true );

		$tile_colour = get_post_meta( $post_id, '_tile_colour', true );
		$tile_colour_pick = get_post_meta( $post_id, '_tile_colour_pick', true );
		$colour_picker_check = get_post_meta( $post_id, '_colour_picker_check', true );
		if ( !$tile_img_front && !$tile_img_back && $colour_picker_check != 'on' ){
			if (!$tile_colour && $colour_picker_check != 'on' ) { $tile_colour = 'themecolor'; }
			$show_tile_colour = $tile_colour;
		} else {
			$show_tile_colour = '';
		}
		
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
		<li <?php if ($colour_picker_check == 'on' && $tile_colour_pick != '' ) { echo 'style="background-color:'.$tile_colour_pick.';"'; } ?> class="<?php echo $show_tile_colour; ?>"><a href="<?php the_permalink() ?>" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>">
        <?php
		if ( $tile_img_front != '' || $tile_img_back != '' || has_post_thumbnail() ) {
		if ( $tile_img_front != '' || $tile_img_back != '' && $tile_size == 'medium' ) {
			if ( $tile_img_front != '' ) {
				$imgsrc = $tile_img_front;
			} else {
				$imgsrc = $tile_img_back;
			}
		} elseif ( has_post_thumbnail() ) {
			$imgsrc1 = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'full' );
			$imgsrc = $imgsrc1[0];
		} elseif ( $tile_img_front != '' || $tile_img_back != '' ) {
			if ( $tile_img_front != '' ) {
				$imgsrc = $tile_img_front;
			} else {
				$imgsrc = $tile_img_back;
			}
		}
		echo '<img class="recent-thumb" src="'. $imgsrc .'" alt="'.$post_title.'" />';
		echo '<div class="title">'.$post_title.'<br/><span class="'.$show_post_colour.'txt"';
		if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; }
		echo '>'.$getcategory.'</span></div>';
		echo '<div class="more"></div>';
		} elseif ( $tile_icon_check == 'on' ) {
		echo '<img class="icon-img" src="'.$url.'/images/'.$tile_icon_sel.'.png" alt="'.$post_title.'" />';
		echo '<div class="title">'.$post_title.'<br/><span class="'.$show_post_colour.'txt"';
		if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; }
		echo '>'.$getcategory.'</span></div>';
		echo '<div class="more"></div>';
		} else {
		echo '<div class="show-title">'.$post_title.'<br/><span class="'.$show_post_colour.'txt"';
		if ($post_colour_picker_check == 'on' && $post_colour_pick != '' ) { echo 'style="color:'.$post_colour_pick.';"'; }
		echo '>'.$getcategory.'</span></div>';
		echo '<div class="more"></div>';
		}
        ?>
        </a></li>
		<?php endwhile; ?>
		</ul>
		<?php echo $after_widget; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('widget_recent_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('widget_recent_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'matrix'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'matrix'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
/* Twitter Widget */
class Matrix_Widget_Twitter extends WP_Widget
{
  function Matrix_Widget_Twitter()
  {
    $widget_ops = array('classname' => 'Matrix_Widget_Twitter', 'description' => __( "Get latest tweets", 'matrix') );
    $this->WP_Widget('Matrix_Widget_Twitter', __( "Matrix Twitter Widget", 'matrix'), $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => __( "Latest Tweets", 'matrix'), 'username' => '', 'tweetnum' => '5' ) );
    $title = $instance['title'];
	$username = $instance['username'];
	$tweetnum = $instance['tweetnum'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title : ', 'matrix') ?>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username : ', 'matrix') ?>
	<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('tweetnum'); ?>"><?php _e('Number of Tweets : ', 'matrix') ?>
	<input class="widefat" id="<?php echo $this->get_field_id('tweetnum'); ?>" name="<?php echo $this->get_field_name('tweetnum'); ?>" type="text" value="<?php echo $tweetnum; ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );
	$instance['username'] = strip_tags( $new_instance['username'] );
	$instance['tweetnum'] = strip_tags( $new_instance['tweetnum'] );
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args);
 
    echo $before_widget;
	
    $title = apply_filters('widget_title', $instance['title'] );
	$username = $instance['username'];
	$tweetnum = $instance['tweetnum'];
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
	
	?>
    <script>
	//Username
		var user = '<?php echo $username; ?>';
	//Number of tweets to be displayed
		var tweetcount = <?php echo $tweetnum; ?>;
	//Twitter on sidebar
		jQuery.getJSON('https://api.twitter.com/1/statuses/user_timeline.json?screen_name=' + user + '&count=' + tweetcount + '&callback=?', function(data){
        // Result
        var tweet = "";
		for (i = 0; i < data.length; i++) {
		tweet += "<li>" + data[i].text + "</li>";
		}
      
        // Links
        tweet = tweet.replace(/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig, function(url) {
            return '<a target="_blank" href="'+url+'">'+url+'</a>';
        }).replace(/B@([_a-z0-9]+)/ig, function(reply) {
            return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
        });
        // Output
        jQuery("#tweeter").html(tweet);
		});
	</script>
	<?php
	echo '<div id="twitter">';
	if (!$username){
		_e('Please enter a username', 'matrix');
	}
	if (!$tweetnum){
		_e('Please enter number of tweets', 'matrix');
	}
	echo '<ul id="tweeter"></ul>';
	echo '</div>';
    echo $after_widget;
  }
 
}
/*
 * Theme options
 */
require_once ('admin/index.php');

/*
 * Social Bartender
 */
require_once ('social-bartender/social-bartender.php');

/*
 * Post Edit Options
 */

/* Load colour picker */
add_action('init', 'matrix_farbtastic_script');
function matrix_farbtastic_script() {
  wp_enqueue_style( 'farbtastic' );
  wp_enqueue_script( 'farbtastic' );
}

/* Load the meta boxes in the post editor screen */
add_action( 'load-post.php', 'matrix_meta_box' );
add_action( 'load-post-new.php', 'matrix_meta_box' );

/* Meta box setup function */
function matrix_meta_box() {

	/* Add meta boxes on the 'add_meta_boxes' hook */
	add_action( 'add_meta_boxes', 'matrix_tile' );
	add_action( 'add_meta_boxes', 'matrix_post_slider' );
	add_action( 'add_meta_boxes', 'matrix_portfolio_func' );
	add_action( 'add_meta_boxes', 'matrix_upload_image_func' );
	add_action( 'add_meta_boxes', 'matrix_post_settings_func' );
	
	/* Save post meta on the 'save_post' hook */
	add_action( 'save_post', 'matrix_save_tile_settings', 10, 2 );
	add_action( 'save_post', 'matrix_save_post_slider_settings', 10, 2 );
	add_action( 'save_post', 'matrix_save_portfolio_settings', 10, 2 );
	add_action( 'save_post', 'matrix_save_media_settings', 10, 2 );
	add_action( 'save_post', 'matrix_save_post_settings', 10, 2 );

}
function admin_scripts()
{
   wp_enqueue_script('media-upload');
   wp_enqueue_script('thickbox');
}

function admin_styles()
{
   wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'admin_scripts');
add_action('admin_print_styles', 'admin_styles');

/* Create one or more meta boxes to be displayed on the post editor screen */
function matrix_tile() {

	add_meta_box(
		'matrix-tile',			// Unique ID
		esc_html__( 'Tile Settings', 'matrix' ),		// Title
		'matrix_tile_settings',		// Callback function
		'post',					// Admin page (or post type)
		'normal',					// Context
		'high'					// Priority
	);
	add_meta_box(
		'matrix-tile',			// Unique ID
		esc_html__( 'Tile Settings', 'matrix' ),		// Title
		'matrix_tile_settings',		// Callback function
		'matrix_portfolio',					// Admin page (or post type)
		'normal',					// Context
		'high'					// Priority
	);
}

function matrix_post_slider() {
	
	add_meta_box(
		'matrix-post-slider',			// Unique ID
		esc_html__( 'Slider Settings', 'matrix' ),		// Title
		'matrix_post_slider_settings',		// Callback function
		'post',					// Admin page (or post type)
		'normal',					// Context
		'high'					// Priority
	);
	add_meta_box(
		'matrix-post-slider',			// Unique ID
		esc_html__( 'Slider Settings', 'matrix' ),		// Title
		'matrix_post_slider_settings',		// Callback function
		'matrix_portfolio',					// Admin page (or post type)
		'normal',					// Context
		'high'					// Priority
	);

}

function matrix_portfolio_func() {
	
	add_meta_box(
		'matrix-portfolio-settings',			// Unique ID
		esc_html__( 'Portfolio Settings', 'matrix' ),		// Title
		'matrix_portfolio_settings',		// Callback function
		'matrix_portfolio',					// Admin page (or post type)
		'normal',					// Context
		'high'					// Priority
	);

}

function matrix_upload_image_func() {
	
	add_meta_box(
		'matrix-upload-image',			// Unique ID
		esc_html__( 'Media Management', 'matrix' ),		// Title
		'matrix_upload_image',		// Callback function
		'post',					// Admin page (or post type)
		'side',					// Context
		'high'					// Priority
	);
	add_meta_box(
		'matrix-upload-image',			// Unique ID
		esc_html__( 'Media Management', 'matrix' ),		// Title
		'matrix_upload_image',		// Callback function
		'matrix_portfolio',					// Admin page (or post type)
		'side',					// Context
		'high'					// Priority
	);
}

function matrix_post_settings_func() {
	add_meta_box(
		'matrix-post-settings',			// Unique ID
		esc_html__( 'Post Settings', 'matrix' ),		// Title
		'matrix_post_settings',		// Callback function
		'post',					// Admin page (or post type)
		'side',					// Context
		'high'					// Priority
	);
	add_meta_box(
		'matrix-post-settings',			// Unique ID
		esc_html__( 'Post Settings', 'matrix' ),		// Title
		'matrix_post_settings',		// Callback function
		'matrix_portfolio',					// Admin page (or post type)
		'side',					// Context
		'high'					// Priority
	);
}

/* Display the tile meta box */
function matrix_tile_settings( $object, $box ) {
	
	$options_array = get_option('matrix_options');

	global $post;  
    $live_tile_speed = get_post_meta( $post->ID, '_live_tile_speed', true );
	if (!$live_tile_speed) { $live_tile_speed = '1000'; }
    $live_tile_dir_sel = get_post_meta( $post->ID, '_live_tile_dir', true );
	$live_tile_mode_sel = get_post_meta( $post->ID, '_live_tile_mode', true );
	$tile_colour_sel = get_post_meta( $post->ID, '_tile_colour', true );
	$tile_colour_pick = get_post_meta( $post->ID, '_tile_colour_pick', true );
	if (!$tile_colour_pick) { $tile_colour_pick = 'none'; }
	$colour_picker_check = get_post_meta( $post->ID, '_colour_picker_check', true );
    $live_check = get_post_meta( $post->ID, '_live_tile_check', true );
	$live_data_stack = get_post_meta( $post->ID, '_live_data_stack', true );
	$live_data_stops = get_post_meta( $post->ID, '_live_data_stops', true );
	if (!$live_data_stops) { $live_data_stops = '0%,100%'; }
	$live_data_delay = get_post_meta( $post->ID, '_live_data_delay', true );
	if (!$live_data_delay) { $live_data_delay = '0'; }
	$tile_img_front = get_post_meta( $post->ID, '_tile_img_front', true );
	$tile_img_back = get_post_meta( $post->ID, '_tile_img_back', true );
	$list_img_front = get_post_meta( $post->ID, '_list_img_front', true );
	$archive_tile_img_front = get_post_meta( $post->ID, '_archive_tile_img_front', true );
	$lightbox_check = get_post_meta( $post->ID, '_lightbox_check', true );
	if (!$lightbox_check) { $lightbox_check = 'on'; };
	$tile_size = get_post_meta( $post->ID, '_tile_size', true );
	if (!$tile_size && $options_array['matrix_archive_tile_type'] != 'list') { $tile_size = $options_array['matrix_archive_tile_type']; } elseif ( !$tile_size ) { $tile_size = 'large'; };
	$tile_display = get_post_meta( $post->ID, '_tile_display', true );
	$tile_icon_sel = get_post_meta( $post->ID, '_tile_icon', true );
	$tile_icon_check = get_post_meta( $post->ID, '_tile_icon_check', true );
	$tile_date_check = get_post_meta( $post->ID, '_tile_date_check', true );
	if (!$tile_date_check ) { $tile_date_check = 'on'; };
	
	wp_nonce_field( basename( __FILE__ ), 'matrix_tile_nonce' ); ?>
    
    <table class="form-table">
    <tbody>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Lightbox", 'matrix' ); ?>
        </th>
    	<td>
    	<input type="checkbox" id="lightbox_check" name="_lightbox_check" <?php checked( $lightbox_check, 'on' ); ?> />  
        <label for="lightbox_check"><?php _e( "Enable lightbox", 'matrix' ); ?></label> 
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Tile size", 'matrix' ); ?>
        </th>
    	<td>
    	<input type="radio" id="tile_size" name="_tile_size" <?php checked( $tile_size, 'small' ); ?> value="small"/><?php _e( "Small", 'matrix' ); ?>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        
        </th>
        <td>
        <input type="radio" id="tile_size" name="_tile_size" <?php checked( $tile_size, 'medium' ); ?> value="medium"/><?php _e( "Medium", 'matrix' ); ?>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        
        </th>
        <td>
        <input type="radio" id="tile_size" name="_tile_size" <?php checked( $tile_size, 'large' ); ?> value="large"/><?php _e( "Large", 'matrix' ); ?>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Date Display", 'matrix' ); ?>
        </th>
    	<td>
    	<input type="checkbox" id="tile_date_check" name="_tile_date_check" <?php checked( $tile_date_check, 'on' ); ?> />  
        <label for="tile_date_check"><?php _e( "Show date on tile", 'matrix' ); ?></label> 
        </td>
    </tr>
    </tbody>
    </table>
    
    <strong><p><?php _e( "Tile Animation", 'matrix' ); ?></p></strong>
    <p><?php _e( "Settings for the animation of this tile", 'matrix' ); ?></p>
    <table class="form-table">
    <tbody>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Tile Animation", 'matrix' ); ?>
        </th>
        <td>
    	<input type="checkbox" id="live_tile_check" name="_live_tile_check" <?php checked( $live_check, 'on' ); ?> />  
        <label for="live_tile_check"><?php _e( "Enable tile animation", 'matrix' ); ?></label>
        </td>
    </tr>
    </tbody>
    </table>
    <script>
	jQuery(document).ready(function(){
		if ( jQuery('#live_tile_check').is(':checked') ) {
			jQuery('#live_settings').show();
		} else {
			jQuery('#live_settings').hide();
		}
		jQuery('#live_tile_check').click(function(){
			jQuery('#live_settings').slideToggle(this.checked);
		});
	});
	</script>
    <div id="live_settings">
    
    <table class="form-table">
    <tbody>
    <tr valign="top">
        <th scope="row">
        <label for="live_tile_mode"><?php _e( "Mode of animation", 'matrix' ); ?></label>
        </th>
        <td>
        <select name="_live_tile_mode" id="live_tile_mode">  
            <option value="slide" <?php selected( $live_tile_mode_sel, 'slide' ); ?>><?php _e( "Slide", 'matrix' ); ?></option>  
            <option value="flip" <?php selected( $live_tile_mode_sel, 'flip' ); ?>><?php _e( "Flip", 'matrix' ); ?></option>  
        </select>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<label for="live_tile_dir"><?php _e( "Direction of animation", 'matrix' ); ?></label>
        </th>
        <td>
        <select name="_live_tile_dir" id="live_tile_dir">  
            <option value="horizontal" <?php selected( $live_tile_dir_sel, 'horizontal' ); ?>><?php _e( "Horizontal", 'matrix' ); ?></option>  
            <option value="vertical" <?php selected( $live_tile_dir_sel, 'vertical' ); ?>><?php _e( "Vertical", 'matrix' ); ?></option>  
        </select>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<label for="live_tile_speed"><?php _e( "Speed of animation (millisecond)", 'matrix' ); ?></label>
        </th>
        <td>
    	<input type="text" name="_live_tile_speed" id="live_tile_speed" value="<?php echo $live_tile_speed ?>"/>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<label for="live_data_delay"><?php _e( "Data delay (millisecond)", 'matrix' ); ?></label>
        </th>
        <td>
    	<input type="text" name="_live_data_delay" id="live_data_delay" value="<?php echo $live_data_delay ?>"/>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<label for="live_data_stops"><?php _e( "Data stops (percentage)", 'matrix' ); ?></label>
        </th>
        <td>
    	<input type="text" name="_live_data_stops" id="live_data_stops" value="<?php echo $live_data_stops ?>"/>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<?php _e( "Tile Image Stacking", 'matrix' ); ?>
        </th>
        <td>
        <input type="checkbox" id="live_data_stack" name="_live_data_stack" <?php checked( $live_data_stack, 'on' ); ?> />
        <label for="live_data_stack"><?php _e( "Make tile move synchronously", 'matrix' ); ?></label> 
        </td>
    </tr>
    </tbody>
    </table>
    </div><!-- #live_settings -->
    
    <p><strong><?php _e( "Tile Display", 'matrix' ); ?></strong></p>
    <p><?php _e( "Only upload front image if tile is not animated. Click <strong>Insert into Post</strong> after uploading.", 'matrix' ); ?></p>
    <script>
	var storeSendToEditor = '';
	var newSendToEditor = '';
	jQuery(document).ready(function(){
			
			storeSendToEditor = window.send_to_editor;

			jQuery('.upload_image_button').click(function() {
				window.send_to_editor = newSendToEditor;
				targetfield = jQuery(this).next('.upload-url');
				previewID = jQuery(this).next('.upload-url').attr('id');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;referer=matrix-settings');
				return false;
			});
		 
			newSendToEditor = function(html) {
				 imgurl = jQuery('img',html).attr('src');
				 jQuery(targetfield).val(imgurl);
				 jQuery('img#'+previewID).attr('src',imgurl);
				 tb_remove();
				 window.send_to_editor = storeSendToEditor;
			}
			jQuery('#matrixcolourpicker').hide();
			jQuery('#matrixcolourpicker').farbtastic("#color");
			jQuery("#color").click(function(){jQuery('#matrixcolourpicker').slideToggle()});		

	});
	</script>
    <table class="form-table">
    <tbody>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Front Image", 'matrix' ); ?>
        </th>
        <td>
    	<input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_tile_img_front" id="tile_img_front" class="upload-url regular-text" value="<?php echo $tile_img_front ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        </th>
        <td class="upload-preview">
        <img id="tile_img_front" src="<?php echo $tile_img_front ?>" alt="Preview" />
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
        <?php _e( "Back Image", 'matrix' ); ?>
        </th>
        <td>
    	<input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_tile_img_back" id="tile_img_back" class="upload-url regular-text" value="<?php echo $tile_img_back ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        </th>
        <td class="upload-preview">
        <img id="tile_img_back" src="<?php echo $tile_img_back ?>" alt="Preview" />
        </td>
    </tr>
    </tbody>
    </table>
    <p><?php _e( "Or use options below if you don't have a tile image. These settings won't be applied if there is a tile image assigned above.", 'matrix' ); ?></p>
    <table class="form-table">
    <tbody>
	<tr valign="top">
    	<th scope="row">
    	<?php _e( "Choose a colour", 'matrix' ); ?>
        </th>
        <td>
        <select name="_tile_colour" id="tile_colour">
        	<option value="themecolor" <?php selected( $tile_colour_sel, 'themecolor' ); ?>><?php _e( "Theme Colour", 'matrix' ); ?></option>
            <option value="blue" <?php selected( $tile_colour_sel, 'blue' ); ?>><?php _e( "Blue", 'matrix' ); ?></option>
            <option value="brown" <?php selected( $tile_colour_sel, 'brown' ); ?>><?php _e( "Brown", 'matrix' ); ?></option>
            <option value="green" <?php selected( $tile_colour_sel, 'green' ); ?>><?php _e( "Green", 'matrix' ); ?></option>
            <option value="lime" <?php selected( $tile_colour_sel, 'lime' ); ?>><?php _e( "Lime", 'matrix' ); ?></option>
            <option value="magenta" <?php selected( $tile_colour_sel, 'magenta' ); ?>><?php _e( "Magenta", 'matrix' ); ?></option>
            <option value="mango" <?php selected( $tile_colour_sel, 'mango' ); ?>><?php _e( "Mango", 'matrix' ); ?></option>
            <option value="pink" <?php selected( $tile_colour_sel, 'pink' ); ?>><?php _e( "Pink", 'matrix' ); ?></option>
            <option value="purple" <?php selected( $tile_colour_sel, 'purple' ); ?>><?php _e( "Purple", 'matrix' ); ?></option>
            <option value="red" <?php selected( $tile_colour_sel, 'red' ); ?>><?php _e( "Red", 'matrix' ); ?></option>
            <option value="teal" <?php selected( $tile_colour_sel, 'teal' ); ?>><?php _e( "Teal", 'matrix' ); ?></option>
        </select>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
        <?php _e( "Colour picker", 'matrix' ); ?>
        </th>
        <td>
        <input type="checkbox" id="colour_picker_check" name="_colour_picker_check" <?php checked( $colour_picker_check, 'on' ); ?> />  
        <label for="colour_picker_check"><?php _e( "Use value from colour picker : ", 'matrix' ); ?></label>
        <input type="text" id="color" name="_tile_colour_pick" value="<?php echo $tile_colour_pick; ?>" />
        <div id="matrixcolourpicker"></div>
        </td>
    </tr>
	<tr valign="top">
    	<th scope="row">
    	<?php _e( "Choose an icon (optional)", 'matrix' ); ?>
        </th>
        <td>
        <input type="checkbox" id="tile_icon_check" name="_tile_icon_check" <?php checked( $tile_icon_check, 'on' ); ?> />
        <label for="tile_icon_check"><?php _e( "Use icon", 'matrix' ); ?></label>
        <select name="_tile_icon" id="tile_icon">
        	<option value="quote-dark" <?php selected( $tile_icon_sel, 'quote-dark' ); ?>><?php _e( "Dark Quote", 'matrix' ); ?></option>
            <option value="quote-white" <?php selected( $tile_icon_sel, 'quote-white' ); ?>><?php _e( "White Quote", 'matrix' ); ?></option>
            <option value="video" <?php selected( $tile_icon_sel, 'video' ); ?>><?php _e( "Video", 'matrix' ); ?></option>
            <option value="hyperlink" <?php selected( $tile_icon_sel, 'hyperlink' ); ?>><?php _e( "Hyperlink", 'matrix' ); ?></option>
            <option value="password" <?php selected( $tile_icon_sel, 'password' ); ?>><?php _e( "Password", 'matrix' ); ?></option>
        </select>
        </td>
    </tr>
    </tbody>
    </table>

	<p><strong><?php _e( "Thumbnail Image for Archive", 'matrix' ); ?></strong></p>
    <p><?php _e( "This image is used at the archives as the thumbnail. Please upload the correct thumbnail size for the tile type set in the theme settings page. Note that you may need to reupload all thumbnails when you change the tile type for archive in order to have them displayed correctly.", 'matrix' ); ?></p>
    <p><?php _e('Current tile type : ', 'matrix'); echo $options_array['matrix_archive_tile_type']; ?>
	<table class="form-table">
    <tbody>
    <?php if ( $options_array['matrix_archive_tile_type'] == 'list' ) { ?>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "List Image", 'matrix' ); ?>
        </th>
        <td>
    	<input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_list_img_front" id="list_img_front" class="upload-url regular-text" value="<?php echo $list_img_front ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        </th>
        <td class="upload-preview">
        <img id="list_img_front" src="<?php echo $list_img_front ?>" alt="Preview" />
        </td>
    </tr>
    <?php } else { ?>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Tile Image", 'matrix' ); ?>
        </th>
        <td>
    	<input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_archive_tile_img_front" id="archive_tile_img_front" class="upload-url regular-text" value="<?php echo $archive_tile_img_front ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        </th>
        <td class="upload-preview">
        <img id="archive_tile_img_front" src="<?php echo $archive_tile_img_front ?>" alt="Preview" />
        </td>
    </tr>
    <?php } ?>
    </tbody>
    </table>
    
<?php }

/* Display the post slider meta box */
function matrix_post_slider_settings( $object, $box ){
	
	wp_nonce_field( basename( __FILE__ ), 'matrix_post_slider_nonce' ); ?>
	
    <strong><p><?php _e( "Upload images for slider", 'matrix' ); ?></p></strong>
    <p><?php _e( "Note : If a featured image is set, only the featured image is displayed. The slider will be disabled.", 'matrix' ); ?></p>
    <script>
	jQuery(document).ready(function(){
				
			jQuery('#upload_slider_button').click(function() {
				 tb_show('', 'media-upload.php?post_id=<?php global $post; echo $post->ID; ?>&type=image&amp;TB_iframe=true&amp;referer=matrix-settings');
				 return false;
			});

	});
	</script>
    <input id="upload_slider_button" type="button" value="<?php _e( "Upload Slider Images", 'matrix' ); ?>" />
    <p><?php _e( "Images displayed at this post's slider :", 'matrix' ); ?></p>
    <?php
	$postid = get_the_ID();
	matrix_img_slider($postid, 'thumbnail'); 
	?>
	
<?php }

/* Display the portfolio meta box */
function matrix_portfolio_settings( $object, $box ){
	
	global $post;
	$portfolio_full_img = get_post_meta( $post->ID, '_portfolio_full_img', true );
	$portfolio_date = get_post_meta( $post->ID, '_portfolio_date', true );
	$portfolio_client = get_post_meta( $post->ID, '_portfolio_client', true );
	$portfolio_client_link = get_post_meta( $post->ID, '_portfolio_client_link', true );
	
	wp_nonce_field( basename( __FILE__ ), 'matrix_portfolio_nonce' ); ?>
	
    <script>
	var storeSendToEditor = '';
	var newSendToEditor = '';
	jQuery(document).ready(function(){
			
			storeSendToEditor = window.send_to_editor;

			jQuery('.upload_image_button').click(function() {
				window.send_to_editor = newSendToEditor;
				targetfield = jQuery(this).next('.upload-url');
				previewID = jQuery(this).next('.upload-url').attr('id');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true&amp;referer=matrix-settings');
				return false;
			});
		 
			newSendToEditor = function(html) {
				 imgurl = jQuery('img',html).attr('src');
				 jQuery(targetfield).val(imgurl);
				 jQuery('img#'+previewID).attr('src',imgurl);
				 tb_remove();
				 window.send_to_editor = storeSendToEditor;
			}

	});
	</script>
    <table class="form-table">
    <tbody>
	<tr valign="top">
    	<th scope="row">
    	<?php _e( "Full-size Image", 'matrix' ); ?>
        </th>
        <td>
    	<input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_portfolio_full_img" id="portfolio_full_img" class="upload-url regular-text" value="<?php echo $portfolio_full_img ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        </th>
        <td class="upload-preview">
        <img id="portfolio_full_img" src="<?php echo $portfolio_full_img ?>" alt="Preview" />
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Date of item", 'matrix' ); ?>
        </th>
        <td>
        <input type="text" name="_portfolio_date" id="portfolio_date" value="<?php echo $portfolio_date ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Client Name", 'matrix' ); ?>
        </th>
        <td>
        <input type="text" name="_portfolio_client" id="portfolio_client" value="<?php echo $portfolio_client ?>"/>
        </td>
    </tr>
    <tr valign="top">
    	<th scope="row">
        <?php _e( "Client Website (optional)", 'matrix' ); ?>
        </th>
        <td>
        <input type="text" name="_portfolio_client_link" id="portfolio_client_link" value="<?php echo $portfolio_client_link ?>"/>
        </td>
    </tr>
    </tbody>
    </table>
<?php }

/* Display the post settings meta box */
function matrix_post_settings( $object, $box ){
	
	global $post;
	$post_colour_sel = get_post_meta( $post->ID, '_post_colour', true );
	$post_colour_pick = get_post_meta( $post->ID, '_post_colour_pick', true );
	if (!$post_colour_pick) { $post_colour_pick = 'none'; }
	$post_colour_picker_check = get_post_meta( $post->ID, '_post_colour_picker_check', true );
	$post_hyperlink = get_post_meta( $post->ID, '_post_hyperlink', true );
	$embed_field = get_post_meta( $post->ID, '_embed_field', true );
	$embed_field_alt = get_post_meta( $post->ID, '_embed_field_alt', true );
	$video_poster = get_post_meta( $post->ID, '_video_poster', true );
	$embed_width = get_post_meta( $post->ID, '_embed_width', true );
	$embed_height = get_post_meta( $post->ID, '_embed_height', true );


	wp_nonce_field( basename( __FILE__ ), 'matrix_post_settings_nonce' ); ?>
    
    <script>
	jQuery(document).ready(function(){
				
			jQuery('#matrixcolourpicker2').hide();
			jQuery('#matrixcolourpicker2').farbtastic("#color2");
			jQuery("#color2").click(function(){jQuery('#matrixcolourpicker2').slideToggle()});
			
			if ( jQuery('#post-format-video').is(':checked') || jQuery('#post-format-audio').is(':checked') ) {
					jQuery('#matrix_embed').show();
				} else {
					jQuery('#matrix_embed').hide();
				}
				jQuery('#post-format-link').is(':checked') ? jQuery("#matrix_hyperlink").show() : jQuery("#matrix_hyperlink").hide();
				
			jQuery('.post-format').change(function(){
				if ( jQuery('#post-format-video').is(':checked') || jQuery('#post-format-audio').is(':checked') ) {
					jQuery('#matrix_embed').slideDown();
				} else {
					jQuery('#matrix_embed').slideUp();
				}
				jQuery('#post-format-link').is(':checked') ? jQuery("#matrix_hyperlink").slideDown() : jQuery("#matrix_hyperlink").slideUp();

			});
			

	});
	</script>

    <p><strong><?php _e('Post\'s Colour', 'matrix'); ?></strong></p>
    <p><?php _e('Used as the colour for the post\'s category and date on the tile, and in its single page.', 'matrix'); ?></p>
    <table class="form-table">
    <tbody>
	<tr valign="top">
    	<th scope="row">
    	<?php _e( "Choose a colour", 'matrix' ); ?>
        </th>
        <td>
        <select name="_post_colour" id="post_colour">
        	<option value="themecolor" <?php selected( $post_colour_sel, 'themecolor' ); ?>><?php _e( "Theme Colour", 'matrix' ); ?></option>
            <option value="blue" <?php selected( $post_colour_sel, 'blue' ); ?>><?php _e( "Blue", 'matrix' ); ?></option>
            <option value="brown" <?php selected( $post_colour_sel, 'brown' ); ?>><?php _e( "Brown", 'matrix' ); ?></option>
            <option value="green" <?php selected( $post_colour_sel, 'green' ); ?>><?php _e( "Green", 'matrix' ); ?></option>
            <option value="lime" <?php selected( $post_colour_sel, 'lime' ); ?>><?php _e( "Lime", 'matrix' ); ?></option>
            <option value="magenta" <?php selected( $post_colour_sel, 'magenta' ); ?>><?php _e( "Magenta", 'matrix' ); ?></option>
            <option value="mango" <?php selected( $post_colour_sel, 'mango' ); ?>><?php _e( "Mango", 'matrix' ); ?></option>
            <option value="pink" <?php selected( $post_colour_sel, 'pink' ); ?>><?php _e( "Pink", 'matrix' ); ?></option>
            <option value="purple" <?php selected( $post_colour_sel, 'purple' ); ?>><?php _e( "Purple", 'matrix' ); ?></option>
            <option value="red" <?php selected( $post_colour_sel, 'red' ); ?>><?php _e( "Red", 'matrix' ); ?></option>
            <option value="teal" <?php selected( $post_colour_sel, 'teal' ); ?>><?php _e( "Teal", 'matrix' ); ?></option>
        </select>
        </td>
    </tr>
    <tr valign="top">
        <th scope="row">
        <?php _e( "Colour picker", 'matrix' ); ?>
        </th>
        <td>
        <input type="checkbox" id="post_colour_picker_check" name="_post_colour_picker_check" <?php checked( $post_colour_picker_check, 'on' ); ?> />  
        <label for="post_colour_picker_check"><?php _e( "Use value from colour picker : ", 'matrix' ); ?></label>
        <input type="text" id="color2" name="_post_colour_pick" value="<?php echo $post_colour_pick; ?>" />
        <div id="matrixcolourpicker2"></div>
        </td>
    </tr>
    </tbody>
    </table>
    <div id="matrix_embed">
    <strong><p><?php _e( "Embed", 'matrix' ); ?></p></strong>
    <p><?php _e( "These fields support iframe embedding, these video formats: ogv, m4v and these audio formats: oga, ogg, mp3.", 'matrix' ); ?></p>
    <p><?php _e( "Enter the url to be embedded into the field below. It will be displayed at the top of the post when the post format is <strong>Audio</strong> or <strong>Video</strong>.", 'matrix' ); ?></p>
    <input type="text" name="_embed_field" id="embed_field" class="matrix_media_input" value="<?php echo $embed_field; ?>"/>
    <p><?php _e( "If you are using your own audio / video, you may wish to use an alternative file format for maximum browser support.", 'matrix' ); ?></p>
    <input type="text" name="_embed_field_alt" id="embed_field_alt" class="matrix_media_input" value="<?php echo $embed_field_alt; ?>"/>
    <p><?php _e( "Upload a poster image for your own video", 'matrix' ); ?></p>
    <input class="upload_image_button" type="button" value="<?php _e( "Upload", 'matrix' ); ?>" />
    	<input type="text" name="_video_poster" id="video_poster" class="upload-url" value="<?php echo $video_poster ?>"/>
    <p><?php _e( "Enter the size of the video to override the default 16:9 ratio.", 'matrix' ); ?></p>
    <div class="row">
    <label for="embed_width" id="embed_width_label"><?php _e( "Width", 'matrix' ); ?></label>
    <input type="text" name="_embed_width" id="embed_width" value="<?php echo $embed_width; ?>"/>
    <label for="embed_width">px</label>
    </div>
    <div class="row">
    <label for="embed_height" id="embed_height_label"><?php _e( "Height", 'matrix' ); ?></label>
    <input type="text" name="_embed_height" id="embed_height" value="<?php echo $embed_height; ?>"/>
    <label for="embed_height">px</label>
    </div>
    </div><!-- end #matrix_embed -->

	<div id="matrix_hyperlink">
    <strong><p><?php _e( "Hyperlink", 'matrix' ); ?></p></strong>
    <p><?php _e( "When the post format is set to <strong>Link</strong>, users will be redirected to the link entered in the field below when they click on the tile.", 'matrix' ); ?></p>
	<label for="post_hyperlink" id="post_hyperlink_label"><?php _e( "Link", 'matrix' ); ?></label>
    <input type="text" name="_post_hyperlink" id="post_hyperlink" value="<?php echo $post_hyperlink; ?>"/>
    </div><!-- end #matrix_hyperlink -->
	
<?php }

/* Display the upload image meta box */
function matrix_upload_image( $object, $box ){
	
	wp_nonce_field( basename( __FILE__ ), 'matrix_upload_image_nonce' ); ?>
    
    <p><?php _e( "Use this button to upload images if you do not want these images to be added into the slider / gallery", 'matrix' ); ?></p>
    <script>
	jQuery(document).ready(function(){
				
			jQuery('.upload_slider_button').click(function() {
				 tb_show('', 'media-upload.php?post_id=&type=image&amp;TB_iframe=true&amp;referer=matrix-settings');
				 return false;
			});

	});
	</script>
    <input class="upload_slider_button" type="button" value="<?php _e( "Upload Custom Images", 'matrix' ); ?>" />
    <p><?php _e( "To use these images in the post, copy and paste the 'Link URL' of each image into the corresponding field in WordPress's media uploader.", 'matrix' ); ?></p>

<?php }

/* Save the meta box's post metadata. */
function matrix_save_post_settings( $post_id, $post ) {

	// Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['matrix_post_settings_nonce'] ) || !wp_verify_nonce( $_POST['matrix_post_settings_nonce'], basename( __FILE__ ) ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
	
    // now we can actually save the data  
    $allowed = array(   
        'a' => array( // on allow a tags  
            'href' => array() // and those anchors can only have href attribute  
        )  
    );  
      
    // Make sure your data is set before trying to save it
	if( isset( $_POST['_post_colour'] ) )  
        update_post_meta( $post_id, '_post_colour', esc_attr( $_POST['_post_colour'] ) );
	if( isset( $_POST['_post_colour_pick'] ) )  
        update_post_meta( $post_id, '_post_colour_pick', esc_attr( $_POST['_post_colour_pick'] ) );
	if( isset( $_POST['_post_hyperlink'] ) )  
        update_post_meta( $post_id, '_post_hyperlink', wp_kses( $_POST['_post_hyperlink'], $allowed ) );
	if( isset( $_POST['_embed_field'] ) )  
        update_post_meta( $post_id, '_embed_field', wp_kses( $_POST['_embed_field'], $allowed ) );
	if( isset( $_POST['_embed_field_alt'] ) )  
        update_post_meta( $post_id, '_embed_field_alt', wp_kses( $_POST['_embed_field_alt'], $allowed ) );
	if( isset( $_POST['_video_poster'] ) )  
        update_post_meta( $post_id, '_video_poster', wp_kses( $_POST['_video_poster'], $allowed ) );
	if( isset( $_POST['_embed_width'] ) )  
        update_post_meta( $post_id, '_embed_width', wp_kses( $_POST['_embed_width'], $allowed ) );
	if( isset( $_POST['_embed_height'] ) )  
        update_post_meta( $post_id, '_embed_height', wp_kses( $_POST['_embed_height'], $allowed ) );



	
	$chk = isset( $_POST['_post_colour_picker_check'] ) && $_POST['_post_colour_picker_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_post_colour_picker_check', $chk );

}

function matrix_save_media_settings( $post_id, $post ) {

	// Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['matrix_upload_image_nonce'] ) || !wp_verify_nonce( $_POST['matrix_upload_image_nonce'], basename( __FILE__ ) ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
	
    // now we can actually save the data  
    $allowed = array(   
        'a' => array( // on allow a tags  
            'href' => array() // and those anchors can only have href attribute  
        )  
    );  
      
    // Make sure your data is set before trying to save it  
}

function matrix_save_portfolio_settings( $post_id, $post ) {

	// Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['matrix_portfolio_nonce'] ) || !wp_verify_nonce( $_POST['matrix_portfolio_nonce'], basename( __FILE__ ) ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
	
    // now we can actually save the data  
    $allowed = array(   
        'a' => array( // on allow a tags  
            'href' => array() // and those anchors can only have href attribute  
        )  
    );  
      
    // Make sure your data is set before trying to save it  
	if( isset( $_POST['_portfolio_full_img'] ) )  
        update_post_meta( $post_id, '_portfolio_full_img', wp_kses( $_POST['_portfolio_full_img'], $allowed ) );
		
    if( isset( $_POST['_portfolio_date'] ) )  
        update_post_meta( $post_id, '_portfolio_date', wp_kses( $_POST['_portfolio_date'], $allowed ) );
		
	if( isset( $_POST['_portfolio_client'] ) )  
        update_post_meta( $post_id, '_portfolio_client', wp_kses( $_POST['_portfolio_client'], $allowed ) );
		
	if( isset( $_POST['_portfolio_client_link'] ) )  
        update_post_meta( $post_id, '_portfolio_client_link', wp_kses( $_POST['_portfolio_client_link'], $allowed ) );
      
}

function matrix_save_post_slider_settings( $post_id, $post ) {

	// Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['matrix_post_slider_nonce'] ) || !wp_verify_nonce( $_POST['matrix_post_slider_nonce'], basename( __FILE__ ) ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
      
}

function matrix_save_tile_settings( $post_id, $post ) {

	// Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
     
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['matrix_tile_nonce'] ) || !wp_verify_nonce( $_POST['matrix_tile_nonce'], basename( __FILE__ ) ) ) return; 
     
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
      
    // now we can actually save the data  
    $allowed = array(   
        'a' => array( // on allow a tags  
            'href' => array() // and those anchors can only have href attribute  
        )  
    );  
      
    // Make sure your data is set before trying to save it  
    if( isset( $_POST['_live_tile_speed'] ) )  
        update_post_meta( $post_id, '_live_tile_speed', wp_kses( $_POST['_live_tile_speed'], $allowed ) );
		
	if( isset( $_POST['_live_data_stops'] ) )  
        update_post_meta( $post_id, '_live_data_stops', wp_kses( $_POST['_live_data_stops'], $allowed ) );
		
	if( isset( $_POST['_live_data_delay'] ) )  
        update_post_meta( $post_id, '_live_data_delay', wp_kses( $_POST['_live_data_delay'], $allowed ) );
		
	if( isset( $_POST['_tile_img_front'] ) )  
        update_post_meta( $post_id, '_tile_img_front', wp_kses( $_POST['_tile_img_front'], $allowed ) );
		
	if( isset( $_POST['_tile_img_back'] ) )  
        update_post_meta( $post_id, '_tile_img_back', wp_kses( $_POST['_tile_img_back'], $allowed ) );
		
	if( isset( $_POST['_list_img_front'] ) )  
        update_post_meta( $post_id, '_list_img_front', wp_kses( $_POST['_list_img_front'], $allowed ) );
		
	if( isset( $_POST['_archive_tile_img_front'] ) )  
        update_post_meta( $post_id, '_archive_tile_img_front', wp_kses( $_POST['_archive_tile_img_front'], $allowed ) );
          
    if( isset( $_POST['_live_tile_mode'] ) )  
        update_post_meta( $post_id, '_live_tile_mode', esc_attr( $_POST['_live_tile_mode'] ) );  
          
	if( isset( $_POST['_live_tile_dir'] ) )  
        update_post_meta( $post_id, '_live_tile_dir', esc_attr( $_POST['_live_tile_dir'] ) ); 
		
	if( isset( $_POST['_tile_size'] ) )  
        update_post_meta( $post_id, '_tile_size', esc_attr( $_POST['_tile_size'] ) );
		
	if( isset( $_POST['_tile_colour'] ) )  
        update_post_meta( $post_id, '_tile_colour', esc_attr( $_POST['_tile_colour'] ) );
		
	if( isset( $_POST['_tile_icon'] ) )  
        update_post_meta( $post_id, '_tile_icon', esc_attr( $_POST['_tile_icon'] ) );
		
	if( isset( $_POST['_tile_colour_pick'] ) )  
        update_post_meta( $post_id, '_tile_colour_pick', esc_attr( $_POST['_tile_colour_pick'] ) );
		
	if( isset( $_POST['_tile_display'] ) )  
        update_post_meta( $post_id, '_tile_display', esc_attr( $_POST['_tile_display'] ) );
		
    // This is purely my personal preference for saving check-boxes  
    $chk = isset( $_POST['_live_tile_check'] ) && $_POST['_live_tile_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_live_tile_check', $chk );
	$chk1 = isset( $_POST['_live_data_stack'] ) && $_POST['_live_data_stack'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_live_data_stack', $chk1 );
	$chk2 = isset( $_POST['_lightbox_check'] ) && $_POST['_lightbox_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_lightbox_check', $chk2 );
	$chk3 = isset( $_POST['_colour_picker_check'] ) && $_POST['_colour_picker_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_colour_picker_check', $chk3 );
	$chk4 = isset( $_POST['_tile_icon_check'] ) && $_POST['_tile_icon_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_tile_icon_check', $chk4 );
	$chk5 = isset( $_POST['_tile_date_check'] ) && $_POST['_tile_date_check'] ? 'on' : 'off';  
    update_post_meta( $post_id, '_tile_date_check', $chk5 );
}

/*
 * Shortcodes
 */

// Quote
add_shortcode("quote", "matrix_quote");
function matrix_quote( $atts, $content = null ) {
	return '<div class="quote">'.$content.'</div>';
}

// Columns
add_shortcode("col_title", "matrix_col_title");
function matrix_col_one_half( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-half"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_half", "matrix_col_one_half");
function matrix_col_one_half_last( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-half last"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_half_last", "matrix_col_one_half_last");
function matrix_col_one_third( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-third"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_third", "matrix_col_one_third");
function matrix_col_one_third_last( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-third last"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_third_last", "matrix_col_one_third_last");
function matrix_col_two_thirds( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="two-thirds"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("two_thirds", "matrix_col_two_thirds");
function matrix_col_two_thirds_last( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="two-thirds last"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("two_thirds_last", "matrix_col_two_thirds_last");
function matrix_col_one_fourth( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-fourth"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_fourth", "matrix_col_one_fourth");
function matrix_col_one_fourth_last( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="one-fourth last"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("one_fourth_last", "matrix_col_one_fourth_last");
function matrix_col_three_fourths( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="three-fourths"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("three_fourths", "matrix_col_three_fourths");
function matrix_col_three_fourths_last( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => '',
	), $atts));
	return '<div class="three-fourths last"><h3>'.$title.'</h3>'.do_shortcode( $content ).'</div>';
}
add_shortcode("three_fourths_last", "matrix_col_three_fourths_last");

// Add separator
function matrix_vert_separator() {
	return '<hr class="break">';
}
add_shortcode("vertical_separator", "matrix_vert_separator");

// Add buttons
function matrix_buttons( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'color' => 'themecolor',
		'link' => '',
	), $atts));
	return '<a href="'.$link.'"><span class="button-met '.$color.'">'.$content.'</span></a>';
}
add_shortcode("button", "matrix_buttons");

// Add tab
function matrix_tab_parent( $atts, $content ){
$GLOBALS['tab_count'] = 0;

do_shortcode( $content );

if( is_array( $GLOBALS['tabs'] ) ){
	$i = 0;
foreach( $GLOBALS['tabs'] as $tab ){
	$i++;
	if ($i == 1) {
		$current = 'current';
		$hidden = '';
	} else {
		$current = '';
		$hidden = 'hide';
	}
$tabs[] = '<li><a href="#tab-'.$i.'" class="'.$current.'">'.$tab['title'].'</a></li>';
$panes[] = '<div id="tab-'.$i.'" class="p-tab '.$hidden.' '.$tab['class'].'">'.$tab['content'].'</div>';
}
$return = "\n".'<div class="orgTab"><ul class="tab-nav">'.implode( "\n", $tabs ).'</ul>'."\n".'<div class="list-wrap">'.implode( "\n", $panes ).'</div></div><!--  -->'."\n";
}
return $return;
}
add_shortcode( 'tabgroup', 'matrix_tab_parent' );

function matrix_tabs( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Tab %d',
'class' => '',
), $atts));

$x = $GLOBALS['tab_count'];
$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'class' => sprintf( $class, $GLOBALS['tab_count'] ), 'content' => $content );

$GLOBALS['tab_count']++;
}
add_shortcode( 'tab', 'matrix_tabs' );

// Add table
function matrix_table( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Title',
'price' => '48',
'currency' => '$',
'duration' => 'yr'
), $atts));

$GLOBALS['tab_count'] = 0;

do_shortcode( $content );

if( is_array( $GLOBALS['tabs'] ) ){
foreach( $GLOBALS['tabs'] as $tab ){
	if ( $tab['content'] != '' ) {
$tabs[] = '<li>'.$tab['item'].'<span class="toggle-indicator">+</span></li><li class="table-details">'.$tab['content'].'</li>';
	} else {
$tabs[] = '<li>'.$tab['item'].'</li>';
	}
}

$return = "\n".'<div class="table-content"><div class="table-title">'.$title.'</div><div class="table-price"><span class="price"><span class="price-pre">'.$currency.'</span>'.$price.'<span class="price-post">/'.$duration.'</span></span></div>'."\n".'<div class="table-info"><ul>'.implode( "\n", $tabs ).'</ul></div></div><!--  -->'."\n";
}
return $return;
}
add_shortcode( 'table', 'matrix_table' );

function matrix_table_rows( $atts, $content ){
extract(shortcode_atts(array(
'item' => 'Row %d',
), $atts));

$x = $GLOBALS['tab_count'];
$GLOBALS['tabs'][$x] = array( 'item' => sprintf( $item, $GLOBALS['tab_count'] ), 'content' =>  $content );

$GLOBALS['tab_count']++;
}
add_shortcode( 'row', 'matrix_table_rows' );

// Add accordion
function matrix_accordion( $atts, $content ){

$GLOBALS['tab_count'] = 0;

do_shortcode( $content );

if( is_array( $GLOBALS['tabs'] ) ){
	$i = 0;
foreach( $GLOBALS['tabs'] as $tab ){
	$i++;
	if ($i == 1){
$tabs[] = '<div class="ac-tab">'.$tab['title'].'<span class="toggle-indicator">-</span></div><div class="toggle-content"><p>'.$tab['content'].'</p></div>';
	} else {
$tabs[] = '<div class="ac-tab">'.$tab['title'].'<span class="toggle-indicator">+</span></div><div class="toggle-content close"><p>'.$tab['content'].'</p></div>';
	}
}
$return = "\n".'<div class="accordion">'.implode( "\n", $tabs ).'</div><!--  -->'."\n";
}
return $return;
}
add_shortcode( 'accordion', 'matrix_accordion' );

function matrix_accordion_rows( $atts, $content ){
extract(shortcode_atts(array(
'title' => 'Tab %d',
), $atts));

$x = $GLOBALS['tab_count'];
$GLOBALS['tabs'][$x] = array( 'title' => sprintf( $title, $GLOBALS['tab_count'] ), 'content' =>  $content );

$GLOBALS['tab_count']++;
}
add_shortcode( 'ac_row', 'matrix_accordion_rows' );

// Add toggles
function matrix_toggles( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'title' => 'Toggle',
		'state' => 'open',
	), $atts));
	if ( $state == 'open' ) {
	return '<div class="toggle-button">'.$title.'<span class="toggle-indicator">-</span></div><div class="toggle-content"><p>'.$content.'</p></div>';
	} else {
	return '<div class="toggle-button">'.$title.'<span class="toggle-indicator">+</span></div><div class="toggle-content close"><p>'.$content.'</p></div>';
	}
}
add_shortcode("toggle", "matrix_toggles");

// Add infobox
function matrix_infobox( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'color' => 'blue',
	), $atts));
	return '<div class="infobox-'.$color.'"><span class="infobox-msg">'.$content.'</span></div>';
}
add_shortcode("infobox", "matrix_infobox");

// Add paragraph highlights
function matrix_phl( $atts, $content = null ) {
	extract(shortcode_atts(array(
		'style' => '1',
	), $atts));
	return '<p class="hl'.$style.'">'.$content.'</p>';
}
add_shortcode("phl", "matrix_phl");

// Add dropcap
function matrix_dropcap( $atts, $content = null ) {
	return '<p class="dropcap">'.$content.'</p>';
}
add_shortcode("dropcap", "matrix_dropcap");



// Add the TinyMCE icons
add_action('init', 'add_button');
function add_button() {  
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') && get_user_option('rich_editing') == true)
   {
     add_filter('mce_external_plugins', 'add_plugin');
     add_filter('mce_buttons_3', 'register_button_1');
	 add_filter('mce_buttons', 'register_button_2');
   }
}

function register_button_1($buttons) {
   array_push($buttons, "columns", "|", "buttons", "|", "tabgroup", "tabs", "|", "table", "rows", "|", "accordion", "ac_row", "toggles", "|", "infobox", "phl");
   return $buttons;
}

function register_button_2($buttons) {
   array_push($buttons, "|", "dropcap", "quote", "vertical_separator");
   return $buttons;
}

function add_plugin($plugin_array) {
   $plugin_array['matrix_shortcodes'] = get_template_directory_uri().'/scripts/customcodes.js';
   $plugin_array['dropdown'] = get_template_directory_uri().'/scripts/customcodes.js';
   $plugin_array['buttons'] = get_template_directory_uri().'/scripts/customcodes.js';
   $plugin_array['infobox'] = get_template_directory_uri().'/scripts/customcodes.js';
   $plugin_array['phl'] = get_template_directory_uri().'/scripts/customcodes.js';
   $plugin_array['tabs'] = get_template_directory_uri().'/scripts/customcodes.js';
   return $plugin_array;
}

// Fix empty p problem
add_filter('the_content', 'shortcode_empty_paragraph_fix');
function shortcode_empty_paragraph_fix($content)
{   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );

    $content = strtr($content, $array);

    return $content;
}
	
/*
 * Custom Portfolio
 */

//Add Portfolio
add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'matrix_portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolios', 'matrix' ),
				'singular_name' => __( 'Portfolio', 'matrix' )
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'comments',
			'custom-fields',
			'revisions',
			'post-formats'
			),
		'taxonomies' => array('category', 'post_tag'),
		'rewrite' => array("slug" => "portfolio"),
		)
	);
	register_taxonomy_for_object_type('category', 'matrix_portfolio');
	register_taxonomy_for_object_type('post_tag', 'matrix_portfolio');

}

//Add Portfolio Messages
add_filter('post_updated_messages', 'portfolio_updated_messages');
function portfolio_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['book'] = array(
    0 => '', // Unused. Messages start at index 1.
    1 => sprintf( __('Portfolio updated. <a href="%s">View item</a>', 'matrix'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Custom field updated.', 'matrix'),
    3 => __('Custom field deleted.', 'matrix'),
    4 => __('Portfolio updated.', 'matrix'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('Portfolio restored to revision from %s', 'matrix'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Portfolio published. <a href="%s">View item</a>', 'matrix'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Portfolio saved.', 'matrix'),
    8 => sprintf( __('Portfolio submitted. <a target="_blank" href="%s">Preview item</a>', 'matrix'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview item</a>', 'matrix'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i', 'matrix' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Portfolio draft updated. <a target="_blank" href="%s">Preview item</a>', 'matrix'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//Display Portfolio Items
add_filter( 'pre_get_posts', 'display_matrix_portfolio' );

function display_matrix_portfolio( $query ) {
	
	$var = false;

	if (isset($query->query_vars['suppress_filters'])){
      $var = $query->query_vars['suppress_filters'];
	}
	if ( is_home() && false ==$var ){
      $query->set( 'post_type', array( 'post', 'matrix_portfolio') );
    }
	return $query;
}

add_filter('pre_get_posts', 'query_post_type');
function query_post_type($query) {
 if ( is_archive() && empty( $query->query_vars['suppress_filters'] ) ) {
    $post_type = get_query_var('post_type');
	if($post_type)
	    $post_type = $post_type;
	else
	    $post_type = array('post','matrix_portfolio');
    $query->set('post_type',$post_type);
	return $query;
    }
}

/*
 * Custom functions
 */

// Add slider for posts
function matrix_img_slider($postid, $imgsize){
$args = array(
	'post_type'   => 'attachment',
	'numberposts' => -1,
	'post_status' => null,
	'post_parent' => $postid,
	'exclude'     => get_post_thumbnail_id(),
	'orderby'     => 'menu_order ID',
    'order'       => 'ASC'
	);

$attachments = get_posts( $args );

if ( !empty($attachments) ) {
	echo '<div class="flexslider postslide"><ul class="slides">';
	foreach ( $attachments as $attachment ) {
		$imgsrc = wp_get_attachment_image_src( $attachment->ID, $imgsize );
		echo '<li><img class="tile-pre-img" src="' . $imgsrc[0] . '" /></li>';
	}
	echo '</ul></div>';
}
}

// Add audio / video for posts
function matrix_audio_video($id, $src, $altsrc, $poster, $width, $height){
	
$filetype = wp_check_filetype($src);
$filetypealt = wp_check_filetype($altsrc);
if ( is_home() ) { $loc = '#fancybox-content'; } else { $loc = ''; }

if ( $filetype['ext'] == 'ogv' || $filetype['ext'] == 'm4v' ) { ?>
<!--instantiate-->
<script type="text/javascript">
jQuery(document).ready(function(){
	// Set width of control bar
	var vpWidth = jQuery(window).width();
		if ( vpWidth < 590 ) {
			jQuery('article#post-<?php echo $id; ?> .jp-controls-holder').css('width',(vpWidth-20) + 'px');
			var newWidth = vpWidth - 310;
			jQuery('article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px'); 
		} else {
			jQuery('article#post-<?php echo $id; ?> .jp-progress').css('width', 270 + 'px'); 
		}
	jQuery('a.lightbox').click(function(){
		var vpWidth = jQuery(window).width();
		if ( vpWidth < 590 ) {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',(vpWidth-20) + 'px');
			var newWidth = vpWidth - 320;
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px'); 
		} else {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',570 + 'px');
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', 270 + 'px');
		}
	});
	jQuery(window).resize(function(){
		var vpWidth = jQuery('article#post-<?php echo $id; ?> .lb-video').width();
		if ( vpWidth < 570 ) {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',vpWidth + 'px');
			var newWidth = vpWidth - 300;
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px'); 
		} else {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',570 + 'px');
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', 270 + 'px');
		}
	});
	
	// Set height of video
	<?php if ( $width != '' && $height !='' ) { ?>
	var oriWidth = <?php echo $width; ?>;
	var oriHeight = <?php echo $height; ?>;
	var ratio = oriHeight / oriWidth;
	
	jQuery('a.lightbox').click(function(){
		var vpWidth = jQuery(window).width();
		if ( vpWidth < 660 ) {
			var newHeight = ratio * (vpWidth-20);
			jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
		}
	});
	jQuery(window).resize(function(){
		var vpWidth = jQuery('article#post-<?php echo $id; ?> .lb-video').width();
		if ( vpWidth < oriWidth ) {
			var newHeight = ratio * vpWidth;
			jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
		}
	});
	<?php } else { ?>
	jQuery('a.lightbox').click(function(){
		var vpWidth = jQuery(window).width();
		if ( vpWidth < 660 ) {
			var newHeight = 0.5625 * (vpWidth-20);
			jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
		} else {
			jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',360 + 'px');
		}
	});
	jQuery(window).resize(function(){
		var vpWidth = jQuery('article#post-<?php echo $id; ?> .lb-video').width();
		if ( vpWidth < 640 ) {
			var newHeight = 0.5625 * vpWidth;
			jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
		}
	});
	<?php } ?>

    jQuery("#jquery_jplayer_<?php echo $id ?>").jPlayer({
        ready: function () {
            jQuery(this).jPlayer("setMedia", {
                <?php echo $filetype['ext']; ?>: "<?php echo $src; ?>" <?php if ( $altsrc != '' ) { ?>,
                <?php echo $filetypealt['ext']; ?>: "<?php echo $altsrc; ?>" <?php } if ( $poster != '' ) { ?>,
                poster: "<?php echo $poster; ?>" <?php } ?>
            });
        },
        swfPath: "<?php echo get_template_directory_uri(); ?>/scripts",
		cssSelectorAncestor: "#jp_container_<?php echo $id ?>",
        supplied: "<?php if ( $filetype['ext'] != '' ) { echo $filetype['ext']; } ?>, <?php if ( $filetypealt['ext'] != '' ) { echo $filetypealt['ext']; } ?>",
		size: {
            width: "100%",
            height: "inherit",
        }
    });
	
	//Set initial height
	<?php if ( $width != '' && $height !='' ) { ?>
	var vpWidth = jQuery(window).width();
	if (vpWidth > oriWidth) {
	jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',oriHeight + 'px');
	} else {
		var newHeight = ratio * vpWidth;
	jQuery("#single #jquery_jplayer_<?php echo $id ?>, #single #jquery_jplayer_<?php echo $id ?> > img, #single #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
	}
	<?php } else { ?>
	var vpWidth = jQuery(window).width();
	if (vpWidth > 640) {
	jQuery("<?php echo $loc; ?> #jquery_jplayer_<?php echo $id ?>, #jquery_jplayer_<?php echo $id ?> > img, #jquery_jplayer_<?php echo $id ?> > video").css('height',360 + 'px');
	} else {
		var newHeight = 0.5625 * vpWidth;
	jQuery("#single #jquery_jplayer_<?php echo $id ?>, #single #jquery_jplayer_<?php echo $id ?> > img, #single #jquery_jplayer_<?php echo $id ?> > video").css('height',newHeight + 'px');
	}
	<?php } ?>

		
});
</script>

	<!--container for everything-->
<div id="jp_container_<?php echo $id ?>" class="jp-video jp-video-360p">
 
    <!--container in which our video will be played-->
    <div id="jquery_jplayer_<?php echo $id ?>" class="jp-jplayer"></div>
     
    <!--main containers for our controls-->
    <div class="jp-gui">
        <div class="jp-interface">
            <div class="jp-controls-holder">
                <!--play and pause buttons-->
                <a href="javascript:;" class="jp-play" tabindex="1">play</a>
                <a href="javascript:;" class="jp-pause" tabindex="1">pause</a>
                <span class="separator sep-1"></span>
                 
                <!--progress bar-->
                <div class="jp-progress">
                    <div class="jp-seek-bar">
                        <div class="jp-play-bar"><span></span></div>
                    </div>
                </div>
                 
                <!--time notifications-->
                <div class="jp-current-time"></div>
                <span class="time-sep">/</span>
                <div class="jp-duration"></div>
                <span class="separator sep-2"></span>
                 
                <!--mute / unmute toggle-->
                <a href="javascript:;" class="jp-mute" tabindex="2" title="mute">mute</a>
                <a href="javascript:;" class="jp-unmute" tabindex="2" title="unmute">unmute</a>
                 
                <!--volume bar-->
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"><span class="handle"></span></div>
                </div>
                <span class="separator sep-2"></span>
                 
                <!--full screen toggle-->
                <a href="javascript:;" class="jp-full-screen" tabindex="3" title="full screen">full screen</a>
                <a href="javascript:;" class="jp-restore-screen" tabindex="3" title="restore screen">restore screen</a>
            </div><!--end jp-controls-holder-->
        </div><!--end jp-interface-->
    </div><!--end jp-gui-->
     
    <!--unsupported message-->
    <div class="jp-no-solution">
        <span>Update Required</span>
        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/">Flash plugin</a>.
    </div>
     
</div><!--end jp_container_1-->
<?php } elseif ( $filetype['ext'] == 'ogg' || $filetype['ext'] == 'mp3' || $filetype['ext'] == 'oga') { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	// Set width of control bar
	var vpWidth = jQuery(window).width();
		if ( vpWidth < 590 ) {
			jQuery('article#post-<?php echo $id; ?> .jp-controls-holder').css('width',(vpWidth-20) + 'px');
			var newWidth = vpWidth - 280;
			jQuery('article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px');
		}
	jQuery('a.lightbox').click(function(){
		var vpWidth = jQuery(window).width();
		if ( vpWidth < 590 ) {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',(vpWidth-20) + 'px');
			var newWidth = vpWidth - 280;
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px'); 
		} else {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',570 + 'px');
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', 310 + 'px');
		}
	});
	jQuery(window).resize(function(){
		var vpWidth = jQuery('article#post-<?php echo $id; ?> .lb-audio').width();
		if ( vpWidth < 570 ) {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',vpWidth + 'px');
			var newWidth = vpWidth - 260;
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', newWidth + 'px'); 
		} else {
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-controls-holder').css('width',570 + 'px');
			jQuery('<?php echo $loc; ?> article#post-<?php echo $id; ?> .jp-progress').css('width', 310 + 'px');
		}
	});

	
    jQuery("#jquery_jplayer_<?php echo $id ?>").jPlayer({
        ready: function () {
            jQuery(this).jPlayer("setMedia", {
                <?php if ( $filetype['ext'] == 'ogg' ) { echo 'oga'; } else { echo $filetype['ext']; }?>: "<?php echo $src; ?>" <?php if ( $altsrc != '' ) { ?>,
                <?php if ( $filetypealt['ext'] == 'ogg' ) { echo 'oga'; } else { echo $filetypealt['ext']; }?>: "<?php echo $altsrc; ?>" <?php } ?>
            });
        },
        swfPath: "<?php echo get_template_directory_uri(); ?>/scripts",
		cssSelectorAncestor: "#jp_container_<?php echo $id ?>",
        supplied: "<?php if ( $filetype['ext'] != '' && $filetype['ext'] == 'ogg' ) { echo 'oga'; } elseif ( $filetype['ext'] != '' ) { echo $filetype['ext']; } ?>, <?php if ( $filetypealt['ext'] != '' && $filetypealt['ext'] == 'ogg' ) { echo 'oga'; } elseif ( $filetypealt['ext'] != '' ) { echo $filetypealt['ext']; } ?>",
    });
});
</script>
  
	<!--container for everything-->
<div id="jp_container_<?php echo $id ?>" class="jp-audio">
 
    <!--container in which our video will be played-->
    <div id="jquery_jplayer_<?php echo $id ?>" class="jp-jplayer"></div>
     
    <!--main containers for our controls-->
    <div class="jp-gui">
        <div class="jp-interface">
            <div class="jp-controls-holder">
                <!--play and pause buttons-->
                <a href="javascript:;" class="jp-play" tabindex="1">play</a>
                <a href="javascript:;" class="jp-pause" tabindex="1">pause</a>
                <span class="separator sep-1"></span>
                 
                <!--progress bar-->
                <div class="jp-progress">
                    <div class="jp-seek-bar">
                        <div class="jp-play-bar"><span></span></div>
                    </div>
                </div>
                 
                <!--time notifications-->
                <div class="jp-current-time"></div>
                <span class="time-sep">/</span>
                <div class="jp-duration"></div>
                <span class="separator sep-2"></span>
                 
                <!--mute / unmute toggle-->
                <a href="javascript:;" class="jp-mute" tabindex="2" title="mute">mute</a>
                <a href="javascript:;" class="jp-unmute" tabindex="2" title="unmute">unmute</a>
                 
                <!--volume bar-->
                <div class="jp-volume-bar">
                    <div class="jp-volume-bar-value"><span class="handle"></span></div>
                </div>
                <span class="separator sep-2"></span>
                 
            </div><!--end jp-controls-holder-->
        </div><!--end jp-interface-->
    </div><!--end jp-gui-->
     
    <!--unsupported message-->
    <div class="jp-no-solution">
        <span>Update Required</span>
        To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/">Flash plugin</a>.
    </div>
     
</div><!--end jp_container_1-->

<?php } else { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
	<?php if ( $width != '' && $height !='' ) { ?>
	var oriWidth = <?php echo $width; ?>;
	var oriHeight = <?php echo $height; ?>;
	var ratio = oriHeight / oriWidth * 100;
	jQuery("article#post-<?php echo $id; ?> .lb-video").css('padding-bottom',ratio + '%');
	<?php } ?>
});
</script>

	<iframe src="<?php echo $src; ?>"></iframe>
<?php }
}

// Pagination
function matrix_pagination(){
global $wp_query;

$total_pages = $wp_query->max_num_pages;

if ($total_pages > 1){

  $current_page = max(1, get_query_var('paged'));

  echo '<div class="pagination clearfix">';

  echo paginate_links(array(
      'base' => get_pagenum_link(1) . '%_%',
      'format' => 'page/%#%',
      'current' => $current_page,
      'total' => $total_pages,
      'prev_text' => '&laquo;',
      'next_text' => '&raquo;',
	  'end_size' => 4,
	  'mid_size' => 3
    ));
	
  echo '<span class="pages">Page '.$current_page.' of '.$total_pages.'</span>';

  echo '</div>';

}
}

// AJAX Pagination
function matrix_ajax_pagination(){
global $wp_query;

$total_pages = $wp_query->max_num_pages;

if ($total_pages > 1){

  $current_page = max(1, get_query_var('paged'));

  echo '<div class="clearfix ajax-pagination">';

  echo paginate_links(array(
      'base' => get_pagenum_link(1) . '%_%',
      'format' => '/page/%#%',
      'current' => $current_page,
      'total' => $total_pages,
      'prev_text' => '',
      'next_text' => '',
    ));

  echo '</div>';

}
}

/*
 * Comments Panel
 */
 
//Comments style
function matrix_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-author vcard">
		<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
        <a href="<?php comment_author_url(); ?>">
		<?php 
		$commenter_id = $comment->user_id;
		$commenter_fname =  get_user_meta($commenter_id, 'first_name', true);
		$commenter_lname =  get_user_meta($commenter_id, 'last_name', true);
		$commenter_name = ucwords(strtolower($commenter_fname . " " . $commenter_lname));
		$commenter = get_comment_author();
	
		if ($commenter_name != ' ') {
		printf(__('<cite class="fn commenter">%s</cite>'), $commenter_name);
		} else {
		printf(__('<cite class="fn commenter">%s</cite>'), $commenter);
		}
		?>
        </a>
		</div>
<?php if ($comment->comment_approved == '0') : ?>
		<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'matrix') ?></em>
		<br />
<?php endif; ?>

		<div class="comment-meta commentmetadata comment-date">
			<?php
				/* translators: 1: date, 2: time */
				printf( __('%1$s at %2$s', 'matrix'), get_comment_date(),  get_comment_time()) ?><?php edit_comment_link(__('(Edit)', 'matrix'),'  ','' );
			?>
		</div>

		<?php comment_text() ?>

		<div class="comment-reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
<?php
}

//Add a default gravatar
    if ( !function_exists('matrix_add_gravatar') ) {
    function matrix_add_gravatar( $avatar_defaults ) {
    $avatar = get_template_directory_uri() . '/images/avatar.png';
    $avatar_defaults[$avatar] = 'Matrix Default';
    return $avatar_defaults;
    }
    add_filter( 'avatar_defaults', 'matrix_add_gravatar' );
    }

// Matrix Search Form
function matrix_search_form( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
	<input id="search-field" type="search" name="s" class="placeholder" placeholder="'. esc_attr__('Search') .'" value="' . get_search_query() . '"/>
	<input type="submit" id="searchsubmit" value=" " />
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'matrix_search_form' );

// Change Password Required Style
function matrix_password_form() {
    global $post;
    $label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
    $o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-pass.php" method="post">
    <p>' . __( "This post is password protected. To view it please enter your password below:", 'matrix' ) . '</p>
    <label class="pass-label" for="' . $label . '">' . __( "PASSWORD:", 'matrix' ) . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" class="input-field"/><input type="submit" name="Submit" class="submit-button" value="' . esc_attr__( "Submit" ) . '" />
    </form>
    ';
    return $o;
}
add_filter( 'the_password_form', 'matrix_password_form' );

?>