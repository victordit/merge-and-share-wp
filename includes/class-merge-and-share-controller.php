<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://vitctord.it
 * @since      1.0.0
 *
 * @package    Merge_And_Share
 * @subpackage Merge_And_Share/includes
 */

/**
 *
 * @since      1.0.0
 * @package    Merge_And_Share
 * @subpackage Merge_And_Share/includes
 * @author     Victor <devprojects@victord.it>
 */
class Merge_And_Share_Controller {


	/**
	* Base path of WordPress
	**/
	protected $base_path;

	/**
	* Base url of WordPress
	**/
	protected $base_url;

	/**
	* Array of dirs and url
	* @see https://codex.wordpress.org/Function_Reference/wp_upload_dir
	**/
	protected $wp_upload_dir;

	/**
	* Base path upload
	**/
	protected $base_path_upload;

	/**
	* Base URL upload
	**/
	protected $base_url_upload;

	/**
	* Base path for images merged
	**/
	protected $base_path_merged;

	/**
	* Base URL for images merged
	**/
	protected $base_url_merged;

	/**
	* Path of file log
	**/
	protected $log_file;

	/**
	* File for Watermark
	**/
	protected $watermak;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		
		//settings
		require_once dirname( __FILE__) . '../../../../../wp-load.php';
		require_once dirname( __FILE__) . '../../../../../wp-admin/includes/file.php';
	

		$this->base_path = get_home_path().'/';
		$this->base_url= get_site_url().'/';
		$this->wp_upload_dir = wp_upload_dir();
		$this->base_path_upload = $this->wp_upload_dir['basedir'];
		$this->base_url_upload = $this->wp_upload_dir['baseurl'];
		$this->base_path_merged = $this->base_path.'shared_creativities/';
		$this->base_url_merged = $this->base_url.'shared_creativities/';
		$this->log_file = $this->base_url.'logs/mas.log';
		//$this->watermak =get_option('mas_watermark');

	}


	public function getBasePath(){
		return $this->base_path;
	}
	public function getBaseUrl(){
		return $this->base_url;
	}
	public function getBasePathUpload(){
		return $this->base_path_upload;
	}
	public function getBaseUrlUpload(){
		return $this->base_path_upload;
	}
	public function getBasePathMerged(){
		return $this->base_path_merged;
	}
	public function getBaseUrlMerged(){
		return $this->base_url_merged;
	}
	public function getFileLog(){
		return $this->log_file;
	}

	/**
	 * Retrieve head of WordPress Site
	 **/
	public function getHead(){
		ob_start(); 
		get_template_part('templates/head');
		$content = ob_get_clean();
		return $content;
	}
	
	/**
	 * Retrieve header of WordPress Site
	 **/
	public function getHeader(){
		ob_start(); 
		get_template_part('templates/header');
		$content = ob_get_clean();
		return $content;
	}

	/**
	 * Retrieve footer of WordPress Site
	 **/
	public function getFooter(){
		ob_start(); 
		get_template_part('templates/footer');
		$content = ob_get_clean();
		return $content;
	}

	public function notFoundMsg($image){
		$html = '<div class="merge-and-share-msg not-found-image">';
		$html.= "<p>Image $image not exits or not is writable</p>";
		$html.='</div>';
		return $html;
	}

	public function errorMsg($error){
		$html = '<div class=" merge-and-share-msg error-imagick">';
		$html.= "<p>Sorry,there's a error with the Server, try later.</p>";
		$html.='</div>';

		return $html;
	}

	public function contentMsg($src){
		$html = '<div class=" merge-and-share-msg content-merged">';
		$html.= '<img src="'.$src.'" title="merged-image" alt="merged-image" />';
		$html.='</div>';

		return $html;
	}
	
	/**
	* @return boolean If the OS is Windows return true
	*
	**/
	public function isWindows(){
		$isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
		if ($isWin) {
			return true;
		}
		return false;
	}

	/**
	* @return string
	* @param striing The Url Of File
	* Retrive path from url 
	**/
	public function getRealPath($url){
		$path_upload = $this->base_path;
		$url_upload = $this->base_url;
		$path =str_replace($url_upload,$path_upload,$url);
		if($this->isWindows()){
			$path = str_replace('/','\\',$path);
		}
		return $path;
	}

	public function exists($file){
		if(file_exists($file) && is_readable($file) && is_writable($file)){
			return true;
		}else{
			if(!file_exists($file)){
				return false;
			}else if(!is_readable($file) || !is_writable($file)){
					//chmod($file, 0644);
					chmod($file, 777);
				if(!is_readable($file) || !is_writable($file))
					return false;
				return true;
			}
		}
		return false;
	}

	/**
	* @return string The name of Blog
	**/
	public function getSiteTitle(){
		return get_bloginfo('name');
	}
	
	/**
	* @return string The description of Blog
	**/
	public function getSiteSlogan(){
		return get_bloginfo('description');
	}

	/**
	*  Write logs for debug Request
    * @param string $mesage The textual message
    * @param mixed array | string $value
    * @param boolean $force_array 
    *
    **/
    public function log($message,$value,$force_array = false){
     	
     $base_path_log = $this->getFileLog(); 
     
      if(is_array($value) || $force_array === true){
        $value  = var_export($value,true);
      }
      $d= debug_backtrace();
      $infoline = 'FILE: '.$d[0]['file'].' LINE: '.$d[0]['line'].' FUNCTION '.$d[0]['function']."\n";
      $message = $infoline.$message.' : '.$value;
      error_log($message,3,$base_path_log);
   }

   /**
   * Set Permisions in path and create it if not exits
   **/
   private function _checkPath($path){
   		if(!file_exists($path)){
			mkdir($path);
			if(!is_readable($path) || !is_writable($path)){
				//chmod($this->base_path_merged, 0644);
				chmod($path, 777);
			}
		}
   }
   /**
   * Set Permisions in file
   **/
   private function _checkImage($image){
   		if(!is_readable($image) || !is_writable($image)){
				//chmod($this->base_path_merged, 0644);
				chmod($image, 777);
		}
   }

	/**
	* @return mixed string | boolean (false)
	* @param integer $w The Width of new created image
	* @param integer $h The Height of new created image
	* @param string $left The url of image on the left
	* @param string $right The url of image on the right
	* @param string $right The url of image on the right
	**/
	public function createImageShared($w,$h,$left,$right,$wm = null){

		$file_name_left = basename($left,'.jpg');
		$file_name_right = basename($right,'.jpg');
		$wm = !$w?'':$wm;
		$new_name_compiled = $file_name_left.'_'.$file_name_right.'.jpg';
		$new_image_path = $this->base_path_merged.$new_name_compiled;
		$url_image = $this->base_url_merged.$new_name_compiled;
		
		if(file_exists($new_image_path)){
			return $url_image;
		}

		$fullimage =new Imagick();
		$fullimage->readImage($left);
		$fullimage->readImage($right);
		$fullimage->resetIterator();
		$new_image = $fullimage->appendImages(false);
		$new_image->cropThumbnailImage($w,$h);  
		
		try {
			//if directory not exits create it
			$this->_checkPath($this->base_path_merged);
			
			//if has watermark
			if(!empty($wm)){	
				// Open the watermark
				$watermark = new Imagick();
				$watermark->readImage($wm);
				$wi = $watermark->getImageWidth();
				$hi = $watermark->getImageHeight();
				$wi = ($w-$wi);
				$hi = ($h-$hi);
				$new_image->compositeImage($watermark, imagick::COMPOSITE_OVER, $wi,$hi);
			}

			//create new image merged
			$new_image->writeImage($new_image_path);
			chmod($new_image_path, 777);

			return $url_image;
		} catch (Exception $e) {
			$this->log('Exception',$e);
			return  false;
		}
		
	}
	/**
	* @return string The Hmtl Content of page
	* @param string $url_image The Url of new merged image
	* @param string $fb_sdk The Uri of sdk for Facebook
	* @param integer $fb_appid The ID of Facebook app
	*
	**/
	public function createPageHtml($url_image,$fb_sdk,$fb_appid){	
		$url_page = str_replace('.jpg','.html',$url_image);
		$path_page = $this->getRealPath($url_page);
		$title = $this->getSiteTitle();
		$desc = $this->getSiteSlogan();
		$base_path  = $this->base_path_merged;

		//if directory not exits create it
		$this->_checkPath($base_path);
			$html="<html>\n";
			$html.="<head>\n";
				//$html.="<title>".$title."</title>\n";
			/*echo "url \n";
			var_dump($url_page);
			exit;*/
				$html.=$this->getHead();
				$html.= '<meta property="og:url" content="'.$url_page.'" />'."\n";
				$html.= '<meta property="og:type" content="website" />'."\n";
				$html.= '<meta property="og:title" content="'.$title.'" />'."\n";
				$html.=' <meta property="og:description"   content="'.$desc.'" />'."\n";
				$html.='<meta property="og:image" content="'.$url_image.'" />'."\n";
			$html.='</head>'."\n";
			$html.='<body class="merge-and-share-page">'."\n";
				$html.=$this->contentMsg($url_image);
				$html.=$this->createFacebookShare($fb_sdk,$fb_appid,$url_page);
				$html.='<div class="fb-share-button" data-href="'.$url.'"></div>'."\n";
			$html.='</body>'."\n";
		$html.='</html>';
		
		//write the file
		try {
			$html_page = fopen($path_page, "w");
			fwrite($html_page,$html);
			fclose($html_page);
			return '{"type":"iframe","content":"'.$url_page.'"}';
		} catch (Exception $e) {
			$this->log('Exception',$e);
			return  false;
		}
	}

	/**
	* @return string Html for Facebook Plugin
	**/
	public function createFacebookShare($fb_sdk,$fb_appid,$url_page){

		$url_sdk = 'http://www.facebook.com/sharer/sharer.php?app_id='.$fb_appid;
		$url_sdk.= '&sdk=joey';
		$url_sdk.= '&u='.urlencode($url_page);
		$url_sdk.= '&display=popup';
		$url_sdk.= 'ref=plugin&src=share_button';


		$html ='<div id="fb-share-div">';
    	$html.='<div id="fb-share">';
        $html.= '<a class="fb-click" href="'.$url_sdk.'" target="_blank">';
        $html.='Share';
        $html.='</a>';
    	$html.='</div>';
		$html.='</div>';

        http://www.facebook.com/dialog/feed?app_id={{fbapp_id}}&link={{link_url}}&message={{share_message|urlencode}}&display=popup&redirect_uri={{link_url}}" 
           
		return $html;
	}
	

}// end class
