<?php

//if($_POST){
	$req =json_decode(file_get_contents('php://input'));
	$isJson = $req != NULL ?true:false;
	if($isJson){
		$left = property_exists($req,'left')?$req->left:'';
		$right =  property_exists($req,'right')?$req->right:'';
		$w =   property_exists($req,'width')?$req->width:'';
		$h =   property_exists($req,'height')?$req->height:'';
		
		//facebook app
		$fb_sdk =  property_exists($req,'fb_sdk')?$req->fb_sdk:'//connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v2.4';
		$fb_appid =  property_exists($req,'fb_appid')?$req->fb_appid:'';
		
		if(!empty($left) && !empty($right) && !empty($fb_appid)){
			
			require_once('class-merge-and-share-controller.php');

			/**********
			* setting *
			***********/
			$mas = new Merge_And_Share_Controller();
			
			$base_path= $mas->getBasePath();
			$base_path_upload = $mas->getBasePathUpload();
			$base_path_merged = $mas->getBasePathMerged();
			$base_url_merged = $mas->getBaseUrlMerged();;
			$right = $mas->getRealPath($right);
			$left = $mas->getRealPath($left);
			// get dimensions
			$w =!empty($w)?$w:600;//width of image
			$h =!empty($h)?$h:400; // height of image

			if(!$mas->exists($right) || !$mas->exists($left)){
				if(!$mas->exists($right)){
					$ouput = $mas->notFoundMsg($right);
				}elseif($mas->exists($left)){
					$ouput = $mas->notFoundMsg($right);
				}
				echo $ouput;
				exit;
			}
			
			//create image 
			$wm = get_option('mas_watermark');
			$new_image_url = $mas->createImageShared($w,$h,$left,$right,$wm);
			if($new_image_url === false){
				echo $mas->errorMsg("Sorry, there's a temporary error. Try later, thanks ...");
				exit;
			}
			
			//create landing page
			$new_page_url= $mas->createPageHtml($new_image_url,$fb_sdk,$fb_appid);

			//RESPONSE
			echo $new_page_url;
			
		}
	}
//}

?>