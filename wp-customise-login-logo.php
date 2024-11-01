<?php
/**
 * Plugin Name: WP Customise Login Logo
 * Description: Update the wordpress login logo
 * Version: 1.0
 * Author: Surbhit Dubey
 * Author URI: http://techfreq.com
 * WP CLL License: GPL2
 */

  /**
 * Including plugin  styles
 */ 
  add_action( 'admin_init', 'wp_cll_url_title_styles' );
	function wp_cll_url_title_styles() {
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)).'/css';
	wp_enqueue_style( 'wp_cll_url_title', $pluginfolder.'/wp_cll_url_title.css' );
	
}
// UPLOAD ENGINE
function wp_cll_load_wp_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'wp_cll_load_wp_media_files' );
/* Initializing  plugin function */
  function wp_cll_initlize_url_title()
 {
	 add_options_page("Update WP login logo","Update WP login logo","manage_options","wp_cll-logo-title","wp_cll_url_title_load");
 }
 
 add_action("admin_menu","wp_cll_initlize_url_title");
 
  /* Calling plugin function */
 function wp_cll_url_title_load()
 {
 /* Plugin heading */
 
	echo "<h3> Update WP login logo, logo link and logo title </h3>";
	
/* Checking submit button clicked or not	 */

	if(isset($_REQUEST['wp_cll_url_title_submit'])){
		
		$wp_cll_logo_url = $_REQUEST['wp_cll_logo_url'];
		$wp_cll_logo_title = $_REQUEST['wp_cll_logo_title'];
		$wp_cll_logo_ad_image_path = $_REQUEST['ad_image_path'];
		
		/*  Stores data in options table in database */
		update_option('wp_cll_logo_url', $wp_cll_logo_url);	
		update_option('wp_cll_logo_title', $wp_cll_logo_title);	
		if(isset($wp_cll_logo_ad_image_path)){
		update_option('wp_cll_logo_ad_image_path', $wp_cll_logo_ad_image_path);
		}
		
	}
	
 /* Url and title form */
?>	
<div class="wp_cll_url_titles">
	<form name="wp_cll_url_title" method="post">
	<table align="left">
		<tr>
			<td><label for="image_url">Upload Image :</label></td>
			<td>
				<?php $check_im_path = get_option('wp_cll_logo_ad_image_path');?>
				<?php if($check_im_path){ ?>
					<span style="float:right;"><a href="javascriot:void(0);" class="remove" >Remove</a></span>
					<img src="<?php echo get_option('wp_cll_logo_ad_image_path');?>" id="imgsrc" class="wp_cll_image_show logoimage">
				<?php } else {?>
					<img src="<?php echo plugins_url( 'css/default.jpg', __FILE__ );?>" id="imgsrc" class="wp_cll_image_show imgdefualt">
				<?php }?>
				<input type="hidden" name="hid_up_image" id="hid_up_image" value="<?php echo get_option('wp_cll_logo_ad_image_path');?>">
				<input id="upload_image" type="hidden" size="36" name="ad_image_path"  value="<?php echo get_option('wp_cll_logo_ad_image_path');?>" /> 
				<input id="upload_image_button" class="button" type="button" value="Update Image" />
			</td>
		</tr>
		<tr>
			<td><label>Logo Link (Link on logo ) :</label></td>
			<td><input type="text" name="wp_cll_logo_url" value="<?php echo get_option('wp_cll_logo_url');?>" required></td>
		</tr>
		<tr>
			<td><label>Logo title on Hover :</label></td>
			<td><input type="text" name="wp_cll_logo_title" value="<?php echo get_option('wp_cll_logo_title');?>" required></td>
		</tr>
		<tr>
			<td colspan=2><input type="submit" name="wp_cll_url_title_submit" class="wp_cll_url_title_submit_cls" value="Submit"></td>
			
		</tr>
	</table>
	</form>
</div>
<script>
// Raising Media upload form
    jQuery(document).ready(function($){
    var custom_uploader;
	$('.remove').click(function(){ 
		$('.logoimage').hide();
		$('#hid_up_image').val('');
		$('#upload_image').val('');
	})
    $('#upload_image_button').click(function(e) {
        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: true
        });
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            console.log(custom_uploader.state().get('selection').toJSON());
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
             $('#imgsrc').attr('src', attachment.url);
            
        });
        //Open the uploader dialog
        custom_uploader.open();

    });
});
    </script>
<?php } // Plugin function end here
 
/*  Calling default  head url and head title filter */
 add_filter( 'login_headerurl', 'wp_cll_wp_logo_url' );
 add_filter('login_headertitle', 'wp_cll_wp_login_title');
 add_action('login_head', 'wp_cll_wp_logo_custom_login');
 
 /*  Update the logo    */
 function wp_cll_wp_logo_custom_login() {
	 	$wp_cll_image_path =  get_option('wp_cll_logo_ad_image_path');
	 	if ($wp_cll_image_path) {
	 	list($width, $height, $type, $attr) = getimagesize($wp_cll_image_path);
		
		echo '<style type="text/css">
       .login h1 a { background-image:url('.$wp_cll_image_path.') !important;  height:'.$height.'px; width:auto; background-size: '.$width.'px '.$height.'px; 
		     }
    </style>';
		}
}

/*  Update the logo link   */
function wp_cll_wp_logo_url($url)
{
return get_option('wp_cll_logo_url');
}

/*  Update the logo hover title   */
function wp_cll_wp_login_title() {
return get_option('wp_cll_logo_title');
}
?>