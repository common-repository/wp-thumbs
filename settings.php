<?php
function wp_thumbs_settings() {
    $wp_thumbs_show_locations = get_option('wp_thumbs_show_locations');
    
	print '<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div><h2>WP Thumbs Settings</h2>';
 
    ///display options
    print '<form method="post" action="options.php" />';
    
    ///get settings
    settings_fields('wp-thumbs-setting');
    
    print '<table class="form-table">';
    print '<tr><td><label>Main Domain</label>';
    print '</td><td><input type="text" name="wp_thumbs_domain" size="40" value="'.get_option('wp_thumbs_domain').'" />
    <p class="description"> Your domain name without any trailing slash at the end or http:// at the begining or www. Used for setting cookies. Example: mysite.com.</p></td></tr>';
    print '<tr><td><label>Display Mode</label></td><td>';
    
    if (get_option('wp_thumbs_display_mode') == 'normal') {$display_mode_normal = 'selected';} else {$display_mode_normal = '';}
    if (get_option('wp_thumbs_display_mode') == 'like') {$display_mode_like = 'selected';} else {$display_mode_like = '';}
    if (get_option('wp_thumbs_display_mode') == 'thumb') {$display_mode_thumb = 'selected';} else {$display_mode_thumb = '';}
    
    print '<select name="wp_thumbs_display_mode" class="regular-text"><option value="normal" '.$display_mode_normal.'>Normal</option> <option value="like" '.$display_mode_like.'>Like Button Only</option> <option value="thumb" '.$display_mode_thumb.'>Thumbs buttons</option></select></td></tr>';
    
    if (get_option('wp_thumbs_display_location') == 'title') {$display_location_title = 'selected';} else {$display_location_title = '';}
    if (get_option('wp_thumbs_display_location') == 'content') {$display_location_content = 'selected';} else {$display_location_content = '';}
    if (get_option('wp_thumbs_show_clicks') == 'Y') {$show_clicks = 'checked';} else {$show_clicks = '';}
    if (get_option('wp_thumbs_show_graph') == 'Y') {$show_graph = 'checked';} else {$show_graph = '';}
    if (get_option('wp_thumbs_show_graph_clicks') == 'Y') {$show_graph_clicks = 'checked';} else {$show_graph_clicks = '';}
    if (get_option('wp_thumbs_show_users') == 'Y') {$show_users = 'checked';} else {$show_users = '';}
    if (get_option('wp_thumbs_show_thankyou') == 'Y') {$show_thankyou = 'checked';} else {$show_thankyou = '';}
    
    print '<tr><td valign="top"><label>Display Areas</label> </td><td>';
        $post_types = get_post_types(array('public' => true), 'objects');
	    $saved_post_types = isset($wp_thumbs_show_locations['post_types']) ? $wp_thumbs_show_locations['post_types'] : array();
            foreach($post_types as $post_type) {
                if (in_array($post_type->name, $saved_post_types)) {
                    $is_checked = 'checked';
                } else {
                    $is_checked = '';
                }
	           echo '<input id="wp_thumbs_show_locations[post_types]['.$post_type->name.']" name="wp_thumbs_show_locations[post_types]['.$post_type->name.']" type="checkbox" value="'.$post_type->name.'" '.$is_checked.' /> <span> '.$post_type->labels->name.'</span><br/>';
		                                       } 
    print '</td></tr>';
    
    print '<tr><td><label>Display Location</label> </td><td> <select name="wp_thumbs_display_location"> <option value="title" '.$display_location_title.'>Below the title</option> <option value="content" '.$display_location_content.'>Below the content</option> </select> </td></tr>';
    print '<tr><td><label>Show clicks beside buttons?</label> </td><td> <input type="checkbox" value="Y" name="wp_thumbs_show_clicks" '.$show_clicks.' /> </td></tr>';
    print '<tr><td><label>Show graph of clicks above buttons?</label> </td><td> <input type="checkbox" value="Y" name="wp_thumbs_show_graph" '.$show_graph.' /> </td></tr>';
    print '<tr><td valign="top"><label>Show number of clicks below graph?</label> </td><td> <input type="checkbox" value="Y" name="wp_thumbs_show_graph_clicks" '.$show_graph_clicks.' /> 
    <p class="description"> Only works when the graph is enabled.</p></td></tr>';
    print '<tr><td><label>Show to only users logged in?</label> </td><td> <input type="checkbox" value="Y" name="wp_thumbs_show_users" '.$show_users.' /> </td></tr>';
    print '<tr><td><label>Show "Thank You!" Message when user clicks a button?</label> </td><td> <input type="checkbox" value="Y" name="wp_thumbs_show_thankyou" '.$show_thankyou.' /> </td></tr>';
    print '</table>';
    
    ///values we do not want to change. Update clears these out otherwise
    print '<input type="hidden" name="wp_thumbs_table_name" value="'.get_option('wp_thumbs_table_name').'" />';
    print '<input type="hidden" name="wp_thumbs_db_version" value="'.get_option('wp_thumbs_db_version').'" />';
    
    print '<p class="submit">
    <input type="submit" class="button-primary" value="Save Changes" />
    </p>';
    
    print '</form>';
    
    print '<a href="http://www.alanpinnt.com/donations/" target="_blank">Please make a donation!</a> <br /><br /> <a href="http://www.alanpinnt.com/contact-me/" target="_blank">Need help with this plugin?</a>';

    print '<br /><br /><p class="description">Total Likes: '.wp_thumbs_count_all('like').' <br />Total Dislikes: '.wp_thumbs_count_all('dislike').'</p>';
    print '</div>';
    
    }

function wp_thumbs_menu() {
    add_submenu_page( 'options-general.php', 'WP Thumbs', 'WP Thumbs', 'manage_options', 'wp-thumbs', 'wp_thumbs_settings'); 	
}
add_action('admin_menu', 'wp_thumbs_menu');
?>