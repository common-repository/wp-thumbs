=== Plugin Name ===
Contributors: apinnt
Donate link: http://www.alanpinnt.com/
Tags: wepay, wepay plugin
Requires at least: 2.0.2
Tested up to: 3.4
Stable tag: 1.1
License: GPL3

WP Thumbs is a voting plugin that allows users to like or dislike posts and pages. There are many customization options.  

== Description ==

WP Thumbs is a voting plugin that allows users to like or dislike posts and pages. 

Features:

*Thumbs up or down mode
*Like button only mode
*Like/Dislike button mode
*Graph of clicks
*Customizable via CSS
*WPMU Compatible
*User only or cookie based security.
*Placement of buttons
*Page or Post Placement

Future:

*IP based security
*Reset likes/dislikes from wp-admin editor
    
== Installation ==

e.g.

1. Upload the entire wp-thumbs plugin folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the settings page under wp-admin, settings > WP Thumbs
4. Place your domain name in the first text box and select the options you want. Once selected hit "Save Changes."
5. Go to your home page and see what you think.
    
Comments, questions, see http://www.alanpinnt.com/wp-thumbs/

== Frequently Asked Questions ==

1. Edit the css file found in the css folder under this plugin. Here is the class names:
    wp-thumbs-div
    wp-thumbs-like-button
    wp-thumbs-dislike-button
    wp-thumbs-like-thumb
    wp-thumbs-dislike-thumb
    wp-thumbs-counter-like
    wp-thumbs-counter-dislike
    wp-thumbs-message
    wp-thumbs-graph-main
    wp-thumbs-graph-likes
    wp-thumbs-graph-dislikes 
    wp-thumbs-graph-clicks
2. Changing the height of the clicks graph is under line line 180, function wp_thumbs_build_graph, file thumbs.php



== Changelog ==

= 1.1 =
Javascript file updated with a fix for htaccess

= 1.0 =
-released

== Upgrade Notice ==

== Screenshots ==
1. Here is normal mode
2. Here is Thumbs mode
3. Here is Like only mode
