<?php
error_reporting(E_ALL & ~E_NOTICE);

// new sizes view:
$thump_check = explode('/',$_GET['thumb']);
if (strpos($thump_check[0], 'x') !== false and count($thump_check)>1) {
  $dimensions = explode('x',$thump_check[0]);
  if(is_numeric($dimensions[0]) and is_numeric($dimensions[1])) {
    $_GET['w'] = $dimensions[0];
    $_GET['h'] = $dimensions[1];
  }
  $_GET['thumb'] = implode('/',array_slice($thump_check,1));
} 

// protection!!
if ($_GET['w'] > 2000) $_GET['w'] = 2000;
if ($_GET['h'] > 2000) $_GET['h'] = 2000;


if (isset($_GET['thumb']) and $_GET['thumb']!='') {
  if (strpos($_GET['thumb'], 'products/') === false) {
    $watermark = false;   
  } 
  
  $image = __DIR__ . '/images/' . $_GET['thumb'];
           
	if (file_exists($image)) {
	    $image_size = getimagesize($image);
  } else {
	    $image = __DIR__ . '/images/lazybg.jpg';
	    $image_size = getimagesize($image);
	    $watermark = false;
	}    
  
  if(is_array($image_size)) { 
  	
  	switch ($image_size[2]) {
  	    case IMAGETYPE_GIF  :
  	        $img = ImageCreateFromGIF($image);
  	        break;
  	    case IMAGETYPE_JPEG :
  	        $img = imagecreatefromjpeg($image);
  	        break;
  	    case IMAGETYPE_PNG  :
  	        $img = ImageCreateFromPNG($image);   
  	        break;
  	    default :
  	        $img = false;
  	}  
    
    $last_modified_time = filemtime($image); 
    $etag = md5_file($image); 

    header("Last-Modified: ".gmdate("D, d M Y H:i:s", $last_modified_time)." GMT"); 
    header("Etag: $etag"); 

    if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time || trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) { 
        header("HTTP/1.1 304 Not Modified"); 
        exit; 
    }  
  	                         
  	header("Content-Type: " . $image_size['mime']);
  	
  	// load watermark to memory
  	if ($watermark != '' and $watermark != false) {
  	
  	    if (!isset($_GET['w'])) $_GET['w'] = $image_size[0];
  	    if (!isset($_GET['h'])) $_GET['h'] = $image_size[1];
  	
        // if watermark width > image width, then resize watermark:
        if($watermark_size[0]>=$image_size[0]) {
          $ratio = 0.7;
         
          $dst = imagecreatetruecolor($watermark_size[0]*$ratio,$watermark_size[1]*$ratio);
        	imagesavealpha($dst, true);    
        	imagefill($dst, 0, 0, IMG_COLOR_TRANSPARENT);
          imagecopyresampled($dst,$watermark,0,0,0,0,$watermark_size[0]*$ratio,$watermark_size[1]*$ratio,$watermark_size[0],$watermark_size[1]);
          $watermark = $dst;
          $watermark_size[0]*=$ratio;
          $watermark_size[1]*=$ratio;
        }
    
  	    // 	bottom right watermark:
  	    $watermark_pos_x = $image_size[0] - $watermark_size[0];
  	    $watermark_pos_y = $image_size[1] - $watermark_size[1];
      
        // 	watermark on center:      
        /*$background = imagecolorallocate($watermark , 0, 0, 0);
        imagecolortransparent($watermark, $background);
        imagealphablending($watermark, false);
        imagesavealpha($watermark, true);
  	    $watermark_pos_x = ($image_size[0] - $watermark_size[0])/2;
  	    $watermark_pos_y = ($image_size[1] - $watermark_size[1])/2; */
        
  	    // do no add watermark to small images:
  	    if (($_GET['w'] != '' or $_GET['h'] != '') and ($_GET['w'] < 300 and $_GET['h'] < 300)) {
  	    } elseif ($image_size[0] < 300 and $image_size[1] < 300) {
  	    } else imagecopy($img, $watermark, $watermark_pos_x, $watermark_pos_y, 0, 0, $image_size[0], $image_size[1]);
  	
  	    // watermark pave
  	    //imageSetTile ($img, $watermark); // watermark pave
  	    //imagefilledrectangle($img,0,0,$image_size[0],$image_size[1],IMG_COLOR_TILED);
  	
  	    // END watermark
  	}
  	
  	
  	if (($_GET['w'] == '' and $_GET['h'] == '') or ($image_size[0]<$_GET['w'] and $image_size[1]<$_GET['h'])) {
  	    $fix_to_X = $image_size[0];
  	    $fix_to_Y = $image_size[1];
  	} else {
  	    //$fix_to_X = 150;
  	    //$fix_to_Y = 150;
  	    if (isset($_GET['w'])) $fix_to_X = $_GET['w'];
  	    if (isset($_GET['h'])) $fix_to_Y = $_GET['h'];
  	}
  	
  	
  	$current_X = $image_size[0];
  	$current_Y = $image_size[1];
  	
  	if ($current_X > $current_Y) {
  	    $x = $fix_to_X;            //
  	    $y = intval($x * $current_Y / $current_X);    //
  	} else {
  	    $y = $fix_to_Y;
  	    $x = intval($y * $current_X / $current_Y);    //
  	
  	}
  	if (isset($_GET['r'])) {
  	    if ($_GET['r'] == 'x') {
  	        $y = $fix_to_Y;
  	        $x = intval($y * $current_X / $current_Y);    //
  	    }
  	    if ($_GET['r'] == 'y') {
  	        $x = $fix_to_X;            //
  	        $y = intval($x * $current_Y / $current_X);    //
  	    }
  	}
  	
  	$thumb = imagecreatetruecolor($x, $y);
  	
  	//imagealphablending($thumb,false);
  	imagesavealpha($thumb, true);    //
  	
  	 
  	imagefill($thumb, 0, 0, IMG_COLOR_TRANSPARENT);
  	imagecopyresampled($thumb, $img, 0, 0, 0, 0, $x, $y, $current_X, $current_Y);
  	   
    imageinterlace($thumb, true);   
    if(isset($_GET['rotate'])){
        $rotate = (int)$_GET['rotate'] > 360 ? 360 : (int)$_GET['rotate'];
        $thumb = imagerotate($thumb,$rotate,0);
    }
  	switch ($image_size[2]) {
  	    case IMAGETYPE_GIF  :   
  	        imagegif($thumb);
  	        break;
  	        break;
  	    case IMAGETYPE_JPEG :
  	        imagejpeg($thumb, null, 85);
  	        break;
  	        break;
  	    case IMAGETYPE_PNG  :
  	        imagepng($thumb);
  	        break;
  	    default :
  	        $im_med = false;
  	}
  	imagedestroy($thumb);
  	die;
    
  } elseif(mime_content_type($image)=='image/svg+xml') { //svg
    header("Content-Type: image/svg+xml");
    echo file_get_contents($image);
  }
} else {
    header("HTTP/1.0 301 Moved Permanently");
    header("Location: /");
    exit;
}
?>