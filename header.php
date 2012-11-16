<!DOCTYPE html>
<html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'matrix' ), max( $paged, $page ) );
	
	// Get theme options	
	global $data; //fetch options stored in $data

?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="shortcut icon" href="<?php echo $data['matrix_favicon']; ?>" />
<link href="<?php bloginfo( 'stylesheet_url' ); ?>" title="style" rel="stylesheet" type="text/css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( $data['matrix_theme_color_pick_check'] == 0 ) { ?>
<link href="<?php echo get_template_directory_uri(); ?>/css/style-<?php echo $data['matrix_theme_color_select']; ?>.css" title="style" rel="stylesheet" type="text/css" media="screen"/>
<?php } else { ?>
<?php
$theme_colour = $data['matrix_theme_color_pick'];
?>
<style>
a,
.toggle-button:hover,
.ac-tab:hover,
ul#port-filter li,
ul#port-filter li:hover,
.about-person:hover,
.price-pre,
.price-post,
.hl-txt1,
.hl-txt2,
.widget #tweeter li a,
#wp-calendar td#today:hover a{color:<?php echo $theme_colour; ?>;
}
.themecolortxt,
span.dark-themecolor:hover,
#pg-content .about-person:hover h3{
	color:<?php echo $theme_colour; ?> !important;
}
ul#nav li.back,
ul#nav > li li,
.hl1,
.hl3,
#content-title,
.title-highlight,
#content .toggle-content,
.flex-control-paging li a:hover,
.flex-control-paging li a.flex-active,
.page,
.home-pagination,
.nextpagelink,
.table-info ul li{
	border-color:<?php echo $theme_colour; ?>;
}
.break,
ul#nav li.current-menu-item > a,
ul#nav li.current-post-ancestor > a,
ul#nav li.current-matrix_portfolio-ancestor > a,
ul#nav li li a,
ul#nav:hover > li:hover > a,
.hl2,
.themecolor,
.quote-bg1,
.testimonial-1,
.toggle-button,
.ac-tab,
.flexslider,
.flex-title,
.flex-control-paging li a,
.jp-play-bar,
.jp-volume-bar-value,
.pagination .current,
.post-pagination > .page-numbers,
ul#port-filter li.filter-current,
.table-title,
.widget h5,
#search-field:focus,
#search-submit:hover,
#post-meta .tile-sidebar,
.sidebreak,
span.dark-themecolor,
#wp-calendar td#today{
	background-color:<?php echo $theme_colour; ?>;
}
</style>
<?php } ?>
<!--[if lt IE 9]>
  <style type="text/css">
  @import url("<?php echo get_template_directory_uri(); ?>/style-ie8.css");
  </style>
  <script>
    document.createElement('header');
    document.createElement('nav');
    document.createElement('section');
    document.createElement('article');
    document.createElement('aside');
    document.createElement('footer');
    document.createElement('hgroup');
    </script>
<![endif]-->
<!--[if IE 9]>
  <style type="text/css">
  @import url("<?php echo get_template_directory_uri(); ?>/style-ie9.css");
  </style>
<![endif]-->
<script type="text/javascript">
var templateURL = '<?php echo get_template_directory_uri(); ?>';
</script>
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
	matrix_load_scripts();

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<style type="text/css">
body{
	<?php if ( $data['matrix_background_image'] != '' ) { ?>
	background:url(<?php echo $data['matrix_background_image']; ?>) center top no-repeat;
	background-attachment:fixed;
	background-size:cover;
	-moz-background-size:cover;
	-webkit-background-size:cover;
	-o-background-size:cover;
	<?php } else { ?>
	background-color:<?php echo $data['matrix_background_color']; ?>;
	<?php } ?>
}
<?php echo $data['matrix_custom_css']; ?>
</style>
</head>
<body <?php body_class(); ?>>
<?php if ( $data['matrix_background_pattern'] == 1 ){ ?>
<div id="bodypat">
<?php } ?>
<section id="container">
<!-- BEGIN HEADER -->
<header class="clearfix">
<!-- BEGIN LOGO -->
<a id="headerlink" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img id="logo" src="<?php echo $data['matrix_logo_image']; ?>" alt="logo"/><span id="sitename"><?php bloginfo( 'name' ); ?></span></a>
<!-- END LOGO -->

<!-- BEGIN NAVIGATION -->
<?php wp_nav_menu( array( 
'container'       => 'nav', 
'menu_id'         => 'nav',
'theme_location'  => 'header-menu',
 ) ); ?>
<!-- END NAVIGATION -->
</header>
<!-- END HEADER -->
