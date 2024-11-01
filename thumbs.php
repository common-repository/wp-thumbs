<?php
/*
Plugin Name: WP Thumbs Plugin
Plugin URI: http://www.alanpinnt.com/wp-thumbs/
Description: This plugin allows you to have a like or dislike option on all pages or posts. Highly customizable, simple to setup, and no issues.
Version: 1.1
Author: Alan pinnt
Author URI: http://www.alanpinnt.com/
License: GPL3
    Copyright 2012 Alan Pinnt www.alanpinnt.com
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WP_THUMBS_PLUGIN_NAME', 'WP Thumbs Plugin');
define('WP_THUMBS_PLUGIN_URI', 'http://www.alanpinnt.com/wp-thumbs/');
define('WP_THUMBS_VERSION', '1.1');
define('WP_THUMBS_DB_VERSION', '1.0');
define('WP_THUMBS_AUTHOR', 'Alan Pinnt');
define('WP_THUMBS_AUTHOR_URI', 'http://www.alanpinnt.com/');

define('WP_THUMBS_URL', plugin_dir_url(__FILE__) );
define('WP_THUMBS_PATH', plugin_dir_path(__FILE__) );
define('WP_THUMBS_BASENAME', plugin_basename( __FILE__ ) );

function wp_thumbs_settings_reg() {
    register_setting('wp-thumbs-setting', 'wp_thumbs_db_version');
    register_setting('wp-thumbs-setting', 'wp_thumbs_table_name');
    register_setting('wp-thumbs-setting', 'wp_thumbs_domain');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_locations');
	register_setting('wp-thumbs-setting', 'wp_thumbs_display_mode');
    register_setting('wp-thumbs-setting', 'wp_thumbs_display_location');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_users');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_graph');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_graph_clicks');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_clicks');
    register_setting('wp-thumbs-setting', 'wp_thumbs_show_thankyou');
}
add_action( 'admin_init', 'wp_thumbs_settings_reg');

function wp_thumbs_install_table() {
   global $wpdb;
    
    if (get_option( "wp_thumbs_db_version" ) != '') {
        $installed_ver = get_option( "wp_thumbs_db_version" );
   } else {
        $installed_ver = 0;
   }

   if( $installed_ver < WP_THUMBS_DB_VERSION ) {
   $table_name = $wpdb->prefix . "plugin_wp_thumbs";
      
   $q1 = "CREATE TABLE $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    time timestamp,
    post int(11) NULL,
    type VARCHAR(10) DEFAULT '' NOT NULL,
    ip VARCHAR(20) DEFAULT '' NOT NULL,
    userid int(11) DEFAULT '0' NOT NULL,
    UNIQUE KEY id (id));";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($q1);
   ///set default values   
   update_option("wp_thumbs_db_version", WP_THUMBS_DB_VERSION);
   update_option("wp_thumbs_table_name", $table_name);
   update_option("wp_thumbs_domain", 'domain.com');
   update_option("wp_thumbs_display_mode", 'normal');
   update_option("wp_thumbs_display_location", 'content');
   update_option("wp_thumbs_show_users", 'N');
   update_option("wp_thumbs_show_graph", 'N');
   update_option("wp_thumbs_show_graph_clicks", 'N');
   update_option("wp_thumbs_show_clicks", 'Y');
   update_option("wp_thumbs_show_thankyou", 'Y');
   }
}
register_activation_hook(__FILE__,'wp_thumbs_install_table');

///used for upgrades
function wp_thumbs_db_check() {

    if (get_option('wp_thumbs_db_version') != WP_THUMBS_DB_VERSION) {
        wp_thumbs_update_table();
    }
}
add_action('plugins_loaded', 'wp_thumbs_db_check');

function wp_thumbs_update_table() {
    ///if there is an update to the table place code here. Since its v1.0 no need but function is here for the future.
}


function wp_thumbs_check_user($userid,$postid) {
    global $wpdb;
    
    $q1 = $wpdb->get_var("SELECT COUNT(id) FROM ".get_option('wp_thumbs_table_name')." WHERE userid='".$userid."' AND post='".$postid."'");
    
    if ($q1 >= 1) {
        return true;
    } else {
        return false;
    } 
}

function wp_thumbs_count($postid) {
    global $wpdb,$wp_thumbs_count;
    
    $q1 = $wpdb->get_var("SELECT COUNT(type) FROM ".get_option('wp_thumbs_table_name')." WHERE post='".$postid."' AND type='like'"); 
    $q2 = $wpdb->get_var("SELECT COUNT(type) FROM ".get_option('wp_thumbs_table_name')." WHERE post='".$postid."' AND type='dislike'"); 
    $wp_thumbs_count['like'] = $q1;
    $wp_thumbs_count['dislike'] = $q2;
}

function wp_thumbs_count_all($type) {
    global $wpdb;
    
    $q1 = $wpdb->query("SELECT type FROM ".get_option('wp_thumbs_table_name')." WHERE type='".$type."'");
    $q1->num_rows; 
    return $q1;
}


///include javascript and css
function wp_thumbs_javascript() {
    wp_enqueue_script('thumbs_js', WP_THUMBS_URL.'js/thumbs.js',array('jquery'));
}
add_action('init','wp_thumbs_javascript');
function wp_thumbs_css() {
    wp_enqueue_style('wp-thumbs', WP_THUMBS_URL.'css/thumbs.css');
}
add_action('init','wp_thumbs_css');


///display stuff
function wp_thumbs_display_check() {
	global $post;

    $wp_thumbs_show_locations = get_option('wp_thumbs_show_locations');

	if(is_array($wp_thumbs_show_locations)) {
		if(in_array(get_post_type($post), $wp_thumbs_show_locations['post_types'])) {
			add_filter('the_content', 'wp_thumbs_display_div');
		} 
	}
}
add_action('template_redirect', 'wp_thumbs_display_check');


function wp_thumbs_build_graph($like=0,$dislike=0) {
    
        $is_bar = 220;
        /////EQ
        $total = $like + $dislike;
        
        $is_like = round($like/$total, 2);
        $is_like = round($is_bar * $is_like);
        
        $is_dislike = round($dislike/$total, 2,PHP_ROUND_HALF_DOWN);
        $is_dislike = round($is_bar * $is_dislike);
        
        ///Display clicks option
        if (get_option('wp_thumbs_show_graph_clicks')=='Y') {
            $show_clicks = '<div class="wp-thumbs-graph-clicks">'.$like.' Likes, '.$dislike.' Dislikes</div>';
            $show_clicks_enabled = '';
        } else {
            $show_clicks = '';
            $show_clicks_enabled = 'margin-bottom:6px;';
        }
        
        return '<div style="width:'.$is_bar.'px;height:10px;'.$show_clicks_enabled.'" class="wp-thumbs-graph-main">
        <div style="width:'.$is_like.'px;height:10px;" class="wp-thumbs-graph-likes">
        </div>
        <div style="width:'.$is_dislike.'px;height:10px;" class="wp-thumbs-graph-dislikes">
        </div>
        </div>
        '.$show_clicks.'';
    
}


function wp_thumbs_display_div($content,$postid=0) {
    global $post,$wp_thumbs_count;
    
    if ($postid==0) {
            $postid = $post->ID;
        } else {
            $postid = $postid;
        }
        
    //// if wp_thumbs_show_users is set to Y or only show to logged in users
    if (get_option('wp_thumbs_show_users') == 'Y' && is_user_logged_in() == true) {
        $display = true;
    } elseif (get_option('wp_thumbs_show_users') == 'Y' && is_user_logged_in() == false)  {
        $display = false;
    } else {
        $display = true;
    }
    
    ///Display Clicks?
    if (get_option('wp_thumbs_show_clicks') == 'Y') {
        wp_thumbs_count($postid);
        $likes = '<div class="wp-thumbs-counter-like">'.$wp_thumbs_count['like'] . ' Likes</div>';
        $dislikes =  '<div class="wp-thumbs-counter-dislike">'.$wp_thumbs_count['dislike'] . ' Dislikes</div>';
    } else {
        $likes = '';
        $dislikes = '';    
    }
    
    ///Display graph
    if (get_option('wp_thumbs_show_graph') == 'Y') {
        wp_thumbs_count($postid);
        $show_graph = wp_thumbs_build_graph($wp_thumbs_count['like'],$wp_thumbs_count['dislike']);
    } else {
        $show_graph = '';   
    }
    
    ///Display Location
    if (get_option('wp_thumbs_display_location') == 'title') {
        $contentafter = $content;
    } else {
        $contentafter = '';
    }
    
    if (get_option('wp_thumbs_display_location') == 'content') {
        $contentbefore = $content;
    } else {
        $contentbefore = '';
    }
    
    ///Display 
    if (get_option('wp_thumbs_show_thankyou') == 'Y') {
        $show_thankyou = '<div id="wp-thumbs-message-'.$postid.'" class="wp-thumbs-message" style="display: none">Thank You!</div>';
    } else {
        $show_thankyou = '';
    }
    
    ///Display mode options
    if (get_option('wp_thumbs_display_mode') == 'normal' && $display == true) {
        return $contentbefore.'<div id="wp-thumbs-post-'.$postid.'" class="wp-thumbs-div">'.$show_graph.'
        <input id="wp-thumbs-like-button" title="Like" alt="'.$postid.'" type="button" value="Like" class="wp-thumbs-like-button" /> '.$likes.' 
        <input id="wp-thumbs-dislike-button" title="Dislike" alt="'.$postid.'" type="button" value="Dislike" class="wp-thumbs-dislike-button" /> '.$dislikes.'
        </div>'.$show_thankyou.$contentafter;
    } elseif (get_option('wp_thumbs_display_mode') == 'like' && $display == true) {
        return $contentbefore.'<div id="wp-thumbs-post-'.$postid.'" class="wp-thumbs-div">'.$show_graph.'
        <input id="wp-thumbs-like-button" title="Like" alt="'.$postid.'" type="button" value="Like" class="wp-thumbs-like-button" /> '.$likes.'
        </div>'.$show_thankyou.$contentafter;
    } elseif (get_option('wp_thumbs_display_mode') == 'thumb' && $display == true) {
        return $contentbefore.'<div id="wp-thumbs-post-'.$postid.'" class="wp-thumbs-div">'.$show_graph.'
        <input id="wp-thumbs-like-button" title="Like" alt="'.$postid.'" type="button" value="Like" class="wp-thumbs-like-thumb" /> '.$likes.'
        <input id="wp-thumbs-dislike-button" title="Dislike" alt="'.$postid.'" type="button" value="Dislike" class="wp-thumbs-dislike-thumb" /> '.$dislikes.'
        </div>'.$show_thankyou.$contentafter;
    } else {
        return $content;
    }

}

///include settings page
include WP_THUMBS_PATH.'settings.php';
?>
