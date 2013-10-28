<?php
strip_tags(str_replace("<br/>", "\r\n", error_reporting(E_ALL)));
ini_set('display_errors', 1);


//VARS
$filesArray = array();
if($_POST['tar']) { $tar = $_POST['tar']; } else { $tar = './input/'; }
if($_POST['out']) { $out = $_POST['out']; } else { $out = './output/'; }
$inext = $_POST['inext'];
$outext = $_POST['outext'];
$size = $_POST['size'];
$aspect = $_POST['asp'];
$qual = $_POST['quality'];
if($_POST['name']) { $rename = $_POST['name']; }
$separator = $_POST['sepa'];
$presu = $_POST['presu'];
$gray_f = $_POST['gray'];
$bri_f = $_POST['bri'];
$cont_f = $_POST['cont'];

if($_POST['quality'] == 0) {
	$png_qual = $_POST['quality'];
}
elseif($_POST['quality'] == 100) {
	$png_qual = 9;
}
else {
	$png_qual = substr($_POST['quality'], 0, -1);
}



//BUILD FILES ARRAY or RETURN ERROR
if (glob($tar) != false) {
	$files = count(glob($tar."*".$inext));

	if($files >= 1) {
		$handle = opendir($tar);
		while (false !== ($entry = readdir($handle))) {
			if ($entry != "." && $entry != ".." && $entry != ".DS_Store") {
				//Check file type
				if($inext != ".*") {
					if(substr($entry, strrpos($entry, '.')) == $inext) {
						$filesArray[] = $entry;
					}
				}
				else {
					$filesArray[] = $entry;
				}
	        }
	    }

		//FILES PROCESSING
		foreach($filesArray as $key=>$file) {
			// Get new dimensions
			list($ancho_orig, $alto_orig) = getimagesize($tar."".$file);
			$ratio = $ancho_orig/$alto_orig;

			$tamarray = explode('x',$size);
			if($tamarray[0] == "") { $ancho_max = $ancho_orig; } else { $ancho_max = $tamarray[0]; } 
			if($tamarray[1] == "") { $alto_max = $alto_orig; } else { $alto_max = $tamarray[1]; }
			$ancho_nuevo = 0;
			$alto_nuevo = 0;
			$image_p = "";
			
			//Create new files
			if($inext == '.jpg') {
				$image = imagecreatefromjpeg($tar."".$file);
			}
			elseif($inext == '.gif') {
				$image = imagecreatefromgif($tar."".$file);
			}
			elseif($inext == '.png') {
				$image = imagecreatefrompng($tar."".$file);
			}
			else {
				$data = file_get_contents($tar."".$file); 
				$image = imagecreatefromstring($data);
			}


			//APPLY FILTERS
				//grayscale
				if($gray_f == 'true') {
				    imagefilter($image, IMG_FILTER_GRAYSCALE);
				}else {}
				//brightness
				if($bri_f != 'none') {
				    imagefilter($image, IMG_FILTER_BRIGHTNESS, $bri_f);
				}else {}
				//contrast
				if($cont_f != 'none') {
				    imagefilter($image, IMG_FILTER_CONTRAST, $cont_f);
				}else {}


			//RESIZE
			if($ancho_max/$alto_max > $ratio) {
				if($aspect == 'true') { $ancho_nuevo = $alto_max*$ratio; } else { $ancho_nuevo = $ancho_max; }
				$alto_nuevo = $alto_max;
			}
			else {
				if($aspect == 'true') { $alto_nuevo = $ancho_max/$ratio; } else { $alto_nuevo = $alto_max; }
				$ancho_nuevo = $ancho_max;
			}

			//SET IMAGES
			$image_p = imagecreatetruecolor($ancho_nuevo, $alto_nuevo);
			imagecopyresampled($image_p, $image, 0, 0, 0 ,0, $ancho_nuevo, $alto_nuevo, $ancho_orig, $alto_orig);
			

			// RENAME
			$filesTotal = count($filesArray);
			$digits = count(str_split($filesTotal));
			$renameto = "";
			
			if(isset($rename)) {
				if($presu == 'pre') {
					$name = $rename."".$separator."".sprintf("%0".$digits."s", $key);
					$renameto = $rename."".$separator."(n)";
				}
				else {
					$name = sprintf("%0".$digits."s", $key)."".$separator."".$rename;
					$renameto = "(n)".$separator."".$rename;
				}
			}
			else {
				$name = substr($file, 0, -4);
				$renameto = "no rename";
			}

			//OUTPUT FILES
			if($outext == 'jpg') {
				imagejpeg($image_p, $out."".$name.".jpg", $qual);
			}
			elseif($outext == 'gif') {
				imagegif($image_p, $out."".$name.".gif");
			}
			elseif($outext == 'png') {
				imagepng($image_p, $out."".$name.".png", $png_qual);
			}
			else {
				imagejpeg($image_p, $out."".$name.".jpg", $qual);
			}

		}

		//LOG
		echo "Files successfully processed!\r\n\r\n";

		echo "::TIME::\r\n";
		echo date('m-d-Y h:i:s A')."\r\n\r\n";

		echo "::DETAILS::\r\n";
		echo "Input folder: ".$tar."\r\n";
		echo "Output folder: ".$out."\r\n";
		echo "Input file type/s: ".$inext."\r\n";
		echo "Output file type: ".$outext."\r\n";
		echo "Resize to: ".$size."\r\n";
		echo "Preserve aspect ratio: ".$aspect."\r\n";
		echo "Quality: ".$qual."\r\n";
		echo "Rename to: ".$renameto."\r\n";
		echo "Grayscale filter: ".$gray_f."\r\n";
		echo "Brightness filter: ".$bri_f."\r\n";
		echo "Contrast filter: ".$cont_f."\r\n";

	}

	else {
		echo "No files were found.";
	}
}
else {
	echo "The selected TARGET directory doesn't exist.";
}

?>