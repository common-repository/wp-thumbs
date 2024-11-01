<?php

/**
 * @author Alan Pinnt
 * @copyright 2012
 */

include '../../../wp-load.php';

if (isset($_POST['ajax']) && $_POST['ajax'] == 'reload') {
    $postid = $_POST['post'];
    print wp_thumbs_display_div($content,$postid);
}
?>