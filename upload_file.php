<?php

$position = $_POST["location"];
list($width_orig, $height_orig) = getimagesize($_FILES["original"]["tmp_name"]);
list($width_add, $height_add) = getimagesize($_FILES["addition"]["tmp_name"]); 

$type = false;

function open_image ($file) {
    //detect type and process accordingly
    global $type;
    $size=getimagesize($file);
    switch($size["mime"]){
        case "image/jpeg":
            $im = imagecreatefromjpeg($file); //jpeg file
        break;
        case "image/gif":
            $im = imagecreatefromgif($file); //gif file
      break;
      case "image/png":
          $im = imagecreatefrompng($file); //png file
      break;
    default: 
        $im=false;
    break;
    }
    return $im;
}

$orig = open_image($_FILES["original"]["tmp_name"]);
$add = open_image($_FILES["addition"]["tmp_name"]);

if(($position % 2) == 0)
{
	$ratio = $height_add / $width_add;
	$width_add2 = $width_orig;
	$height_add2 = $ratio * $width_add2;
	$new_height = $height_orig + $height_add2;
	$new_width = $width_orig;
	
	$output = ImageCreateTrueColor($new_width, $new_height);
	$add_new = ImageCreateTrueColor($width_add2, $height_add2);
	imagecopyResampled ($add_new, $add, 0, 0, 0, 0, $width_add2, $height_add2, $width_add, $height_add);
	
	if($position == 2)
	{
		imagecopy($output, $add_new, 0, 0, 0, 0, $width_add2, $height_add2);
		imagecopy($output, $orig, 0, $height_add2, 0, 0, $width_orig, $height_orig);
	}
	else
	{
		imagecopy($output, $add_new, 0, $height_orig, 0, 0, $width_add2, $height_add2);
		imagecopy($output, $orig, 0, 0, 0, 0, $width_orig, $height_orig);
	}
}
else
{
	$ratio = $height_add / $width_add;
	$height_add2 = $height_orig;
	$width_add2 = $height_add2 / $ratio;
	$new_width = $width_orig + $width_add2;
	$new_height = $height_orig;
	
	$output = ImageCreateTrueColor($new_width, $new_height);
	$add_new = ImageCreateTrueColor($width_add2, $height_add2);
	imagecopyResampled ($add_new, $add, 0, 0, 0, 0, $width_add2, $height_add2, $width_add, $height_add);
	
	if($position == 1)
	{
		imagecopy($output, $add_new, 0, 0, 0, 0, $width_add2, $height_add2);
		imagecopy($output, $orig, $width_add2, 0, 0, 0, $width_orig, $height_orig);
	}
	else
	{
		imagecopy($output, $add_new, $width_orig, 0, 0, 0, $width_add2, $height_add2);
		imagecopy($output, $orig, 0, 0, 0, 0, $width_orig, $height_orig);
	}
}
header('Content-type: image/jpeg');
$name=explode(".", basename($_FILES["original"]["tmp_name"]));
header("Content-Disposition: inline; filename=".$name[0]."_t.jpg");
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($url)) . ' GMT');
header("Cache-Control: public");
header("Pragma: public");
imagejpeg($output);
imagedestroy($output);
imagedestroy($orig);
imagedestroy($add);
imagedestroy($add_new);
?>
