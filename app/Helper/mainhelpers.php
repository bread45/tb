<?php

use Modules\Permissions\Entities\PermissionManager;
use App\User;
use App\Notification;
use Modules\CMSPages\Entities\CMSPages;
use Modules\GeneralSettings\Entities\GeneralSettings;

function isAuthorize($route_name) {
    if (Auth::user()->role_id == '1') {
        return true;
    }
    if (strpos($route_name, 'store') !== false) {
        return true;
    }
    if (strpos($route_name, 'getall') !== false) {
        return true;
    }
    $check = PermissionManager::where('role_id', Auth::user()->role_id)->whereHas('routes', function ($q) use ($route_name) {
                $q->where('route_name', $route_name);
            })->first();
    if ($check && $check->status == 'active') {
        return true;
    }
//    return true;
    return false;
}

function activeMenu($uri = '') {
    $active = '';
    if (Request::segment(1) == $uri || Request::is(Request::segment(1) . '/' . $uri . '/*') || Request::is(Request::segment(1) . '/' . $uri) || Request::is($uri)) {
        $active = 'kt-menu__item--active kt-menu__item--open';
    }
    return $active;
}

function activeWidgetMenu($uri = '') {
    $active = '';
    if (Request::is(Request::segment(1) . '/' . $uri . '/*') || Request::is(Request::segment(1) . '/' . $uri) || Request::is($uri)) {
        $active = 'kt-widget__item--active kt-menu__item--open';
    }
    return $active;
}

function activeSubMenu($uri = '') {
    $actives = '';
    if (Request::is(Request::segment(1) . '/' . $uri . '/*') || Request::is(Request::segment(1) . '/' . $uri) || Request::is($uri)) {
        $actives = 'kt-menu__item--open';
    }
    return $actives;
}

function isFileExist($path = '', $image = '') {
    $image_path = public_path($path) . $image;
    if ($path && $image) {
        if (file_exists($image_path)) {
            return asset(isset($image) ? $path . $image : '/media/users/100_1.jpg');
        } else {
            return asset('/sitebucket/other/no_profile_img.png');
        }
    } else {
        return asset('/sitebucket/other/no_profile_img.png');
    }
}

function get_api_key($request) {
    $input = $request->all();
    $headers = $request->headers->all();
    if (isset($headers["apikey"]) && count($headers["apikey"]) > 0) {
        $input["apikey"] = $headers["apikey"][0];
    }
    if (isset($headers["userid"]) && count($headers["userid"]) > 0) {
        $input["userid"] = $headers["userid"][0];
    }
    if (isset($headers["type"]) && count($headers["type"]) > 0) {
        $input["type"] = $headers["type"][0];
    }
    return $input;
}

function removeAllImage($path, $image) {
    if (file_exists(public_path($path . $image))) {
        unlink(public_path($path . '/' . $image));
    }
    if (file_exists(public_path($path . '/thumbnail/' . 'small_' . $image))) {
        unlink(public_path($path . '/thumbnail/' . 'small_' . $image));
    }
    if (file_exists(public_path($path . '/thumbnail/' . 'medium_' . $image))) {
        unlink(public_path($path . '/thumbnail/' . 'medium_' . $image));
    }
    if (file_exists(public_path($path . '/thumbnail/' . 'large_' . $image))) {
        unlink(public_path($path . '/thumbnail/' . 'large_' . $image));
    }
}

function campaignCreate($type, $options, $content, $segment_opts = NULL, $type_opts = NULL) {
    $params = array();
    $params["type"] = $type;
    $params["options"] = $options;
    $params["content"] = $content;
    $params["segment_opts"] = $segment_opts;
    $params["type_opts"] = $type_opts;
    return callServer("campaignCreate", $params);
}

function callServer( $url, $request_type, $api_key, $data = array() ) {
	if( $request_type == 'GET' )
		$url .= '?' . http_build_query($data);
 
	$mch = curl_init();
	$headers = array(
		'Content-Type: application/json',
		'Authorization: Basic '.base64_encode( 'user:'. $api_key )
	);
	curl_setopt($mch, CURLOPT_URL, $url );
	curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
	//curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
	curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
	curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
	curl_setopt($mch, CURLOPT_TIMEOUT, 10);
	curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection
 
	if( $request_type != 'GET' ) {
		curl_setopt($mch, CURLOPT_POST, true);
		curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
	}
        $response = curl_exec($mch);
	return json_decode($response);
}
function profile_details($column){
    $User = User::where('id',2)->first();
    return $User->$column;
}
function getCmsPages($slug)
{   
    $cmspg = CMSPages::where('slug', $slug)->first();
    
    if (!empty($cmspg)) {
        if (!empty($cmspg->banner_image)) {
            $cmspg->banner_image_large = asset('images/cmspages') . '/thumbnail/large_' . $cmspg->banner_image;
            $cmspg->banner_image = asset('images/cmspages') . '/' . $cmspg->banner_image;
        } 
        return $cmspg;
    } 
}
function getSetting($slug)
{   
    $cmspg = GeneralSettings::where('attr_key', $slug)->first();    
    if (!empty($cmspg)) {         
        return $cmspg->attr_value;
    } 
}
 
function truncate($text, $length = 200, $dots = true) {
    $text = trim(preg_replace('#[\s\n\r\t]{2,}#', ' ', $text));
    $text_temp = $text;
    while (substr($text, $length, 1) != " ") { $length++; if ($length > strlen($text)) { break; } }
    $text = substr($text, 0, $length);
    return $text . ( ( $dots == true && $text != '' && strlen($text_temp) > $length ) ? '...' : ''); 
}
function GetToken($url, $client_secret, $code) {
    $curl = curl_init();
    $post_data = array('client_secret' => $client_secret, 'code' => $code, 'grant_type' => 'authorization_code');
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }
}

function GetTokenByRefreshToken($url, $client_id, $client_secret, $refresh_token) {
    $curl = curl_init();
    $scope = 'tempest.orders.read tempest.orders.write tempest.products.read tempest.products.write tempest.categories.read tempest.categories.write tempest.settings.orderstatuses.read';

    $post_data = array('client_id' => $client_id, 'client_secret' => $client_secret, 'refresh_token' => $refresh_token, 'grant_type' => 'refresh_token', 'scope' => $scope);
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_data
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }
}

function call($url, $access_token, $method, $post_data = array()) {
    $curl = curl_init();
    if ($method == 'POST') {

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $access_token",
                "Content-Type: application/json"
            ),
        ));
    } elseif ($method == 'PUT') {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $access_token",
                "Content-Type: application/json"
            ),
        ));
    } else {
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $access_token"
            ),
        ));
    }
    $response = curl_exec($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }
}
function addhttp($url) {
    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
function resize_image($file, $w, $h, $crop=false) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
    //Get file extension
    $exploding = explode(".",$file);
    $ext = end($exploding);
    
    switch($ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}
function calculate_stars($max, $rating){
        $full_stars=floor($rating);
        $half_stars = ceil($rating-$full_stars);
        $gray_stars = $max-($full_stars + $half_stars);
        return array ($full_stars, $half_stars, $gray_stars);
    }
    function notificationCreate($from_user_id, $to_user_id, $type, $title, $message) {
    Notification::create([
        'from_user_id' => $from_user_id,
        'to_user_id' => $to_user_id,
        'notification_type' => $type,
        'title' => $title,
        'message' => $message,
        'url' => '',
    ]);
}