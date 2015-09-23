<?php
/**
 * Plugin Name:       Merge and Share
 * Plugin URI:        http://vitctord.it
 * Description:       Merge two images and share on Facebook
 * Version:           1.0.0
 * Author:            Victor Cepeda
 * Author URI:        http://vitctord.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       merge-and-share
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-merge-and-share-activator.php
 */
function activate_merge_and_share() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-merge-and-share-activator.php';
	Merge_And_Share_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-merge-and-share-deactivator.php
 */
function deactivate_merge_and_share() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-merge-and-share-deactivator.php';
	Merge_And_Share_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_merge_and_share' );
register_deactivation_hook( __FILE__, 'deactivate_merge_and_share');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-merge-and-share.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_merge_and_share() {

	$plugin = new Merge_And_Share();
	$plugin->run();

}
run_merge_and_share();

function merge_share($fb_app_id){
	$fb_app_id = implode($fb_app_id,',');
	$href= plugin_dir_url( __FILE__ )."includes/imagick.php";
	$share_button = '<div class="merge_share_button"><a href="'.$href.'" data-fancybox-type="iframe" data-fb-app="'.$fb_app_id.'" title="merge and share">Share</a></div>';
	echo $share_button;
}
add_shortcode('merge_share', 'merge_share');

//add pages
add_action('admin_menu', 'mas_add_pages');


//ad options
$mas_watermark_option_value= plugin_dir_path( __FILE__ ).'public/images/watermarker.png';
add_option('mas_watermark',$mas_watermark_option_value);


// action function for add pages
function mas_add_pages() {
 
    add_options_page(__('Merge and Share Settings','menu-mas'), __('Merge and Share Settings','menu-mas'), 'manage_options', 'massettings', 'mas_settings_page');

    // mas_settings_page() 
    // displays the page content
	function mas_settings_page() {
	    if (!current_user_can('manage_options'))wp_die( __('You do not have sufficient permissions to access this page.'));
	    $opt_name = 'mas_watermark';
	    $mas_wp_home_path = get_home_path();

	    $opt_val = str_replace($mas_wp_home_path,'',get_option($opt_name));

	    if(isset($_POST[$opt_name]) && !empty($_POST[$opt_name])) {
	    	$opt_val_for_db= $mas_wp_home_path.$_POST[$opt_name ];
	        update_option($opt_name,$opt_val_for_db);
		?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
		<?php
		 }

		    echo '<div class="wrap">';
		    echo "<h2>" . __( 'Merge and Share Settings', 'menu-mas' ) . "</h2>";
		?>
		<h3>Upload new watermak image </h3>
		<form name="form1" method="post" action="">
			<p><?php _e("Image for watermark:", 'menu-test' ); ?> 
				<label>Watermark</label>
				<span>Put the path of image in your site</span>
				<span>(ex:wp-content/wp-upload/watermak/watermark.jpg)</span><br>
				<input type="text" name="<?php echo $opt_name; ?>" value="<?php echo $opt_val; ?>" size="60">
			</p>
			<hr />
			<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
			</p>
		</form>
		</div>
		<?php
		}
}

?>