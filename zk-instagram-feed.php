<?php
/*
Plugin Name: ZK Instagram feed
Version: 0.0.1
Description: Display instagram feed with shortcode (eg: [zk-instagram-feed username="username" number="9" size="320"] )
Author: Robin Ferrari
Author URL: https://www.zeckart.com
GitHub Plugin URI: https://github.com/zecka/zk-instagram-feed
*/

define( 'ZKIF_URL', substr(plugin_dir_url( __FILE__ ), 0, -1) );
define( 'ZKIF_PATH', substr(plugin_dir_path( __FILE__ ), 0, -1) );


require ZKIF_PATH.'/get-feed.php';
require ZKIF_PATH.'/display-feed.php';