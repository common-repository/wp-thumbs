<?php
//////Author: Alan Pinnt
//////Created: 21 - 11 - 2012 - 18:55

include '../../../wp-load.php';

global $wpdb,$current_user,$wpmu_version;

if ($wpmu_version) {
    $current_site = get_current_site();
    $url = $current_site->domain;
} else {
    $url = get_option('wp_thumbs_domain');
}


if (isset($_POST['data']) && $_POST['data'] == 'like') {

$postid = $_POST['id'];

///if user is logged in or not
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $userid = $current_user->ID;
    $wp_thumbs_check = wp_thumbs_check_user($current_user->ID,$postid);
} else {
    $wp_thumbs_check = false;
    $userid = 0;  
}

setcookie("$postid", 'like', time()+36000, "/", $url);
   
//register click - like
if ($_COOKIE[$postid] || $wp_thumbs_check == true) {
print 'no';
} else {
$wpdb->query("INSERT INTO ".get_option('wp_thumbs_table_name')." (post,type,ip,userid) VALUES ('".$postid."','like','".$_SERVER['REMOTE_ADDR']."','".$userid."')");
print 'yes';
}


} elseif (isset($_POST['data']) && $_POST['data'] == 'dislike') {

$postid = $_POST['id'];

///if user is logged in or not
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $userid = $current_user->ID;
    $wp_thumbs_check = wp_thumbs_check_user($current_user->ID,$postid);
} else {
    $wp_thumbs_check = false;
    $userid = 0;
}

setcookie("$postid", 'dislike', time()+36000, "/", $url);   

//register click - dislike
if ($_COOKIE[$postid] || $wp_thumbs_check == true) {
print 'no';
} else {
$wpdb->query("INSERT INTO ".get_option('wp_thumbs_table_name')." (post,type,ip,userid) VALUES ('".$postid."','dislike','".$_SERVER['REMOTE_ADDR']."','".$userid."')");
print 'yes';
}
}
?>