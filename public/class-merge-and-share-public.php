<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://vitctord.it
 * @since      1.0.0
 *
 * @package    Merge_And_Share
 * @subpackage Merge_And_Share/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Merge_And_Share
 * @subpackage Merge_And_Share/public
 * @author     Victor <devprojects@victord.it>
 */
class Merge_And_Share_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Merge_And_Share_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Merge_And_Share_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//add fancybox
		wp_enqueue_style( $this->plugin_name.'-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/jquery.fancybox.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-buttons-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/helpers/jquery.fancybox-buttons.css', array($this->plugin_name.'-fancybox'), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'-thumbs-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/helpers/jquery.fancybox-thumbs.css', array($this->plugin_name.'-fancybox'), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/merge-and-share-public.css', array(
			$this->plugin_name.'-fancybox',
			$this->plugin_name.'-buttons-fancybox',
			$this->plugin_name.'-thumbs-fancybox'
		), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Merge_And_Share_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Merge_And_Share_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//add fancybox
		//wp_enqueue_script( $this->plugin_name.'-jquery-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/jquery-1.10.2.min.js', array(), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name.'-mousewheel-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/jquery.mousewheel.pack.js', array('jquery'), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name.'-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/jquery.fancybox.pack.js', array(
				'jquery',
				$this->plugin_name.'-mousewheel-fancybox'
			), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name.'-buttons-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/helpers/jquery.fancybox-buttons.js', array($this->plugin_name.'-fancybox'), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name.'-thumbs-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/helpers/jquery.fancybox-thumbs.js', array($this->plugin_name.'-fancybox'), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name.'-media-fancybox', plugin_dir_url( __FILE__ ) . 'js/fancybox/helpers/jquery.fancybox-media.js', array($this->plugin_name.'-fancybox'), $this->version, 'all' );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/merge-and-share-public.js', array(
			'jquery',
			$this->plugin_name.'-fancybox',
			$this->plugin_name.'-buttons-fancybox',
			$this->plugin_name.'-thumbs-fancybox',
			$this->plugin_name.'-media-fancybox'
		), $this->version, false );

	}

}
