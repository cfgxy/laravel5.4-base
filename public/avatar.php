<?php

require __DIR__.'/../plugins/guxy-common/helpers.php';

$arr = explode('.', $_SERVER['QUERY_STRING']);
if (count($arr) == 1) {
    $encid = $arr[0];
} elseif (count($arr) == 2) {
    $encid = $arr[0];
    if (is_numeric($arr[1])) {
        $ver   = $arr[1];
    } else {
        $style = $arr[1];
    }
} else {
    $encid = $arr[0];
    $ver   = $arr[1];
    $style = $arr[2];
}
$id = guxy_decrypt($encid);

if (!$id || !is_numeric($id)) {
    header("HTTP/1.0 404 Not Found");
    exit;
}

$expires = 7200;
$avatar_path = __DIR__ . '/../storage/app/public/avatar';
if (is_file("$avatar_path/$encid.jpg")) {
    header("Cache-Control: maxage=$expires");
    header("Content-Type: image/jpg");
    $url = "/uploads/avatar/$encid.jpg";
} else {
    header("Content-Type: image/png");
    $url = "/dist/images/default-avatar.png";
}

header('Expires: ' . date(DATE_RFC1123, time() + $expires));
header('X-Accel-Redirect: ' . $url);


