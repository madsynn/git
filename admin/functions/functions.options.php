<?php

add_action('init','of_options');

if (!function_exists('of_options'))
{
	function of_options()
	{
		//Access the WordPress Categories via an Array
		$of_categories = array();  
		$of_categories_obj = get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
		    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp = array_unshift($of_categories, "Select a category:");    
	       
		//Access the WordPress Pages via an Array
		$of_pages = array();
		$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
		foreach ($of_pages_obj as $of_page) {
		    $of_pages[$of_page->ID] = $of_page->post_name; }
		$of_pages_tmp = array_unshift($of_pages, "Select a page:");       
	
		//Testing 
		$of_options_select = array("one","two","three","four","five"); 
		$of_options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five");
		
		//Sample Homepage blocks for the layout manager (sorter)
		$of_options_homepage_blocks = array
		( 
			"disabled" => array (
				"placebo" 		=> "placebo", //REQUIRED!
				"block_one"		=> "Block One",
				"block_two"		=> "Block Two",
				"block_three"	=> "Block Three",
			), 
			"enabled" => array (
				"placebo" => "placebo", //REQUIRED!
				"block_four"	=> "Block Four",
			),
		);


		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) 
		{
		    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) 
		    { 
		        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) 
		        {
		            if(stristr($alt_stylesheet_file, ".css") !== false)
		            {
		                $alt_stylesheets[] = $alt_stylesheet_file;
		            }
		        }    
		    }
		}


		//Background Images Reader
		$bg_images_path = get_stylesheet_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
		    if ($bg_images_dir = opendir($bg_images_path) ) { 
		        while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
		            if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
		                $bg_images[] = $bg_images_url . $bg_images_file;
		            }
		        }    
		    }
		}
		

		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option('of_uploads');
		$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
		$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
		$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");
		
		// Image Alignment radio box
		$of_options_thumb_align = array("alignleft" => "Left","alignright" => "Right","aligncenter" => "Center"); 
		
		// Image Links to Options
		$of_options_image_link_to = array("image" => "The Image","post" => "The Post"); 
		
		// Matrix Archive Tiles
		$matrix_archive_tiles = array(
		"list" => __("List", 'matrix'),
		"small" => __("Small", 'matrix'),
		"medium" => __("Medium",'matrix'),
		"large" => __("Large",'matrix')
		);
		
		// Matrix Theme Colours
		$matrix_theme_colour = array(
		"blue","brown","green","lime","magenta","mango","pink","purple","red","teal");


/*-----------------------------------------------------------------------------------*/
/* The Options Array */
/*-----------------------------------------------------------------------------------*/

// Set the Options Array
global $of_options;
$of_options = array();

$of_options[] = array( "name" => __("General Settings", "matrix"),
					"type" => "heading");
					
$of_options[] = array( "name" => __("Hello there!", "matrix"),
					"desc" => "",
					"id" => "matrix_introduction",
					"std" => __("<h3 style=\"margin: 0 0 10px;\">Thank you for purchasing Matrix WordPress Theme!</h3>
					Each setting is accompanied by a clear description of what it does. If there is something you are not clear, or you have any questions which has not been covered in the documentation, please contact me at <a href='http://themeforest.net/user/billyf'>ThemeForest</a> or send an email to me using the address given in the documentation file.", "matrix"),
					"icon" => true,
					"type" => "info");
					
$of_options[] = array( "name" => __("Logo", "matrix"),
					"desc" => __("Upload your website's logo here to be displayed at the header.", "matrix"),
					"id" => "matrix_logo_image",
					"std" => "",
					"mod" => "min",
					"type" => "media");
					
$of_options[] = array( "name" => __("Custom Favicon", "matrix"),
					"desc" => __("Upload a 16px x 16px .png or .gif image which will be used as your website's favicon.", "matrix"),
					"id" => "matrix_favicon",
					"std" => "",
					"type" => "upload");

$of_options[] = array( "name" => __("Theme Colour", "matrix"),
					"desc" =>  __("Choose the main colour for the theme.", "matrix"),
					"id" => "matrix_theme_color_select",
					"std" => "Blue",
					"type" => "select",
					"options" => $matrix_theme_colour);

$of_options[] = array( "name" => '',
					"desc" => __("Or pick the main colour for the theme.", "matrix"),
					"id" => "matrix_theme_color_pick",
					"std" => "#19a2de",
					"type" => "color");
					
$of_options[] = array( "name" => '',
					"desc" => __("Check this to use the picked colour. If unchecked, the selected colour in the drop-down list will be used.", "matrix"),
					"id" => "matrix_theme_color_pick_check",
					"std" => 0,
					"type" => "checkbox");


$of_options[] = array( "name" => __("Background Image", "matrix"),
					"desc" => __("Upload a background image", "matrix"),
					"id" => "matrix_background_image",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __("Background Pattern", "matrix"),
					"desc" => __("Used to dim the background. Particularly useful when you are using a bright background.", "matrix"),
					"id" => "matrix_background_pattern",
					"std" => 1,
					"type" => "checkbox");
					
$of_options[] = array( "name" =>  __("Background Colour", "matrix"),
					"desc" => __("Pick a background colour for the theme. This colour will be shown when there is no background image.", "matrix"),
					"id" => "matrix_background_color",
					"std" => "#ffffff",
					"type" => "color");
					
$of_options[] = array( "name" => "Archive Tile Type",
					"desc" => "Choose the type of tile to be used to display posts in archives.",
					"id" => "matrix_archive_tile_type",
					"std" => "list",
					"type" => "radio",
					"options" => $matrix_archive_tiles);
					
$of_options[] = array( "name" => __("Footer Text", "matrix"),
                    "desc" => __("Text to be displayed at the website footer.", "matrix"),
                    "id" => "matrix_footer_text",
                    "std" => __("Copyright &#169; 2012 Matrix", "matrix"),
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("Tracking Code", "matrix"),
					"desc" => __("Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.", "matrix"),
					"id" => "matrix_tracking_code",
					"std" => "",
					"type" => "textarea");
					
$of_options[] = array( "name" => __("Home Settings", "matrix"),
					"type" => "heading");

$of_options[] = array( "name" => __("Slider Options", "matrix"),
					"desc" => __("Unlimited slider with drag and drop sortings. This will be shown at the homepage", "matrix"),
					"id" => "matrix_home_slider",
					"std" => "",
					"type" => "slider");
					
$of_options[] = array( "name" => '',
					"desc" => __("Check this to use a toggle for slider. If it is unchecked, the slider will be displayed all the time.", "matrix"),
					"id" => "matrix_home_slider_toggle",
					"std" => 1,
					"type" => "checkbox");
					
$of_options[] = array( "name" => __("Highlighted Text", "matrix"),
                    "desc" => __("Text to be displayed below the slider.", "matrix"),
                    "id" => "matrix_home_highlighted_text",
                    "std" => __("Hello! This is a Metro UI-inspired template which brings a new web-browsing experience to the users. To further improve it, feedbacks are greatly welcomed!", "matrix"),
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("Featured Content", "matrix"),
					"desc" => __("This will be shown below the tiles at the main page. Similarly, it has drag and drop sortings.", "matrix"),
					"id" => "matrix_home_featured_content",
					"std" => "",
					"type" => "slider");
					
$of_options[] = array( "name" => '',
					"desc" => __("Check this to use a toggle for featured content. If it is unchecked, the featured content will be shown all the time.", "matrix"),
					"id" => "matrix_home_featured_content_toggle",
					"std" => 1,
					"type" => "checkbox");
					
$of_options[] = array( "name" => __("AJAX Pagination", "matrix"),
					"desc" => __("Enables AJAX pagination at front page. Note that this feature has been disabled in IE8 due to incompatibility. Besides, all audio / video uploaded by yourself will not work using AJAX as Javascript will not run in newly acquired content via AJAX. So, you should use iframe content from services such as YouTube or Vimeo if you want to use this feature.", "matrix"),
					"id" => "matrix_ajax_pagination_front",
					"std" => 0,
					"type" => "checkbox");

					
$of_options[] = array( "name" => __("Contact Page", "matrix"),
					"type" => "heading");
					
$of_options[] = array( "name" => __("Page Image", "matrix"),
					"desc" => __("Upload a page image to be displayed at the top.", "matrix"),
					"id" => "matrix_contact_page_image",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __("Contact 1 City", "matrix"),
					"desc" => __("Enter the city for contact 1.", "matrix"),
					"id" => "matrix_contact_1_city",
					"std" => __("City", "matrix"),
					"type" => "text");
					
$of_options[] = array( "name" => __("Contact 1 Address", "matrix"),
                    "desc" => __("Enter the address for contact 1. Insert &#60;br /&#62; to start trailing texts on a new line.", "matrix"),
                    "id" => "matrix_contact_1_address",
                    "std" => __("Lot<br />Street<br />Postcode<br />Country<br />", "matrix"),
                    "type" => "textarea");

$of_options[] = array( "name" => __("Contact 1 Location (optional)", "matrix"),
                    "desc" => __("Enter the link to the location map, such as Google Maps or Bing Maps.", "matrix"),
                    "id" => "matrix_contact_1_location",
                    "std" => "",
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("Contact 2 City", "matrix"),
					"desc" => __("Enter the city for contact 2.", "matrix"),
					"id" => "matrix_contact_2_city",
					"std" => __("City", "matrix"),
					"type" => "text");
					
$of_options[] = array( "name" => __("Contact 2 Address", "matrix"),
                    "desc" => __("Enter the address for contact 2. Insert &#60;br /&#62; to start trailing texts on a new line.", "matrix"),
                    "id" => "matrix_contact_2_address",
                    "std" => __("Lot<br />Street<br />Postcode<br />Country<br />", "matrix"),
                    "type" => "textarea");

$of_options[] = array( "name" => __("Contact 2 Location (optional)", "matrix"),
                    "desc" => __("Enter the link to the location map, such as Google Maps or Bing Maps.", "matrix"),
                    "id" => "matrix_contact_2_location",
                    "std" => "",
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("Contact Number", "matrix"),
                    "desc" => __("Insert your contact numbers here. Use &#60;br /&#62; to start trailing texts on a new line.", "matrix"),
                    "id" => "matrix_contact_number",
                    "std" => __("Headquarters : +123-456-7890<br/>London : +123-456-7890<br/>New York : +123-456-7890", "matrix"),
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("About Page", "matrix"),
					"type" => "heading");
					
$of_options[] = array( "name" => __("Page Image", "matrix"),
					"desc" => __("Upload a page image to be displayed at the top.", "matrix"),
					"id" => "matrix_about_page_image",
					"std" => "",
					"type" => "media");
					
$of_options[] = array( "name" => __("Highlighted Text", "matrix"),
					"desc" => __("The content inserted here will be displayed on the page image at the top. You may use HTML tags here.", "matrix"),
					"id" => "matrix_about_page_excerpt",
					"std" => __("We are dedicated to provide the <span class='themecolortxt'>best</span> service in the industry to our clients. With experience of more than a decade in this industry, we are your most reliable partner for your upcoming project.", "matrix"),
					"type" => "text");
					
$of_options[] = array( "name" => __("Title for Key Personnel Section", "matrix"),
                    "desc" => __("Enter the title here.", "matrix"),
                    "id" => "matrix_about_page_team_title",
                    "std" => __("The Team", "matrix"),
                    "type" => "text");
					
$of_options[] = array( "name" => __("Tagline for Key Personnel Section", "matrix"),
                    "desc" => __("Enter the tagline here. This area supports HTML tags.", "matrix"),
                    "id" => "matrix_about_page_team_tagline",
                    "std" => __("Some text here.", "matrix"),
                    "type" => "textarea");

$of_options[] = array( "name" => __("Company Workers", "matrix"),
					"desc" => __("You can show some company workers at the About page, with their respective photographs and job title", "matrix"),
					"id" => "matrix_about_page_the_team",
					"std" => "",
					"type" => "matrix_the_team");
					
$of_options[] = array( "name" => __("Title for Testimonial Section", "matrix"),
                    "desc" => __("Enter the title here.", "matrix"),
                    "id" => "matrix_about_page_testimonial_title",
                    "std" => __("Testimonials", "matrix"),
                    "type" => "text");
					
$of_options[] = array( "name" => __("Tagline for Testimonial Section", "matrix"),
                    "desc" => __("Enter the tagline here. This area supports HTML tags.", "matrix"),
                    "id" => "matrix_about_page_testimonial_tagline",
                    "std" => __("Our awesome clients <span class='whitetxt'>love us</span>", "matrix"),
                    "type" => "textarea");
					
$of_options[] = array( "name" => __("Testimonials", "matrix"),
					"desc" => __("You can show some testimonials at the About page.", "matrix"),
					"id" => "matrix_about_page_testimonials",
					"std" => "",
					"type" => "matrix_testimonials");
					
$of_options[] = array( "name" => __("Social Network", "matrix"),
					"type" => "heading");

$of_options[] = array( "name" => __("Footer", "matrix"),
					"desc" => "",
					"id" => "matrix_social_footer",
					"std" => __("To choose the social icons to be displayed at the site's footer, please go to <strong>Settings > Social Bartender</strong>.", "matrix"),
					"type" => "info");
					
$of_options[] = array( "name" => __("Custom Style", "matrix"),
					"type" => "heading");
					
$of_options[] = array( "name" => __("Custom CSS", "matrix"),
                    "desc" => __("Enter some custom CSS here to override the default style.", "matrix"),
                    "id" => "matrix_custom_css",
                    "std" => "",
                    "type" => "textarea");
										
// Backup Options
$of_options[] = array( "name" => "Backup Options",
					"type" => "heading");
					
$of_options[] = array( "name" => "Backup and Restore Options",
                    "id" => "of_backup",
                    "std" => "",
                    "type" => "backup",
					"desc" => 'You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.',
					);
					
$of_options[] = array( "name" => "Transfer Theme Options Data",
                    "id" => "of_transfer",
                    "std" => "",
                    "type" => "transfer",
					"desc" => 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options".
						',
					);
					
	}
}
?>
