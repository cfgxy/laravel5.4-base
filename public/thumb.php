<?php
/**
 * A simple thumbnail generator which accepts request uris like thumb.php?file=/uploads/06/1230/.45960180ab538t200x130.jpg 
 * and generates thumbnails from the original images.
 * Nginx proxy cache should be used to avoid unecessary requests to this uri
 * @author Joe Cai <joey.cai@gmail.com>
 *
 */

ini_set('display_errors', 'off');
error_reporting(E_ALL ^ E_NOTICE);
$file = $_GET['file'];
if (!$file) {
  on_error();
}
$filename = basename($file);
$path = dirname($file);
$path = '/var/www/public' . $path;

if (!preg_match('#\.(?<file>[^/]+)t(?<w>\d+)(x(?<h>\d+))?((?<mark>m)?((?<type>[nafsl])(?<c>\d{1,2})?)?)?\.jpg$#', $filename, $matches)) {
  on_error();
}

$p = array();
$p['file'] = $matches['file'];
$p['w'] = $matches['w'] ?: 100;
$p['h'] = $matches['h'] ?: $p['w'];
$p['type'] = $matches['type'] ?: 'a';
$p['c'] = (int)$matches['c'] ?: 85;
$p['mark'] = !!$matches['mark'];

$prefix = $p['file'];

$pattern = $path.'/'.$prefix.'*';
foreach (glob($pattern) as $f) {
  if (!$orig_file && (substr($f, -4) == 'orig')) {
    $orig_file = $f;
  } else if (!$marker_file && (substr($f, -5) == 'marker')) {
    $marker_file = $f;
  } else {
    $normal_file = $f;
  }
}

$orig_file = $orig_file ?: $normal_file;
$marker_file = $marker_file ?: $normal_file;

if ($p['mark']) {
  $base_file = $marker_file;
} else {
  $base_file = $orig_file;
}

if (!isset($base_file) || !$base_file) {
  if (isset($_GET['lab'])) {
    return placeholder($p['w'], $p['h']);
  }

  on_error();
}

$info = getimagesize($base_file); 
$ow = $info[0];
$oh = $info[1];


switch ($info['mime']) {
  case 'image/gif':
    $loader = 'imagecreatefromgif';
    break;
  case 'image/jpeg':
  case 'image/pjpeg':
    $loader = 'imagecreatefromjpeg';
    break;

  case 'image/png':
    $loader = 'imagecreatefrompng';
    break;
  default:
    on_error();
}

//source handler
$source = $loader($base_file);

switch ($p['type']) {
  case 'n':
    $scale_params = aspect_fit($ow, $oh, $p['w'], $p['h']);
    $scale_params['rect_x'] = 0;
    $scale_params['rect_y'] = 0;
    break;
  case 'l':
    $scale_params = aspect_fit($ow, $oh, $p['w'], $p['h']);
    $scale_params['rect_x'] = 0;
    $scale_params['rect_y'] = 0;
    if ($scale_params['rect_w'] > $ow || $scale_params['rect_h'] > $oh) {
      $scale_params['rect_w'] = $ow;
      $scale_params['rect_h'] = $oh;
    }   
    break;
  case 'f':
    $scale_params = aspect_fill($ow, $oh, $p['w'], $p['h']);
    break;
  case 's':
    $scale_params = scale_fill($ow, $oh, $p['w'], $p['h']);
    break;
  case 'a':
  default:
    $scale_params = aspect_fit($ow, $oh, $p['w'], $p['h']);
    break;
}

extract($scale_params);

if ($p['type'] == 'n' || $p['type'] == 'l') {
  $thumb = imagecreatetruecolor($rect_w, $rect_h);
} else {
  $thumb = imagecreatetruecolor($p['w'], $p['h']);
  if ($p['type'] == 'a') {
    $white = imagecolorallocate($thumb, 0xFF, 0xFF, 0xFF);
    imagefill($thumb, 0, 0, $white);
  }
}

imagecopyresampled($thumb, $source, $rect_x, $rect_y, 0, 0, $rect_w, $rect_h, $ow, $oh);
header('Content-Type: image/jpeg');
imagejpeg($thumb, null, $p['c']);
imagedestroy($thumb);


function on_error()
{
  header("Status: 404 Not Found");
  exit();
}


/**
 * create a place holder, only for testing environments
 * @param int $width
 * @param int $height
 */
function placeholder($width, $height)
{
  $im = imagecreatetruecolor($width, $height);
  $bgColor = imagecolorallocate($im, 239, 239, 239);
  $txtColor = imagecolorallocate($im, 100, 100, 100);
  imagefill($im, 0, 0, $bgColor);

  //width and height of the string
  $sw = 62; 
  $sh = 12;
  
  $sTop = $width < $sw ? 2 : round(($width - $sw) / 2);
  $sLeft = $height < $sh ? 2 : round(($height - $sh) / 2);
  
  imagestring($im, 3, $sTop, $sLeft,  'NOT FOUND', $txtColor);
  header('Content-Type: image/jpeg');
  imagejpeg($im, null, 60);
  imagedestroy($im);
}

function aspect_fit($ow, $oh, $tw, $th) {
  $th = $th ?: $tw;
  $rw = $ow / $tw;
  $rh = $oh / $th;

  $r = max($rw, $rh);
  $w = round($ow / $r);
  $h = round($oh / $r);
  $x = round(($tw - $w) / 2);
  $y = round(($th - $h) / 2);

  $w = min($w, $tw);
  $h = min($h, $th);
  return array('rect_x' => $x, 'rect_y' => $y, 'rect_w' => $w, 'rect_h' => $h);
}

function aspect_fill($ow, $oh, $tw, $th) {
  $th = $th ?: $tw;
  $rw = $ow / $tw;
  $rh = $oh / $th;

  $r = min($rw, $rh);
  $w = round($ow / $r);
  $h = round($oh / $r);
  $x = round(($tw - $w) / 2);
  $y = round(($th - $h) / 2);

  return array('rect_x' => $x, 'rect_y' => $y, 'rect_w' => $w, 'rect_h' => $h);
}

function scale_fill($ow, $oh, $tw, $th) {

  $x = 0; $y = 0;
  $w = $tw; $h = $th;

  return array('rect_x' => $x, 'rect_y' => $y, 'rect_w' => $w, 'rect_h' => $h);
}

