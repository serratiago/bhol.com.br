<?php
/*
Plugin Name: Easy Custom Auto Excerpt
Plugin URI: https://www.tonjoostudio.com/addons/easy-custom-auto-excerpt/
Description: Auto Excerpt for your post on home, front_page, search and archive.
Version: 2.4.1
Author: tonjoo
Author URI: https://www.tonjoostudio.com/
Contributor: Todi Adiyatmo Wijoyo, Haris Ainur Rozak
*/

$plugin = plugin_basename(__FILE__);

define("TONJOO_ECAE", 'easy-custom-auto-excerpt');
define("ECAE_VERSION", '2.4');
define("ECAE_DIR_NAME", str_replace("/easy-custom-auto-excerpt.php", "", plugin_basename(__FILE__)));
define("ECAE_HTTP_PROTO", is_ssl() ? "https://" : "http://");

require_once(plugin_dir_path(__FILE__) . 'tonjoo-library.php');
require_once(plugin_dir_path(__FILE__) . 'default.php');
require_once(plugin_dir_path(__FILE__) . 'options-sanitize.php');
require_once(plugin_dir_path(__FILE__) . 'options-page.php');
require_once(plugin_dir_path(__FILE__) . 'regex.php');
require_once(plugin_dir_path(__FILE__) . 'advExcLoc.php');
require_once(plugin_dir_path(__FILE__) . 'ajax.php');
require_once(plugin_dir_path(__FILE__) . 'the-post.php');

// Plugin init, commonly for localization purpose
add_action('plugins_loaded', 'tonjoo_ecae_plugin_init');
function tonjoo_ecae_plugin_init() {
    // modify post object here
    global $is_main_query_ecae;

    $is_main_query_ecae=true;

    // Localization
    load_plugin_textdomain(TONJOO_ECAE, false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// Remove some not needed wordpress filters
add_action('wp_head', 'tonjoo_ecae_remove_all_filters');
function tonjoo_ecae_remove_all_filters() {
    remove_all_filters('get_the_excerpt');
    remove_all_filters('the_excerpt');

    /**
     * Filter get_the_excerpt to return the_content
     */
    add_filter('get_the_excerpt', 'tonjoo_ecae_get_the_excerpt',999);
    add_filter('the_excerpt', 'tonjoo_ecae_get_the_excerpt',999);
}

// Remove default <-- more --> link
add_filter( 'the_content_more_link', 'modify_read_more_link' );
function modify_read_more_link() {
    return '';
}

// Direcly call the excerpt
function tonjoo_ecae_get_the_excerpt($output) {
    global $post;

    return apply_filters( 'the_content', $post->post_content );
}

// Donation
add_filter("plugin_action_links_$plugin", 'tonjoo_ecae_donate');
function tonjoo_ecae_donate($links) {
    $settings_link = '<a href="' . admin_url("admin.php?page=tonjoo_excerpt") . '" >Settings</a>';
    array_push($links, $settings_link);

    if(! function_exists('is_ecae_premium_exist')) {
        $premium_link = '<a href="http://wpexcerptplugin.com/" target="_blank" >Upgrade to premium</a>';
        array_push($links, $premium_link);
    }

    return $links;
}


/**
 * ecae button shortcode
 */
add_shortcode('ecae_button', 'ecae_button_shortcode');
function ecae_button_shortcode() {
    $options = get_option('tonjoo_ecae_options');
    $options = tonjoo_ecae_load_default($options);

    $button_skin = explode('-PREMIUM', $options['button_skin']);
    $trim_readmore_before = trim($options['read_more_text_before']);

    $read_more_text_before = empty($trim_readmore_before) ? $options['read_more_text_before'] : $options['read_more_text_before']."&nbsp;&nbsp;";

    // localization with WPML or not
    if(function_exists('icl_object_id') && function_exists('icl_t')) {
        $local_button_text = icl_t(TONJOO_ECAE, 'Readmore text', $options['read_more']);
        $local_before_button_text = icl_t(TONJOO_ECAE, 'Before readmore link', $read_more_text_before);
    }
    else {
        $local_button_text = __($options['read_more'], TONJOO_ECAE);
        $local_before_button_text = __($read_more_text_before, TONJOO_ECAE);
    }

    $link = get_permalink();
    $readmore_link = " <a class='ecae-link' href='$link'><span>$local_button_text</span></a>";
    $readmore = "<p class='ecae-button {$button_skin[0]}' style='text-align:{$options['read_more_align']};' >$local_before_button_text $readmore_link</p>";

    if(is_single()) {
        return "";
    }
    else { 
        return $readmore;
    }
}


/**
 * admin_enqueue_scripts - equeue on plugin page only
 */
if (strpos($_SERVER['REQUEST_URI'], "tonjoo_excerpt") !== false) {
    add_action('admin_enqueue_scripts', 'ecae_admin_enqueue_scripts');
}

function ecae_admin_enqueue_scripts() {
    if(isset($_GET['page']) && $_GET['page'] == "tonjoo_excerpt") {
        //print script
        echo "<script type='text/javascript'>";
        echo "var ecae_dir_name = '".plugins_url( ECAE_DIR_NAME , dirname(__FILE__) )."';";
        echo "var ecae_button_dir_name = '".plugins_url( ECAE_DIR_NAME.'/buttons/' , dirname(__FILE__) )."';";

        if(function_exists('is_ecae_premium_exist')) {
            echo "var ecae_premium_dir_name = '".plugins_url( ECAE_PREMIUM_DIR_NAME , dirname(__FILE__) )."';";
            echo "var ecae_button_premium_dir_name = '".plugins_url( ECAE_PREMIUM_DIR_NAME.'/buttons/' , dirname(__FILE__) )."';";
            echo "var ecae_premium_enable = true;";
        }
        else {
            echo "var ecae_button_premium_dir_name = '".plugins_url( ECAE_DIR_NAME.'/assets/premium-promo/' , dirname(__FILE__) )."';";
            echo "var ecae_premium_enable = false;";
        }

        echo "</script>";

        // javascript
        wp_enqueue_script('ace-js',plugin_dir_url( __FILE__ )."assets/ace-min-noconflict-css-monokai/ace.js",array(),ECAE_VERSION);
        wp_enqueue_script('select2-js',plugin_dir_url( __FILE__ )."assets/select2/select2.js",array(),ECAE_VERSION);
        wp_enqueue_script('cloneya-js',plugin_dir_url( __FILE__ )."assets/jquery-cloneya.min.js",array(),ECAE_VERSION);

        // css
        wp_enqueue_style('select2-css',plugin_dir_url( __FILE__ )."assets/select2/select2.css",array(),ECAE_VERSION);

        // admin script and stylel
        wp_enqueue_script('ecae-admin-js',plugin_dir_url( __FILE__ )."assets/admin-script.js",array(),ECAE_VERSION);
        wp_enqueue_style('ecae-admin-css',plugin_dir_url( __FILE__ )."assets/admin-style.css",array(),ECAE_VERSION);
    }
}


/**
 * wp_enqueue_scripts
 */
add_action('wp_enqueue_scripts', 'ecae_wp_enqueue_scripts', 100);
function ecae_wp_enqueue_scripts() {
    $options = get_option('tonjoo_ecae_options');
    $options = tonjoo_ecae_load_default($options);
    $inline_css = '';

    // frontend style
    wp_enqueue_style('ecae-frontend',plugin_dir_url( __FILE__ )."assets/style-frontend.css",array(),ECAE_VERSION);

    /**
     * Font
     */
    if($options['button_font'] != '') {
        switch ($options['button_font']) {
            case "Open Sans":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext);"; //Open Sans
                break;
            case "Lobster":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Lobster);"; //Lobster
                break;
            case "Lobster Two":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Lobster+Two:400,400italic,700,700italic);"; //Lobster Two
                break;
            case "Ubuntu":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Ubuntu:300,400,500,700,300italic,400italic,500italic,700italic);"; //Ubuntu
                break;
            case "Ubuntu Mono":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Ubuntu+Mono:400,700,400italic,700italic);"; //Ubuntu Mono
                break;
            case "Titillium Web":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Titillium+Web:400,300,700,300italic,400italic,700italic);"; //Titillium Web
                break;
            case "Grand Hotel":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Grand+Hotel);"; //Grand Hotel
                break;
            case "Pacifico":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Pacifico);"; //Pacifico
                break;
            case "Crafty Girls":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Crafty+Girls);"; //Crafty Girls
                break;
            case "Bevan":
                $inline_css.= "@import url(".ECAE_HTTP_PROTO."fonts.googleapis.com/css?family=Bevan);"; //Bevan
                break;
            default:
                // do nothing
        }

        $inline_css.= "p.ecae-button { font-family: '".$options['button_font']."', Helvetica, Arial, sans-serif; }";
    }

    /**
     * Others
     */
    $trimmed_custom_css = str_replace(' ', '', $options["custom_css"]);

    if($trimmed_custom_css != '') {
        $inline_css.= $options["custom_css"];
    }

    if(function_exists('is_ecae_premium_exist') && isset($options["button_font_size"])) {
        $inline_css.= '.ecae-button { font-size: '.$options["button_font_size"].'px !important; }';
    }

    // if($is_readmore && $options['readmore_inline'] == 'yes')
    if($options['readmore_inline'] == 'yes') {
        $inline_css.= ".ecae p:nth-last-of-type(2) {
            display: inline !important;
            padding-right: 10px;
        }

        .ecae-button {
            display: inline-block !important;
        }";
    }

    // Add inline css
    wp_add_inline_style( 'ecae-frontend', $inline_css );

    /**
     * Button skin
     */
    $array_buttonskins = ecae_get_array_buttonskins();

    if(! isset($options['button_skin']) || ! in_array($options['button_skin'], $array_buttonskins)) {
        $options['button_skin'] = 'ecae-buttonskin-none';
    }

    /* filter if premium */
    $exp = explode('-PREMIUM', $options['button_skin']);
    if(count($exp) > 1 AND $exp[1] == 'true') {
        wp_enqueue_style($exp[0],plugins_url(ECAE_PREMIUM_DIR_NAME."/buttons/{$exp[0]}.css"),array(),ECAE_VERSION);
    }
    else {
        wp_enqueue_style($exp[0],plugins_url(ECAE_DIR_NAME."/buttons/{$exp[0]}.css"),array(),ECAE_VERSION);
    }
}

function ecae_get_array_buttonskins() {
    $skins = scandir(dirname(__FILE__)."/buttons");
    $button_skin = array();

    foreach ($skins as $key => $value) {
        $extension = pathinfo($value, PATHINFO_EXTENSION);
        $filename = pathinfo($value, PATHINFO_FILENAME);
        $extension = strtolower($extension);
        $the_value = strtolower($filename);

        if($extension == 'css') {
            array_push($button_skin,$the_value);
        }
    }

    if(function_exists('is_ecae_premium_exist')) {
        $skins = scandir(ABSPATH . 'wp-content/plugins/'.ECAE_PREMIUM_DIR_NAME.'/buttons');

        foreach ($skins as $key => $value) {
            $extension = pathinfo($value, PATHINFO_EXTENSION);
            $filename = pathinfo($value, PATHINFO_FILENAME);
            $extension = strtolower($extension);
            $the_value = strtolower($filename);

            if($extension=='css') {
                array_push($button_skin,$the_value.'-PREMIUMtrue');
            }
        }
    }

    return $button_skin;
}

/**
 * Display a notice that can be dismissed
 */
add_action('admin_notices', 'ecae_premium_notice');
function ecae_premium_notice() {
    global $current_user;

    $user_id = $current_user->ID;
    $ignore_notice = get_user_meta($user_id, 'ecae_premium_ignore_notice', true);
    $ignore_count_notice = get_user_meta($user_id, 'ecae_premium_ignore_count_notice', true);
    $max_count_notice = 15;

    // if usermeta(ignore_count_notice) is not exist
    if($ignore_count_notice == "") {
        add_user_meta($user_id, 'ecae_premium_ignore_count_notice', $max_count_notice, true);

        $ignore_count_notice = 0;
    }

    // display the notice or not
    if($ignore_notice == 'forever') {
        $is_ignore_notice = true;
    }
    else if($ignore_notice == 'later' && $ignore_count_notice < $max_count_notice) {
        $is_ignore_notice = true;

        update_user_meta($user_id, 'ecae_premium_ignore_count_notice', intval($ignore_count_notice) + 1);
    }
    else {
        $is_ignore_notice = false;
    }

    /* Check that the user hasn't already clicked to ignore the message & if premium not installed */
    if (! $is_ignore_notice  && ! function_exists("is_ecae_premium_exist")) {
        echo '<div class="updated"><p>';

        printf(__('Get 40+ read more button style, %1$s Get Easy Custom Auto Excerpt Premium ! %2$s Do not bug me again %3$s Not Now %4$s',TONJOO_ECAE),
            '<a href="http://wpexcerptplugin.com" target="_blank">',
            '</a><span style="float:right;"><a href="?ecae_premium_nag_ignore=forever" style="color:#a00;">',
            '</a> <a href="?ecae_premium_nag_ignore=later" class="button button-primary" style="margin:-5px -5px 0 5px;vertical-align:baseline;">',
            '</a></span>');

        echo "</p></div>";
    }
}

add_action('admin_init', 'ecae_premium_nag_ignore');
function ecae_premium_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;

    // If user clicks to ignore the notice, add that to their user meta
    if (isset($_GET['ecae_premium_nag_ignore']) && $_GET['ecae_premium_nag_ignore'] == 'forever') {
        update_user_meta($user_id, 'ecae_premium_ignore_notice', 'forever');

        /**
         * Redirect
         */
        $location = admin_url("admin.php?page=tonjoo_excerpt") . '&settings-updated=true';
        echo "<meta http-equiv='refresh' content='0;url=$location' />";
        echo "<h2>Loading...</h2>";
        exit();
    }
    else if (isset($_GET['ecae_premium_nag_ignore']) && $_GET['ecae_premium_nag_ignore'] == 'later') {
        update_user_meta($user_id, 'ecae_premium_ignore_notice', 'later');
        update_user_meta($user_id, 'ecae_premium_ignore_count_notice', 0);

        $total_ignore_notice = get_user_meta($user_id, 'ecae_premium_ignore_count_notice_total', true);

        if($total_ignore_notice == '') $total_ignore_notice = 0;

        update_user_meta($user_id, 'ecae_premium_ignore_count_notice_total', intval($total_ignore_notice) + 1);

        if(intval($total_ignore_notice) >= 5) {
            update_user_meta($user_id, 'ecae_premium_ignore_notice', 'forever');
        }

        /**
         * Redirect
         */
        $location = admin_url("admin.php?page=tonjoo_excerpt") . '&settings-updated=true';
        echo "<meta http-equiv='refresh' content='0;url=$location' />";
        echo "<h2>Loading...</h2>";
        exit();
    }
}

/**
 * Main Query Check
 */
add_action( 'loop_end', 'tonjoo_ecae_loop_end' );
function tonjoo_ecae_loop_end( $query ) {
    // modify post object here
    global $is_main_query_ecae;

    $is_main_query_ecae=false;

    if($query->is_main_query()){
        $is_main_query_ecae=true;
    }
}

/**
 * Do Filter after this
 * add_filter('the_content', 'do_shortcode', 11); // AFTER wpautop()
 * So we can preserve shortcode
 */
add_filter('the_content', 'tonjoo_ecae_execute', 10);
function tonjoo_ecae_execute($content, $width = 400) {
    global $content_pure;
    global $post;

    // if password protected
    if ( post_password_required( $post ) ) {
        return get_the_password_form( $post );
    }

    $options = get_option('tonjoo_ecae_options');
    $options = tonjoo_ecae_load_default($options);

    // if post type is FRS
    if('pjc_slideshow' == get_post_type()) {
        return $content;

        exit;
    }

    // if RSS FEED
    if(is_feed() && $options['disable_on_feed'] == 'yes') {
        return $content;

        exit;
    }

    if(isset($options['special_method']) && $options['special_method'] == 'yes') {
        global $is_main_query_ecae;

        if(!$is_main_query_ecae)
            return $content;
    }

    $content_pure = $content;

    $width   = $options['width'];
    $justify = $options['justify'];

    /**
     * no limit number if 1st-paragraph mode
     */
    if(strpos($options['excerpt_method'],'-paragraph')) {
        if(function_exists("is_ecae_premium_exist")) {
            $width = strlen(wp_kses($content,array())); //max integer in 32-bit system
        }
        else {
            $options['excerpt_method'] = 'paragraph';
        }
    }

    if($options['location_settings_type'] == 'basic') {
        if ($options['home'] == "yes" && is_home()) {
            return tonjoo_ecae_excerpt($content, $width, $justify);
        }

        if ($options['front_page'] == "yes" && is_front_page()) {
            return tonjoo_ecae_excerpt($content, $width, $justify);
        }

        if ($options['search'] == "yes" && is_search()) {
            return tonjoo_ecae_excerpt($content, $width, $justify);
        }

        if ($options['archive'] == "yes" && is_archive()) {
            return tonjoo_ecae_excerpt($content, $width, $justify);
        }

        /**
         * excerpt in pages
         */
        $excerpt_in_page = $options['excerpt_in_page'];
        $excerpt_in_page = trim($excerpt_in_page);

        if($excerpt_in_page!='') {
            $excerpt_in_page = explode('|',$excerpt_in_page);
        }
        else {
            $excerpt_in_page = array();
        }

        foreach ($excerpt_in_page as $key => $value) {
            if($value != '' && is_page($value)) {
                return tonjoo_ecae_excerpt($content, $width, $justify);
                break;
            }
        }
    }
    else {
        $advExcLoc = new advExcLoc($options,$content,$width,$justify);

        if(is_home()){
            $options['excerpt_method'] = $advExcLoc->get_excerpt_method('advanced_home');
            return $advExcLoc->do_excerpt('advanced_home','home_post_type','home_category');
        }

        if(is_front_page()){
            $options['excerpt_method'] = $advExcLoc->get_excerpt_method('advanced_frontpage');
            return $advExcLoc->do_excerpt('advanced_frontpage','frontpage_post_type','frontpage_category');
        }

        if(is_archive()){
            $options['excerpt_method'] = $advExcLoc->get_excerpt_method('advanced_archive');
            return $advExcLoc->do_excerpt('advanced_archive','archive_post_type','archive_category');
        }

        if(is_search()){
            $options['excerpt_method'] = $advExcLoc->get_excerpt_method('advanced_search');
            return $advExcLoc->do_excerpt('advanced_search','search_post_type','search_category');
        }

        // Page Excerpt
        if($options['advanced_page_main'] != 'disable') {
            $type = 'advanced_page_main';
            $excerpt_in_page = $options['excerpt_in_page_advanced'];
            $options['excerpt_method'] = $advExcLoc->get_excerpt_method($type);

            // excerpt size
            if(isset($options[$type . '_width']) && $options[$type . '_width'] > 0) {
                $width = $options[$type . '_width'];
            }

            if(is_array($excerpt_in_page)) {
                foreach ($excerpt_in_page as $key => $value) {
                    if($value != '' && is_page($value)) {
                        return tonjoo_ecae_excerpt($content, $width, $justify);
                        break;
                    }
                }
            }

            if($options['advanced_page_main'] == 'selection') {
                $advanced_page = $options['advanced_page'];
                $page_post_type = $options['page_post_type'];
                $page_category = $options['page_category'];

                if(count($advanced_page) > 0) {
                    foreach ($advanced_page as $key => $value) {
                        if($value != '' && is_page($value)) {
                            $return['data'] = $content;
                            $return['excerpt'] = false;

                            if(isset($page_post_type[$key]))
                                $return = $advExcLoc->excerpt_in_post_type($page_post_type[$key],$width);

                            if($return['excerpt'] == false) {
                                if(isset($page_category[$key]))
                                    $return = $advExcLoc->excerpt_in_category($page_category[$key],$width);

                                return $return['data'];
                            }
                            else return $return['data'];
                        }
                    }
                }
            }
            // end advanced_page_main
        }
        // end location_settings_type
    }

    /**
     * else
     */
    return $content;
}

function tonjoo_ecae_get_img_added_css($options) {
    /**
     * image position
     */
    switch ($options['image_position']) {
        case 'right':
            $img_added_css = "";
            break;

        case 'left':
            $img_added_css = "";
            break;

        case 'center':
            $img_added_css = "margin-left:auto !important; margin-right:auto !important;";
            break;

        case 'float-left':
            $img_added_css = "float:left;";
            break;

        case 'float-right':
            $img_added_css = "float:right;";
            break;

        default:
            $img_added_css = "text-align:left;";
            break;
    }

    if($options['image_width_type'] == 'manual') {
        $img_added_css.= "width:{$options['image_width']}px;";
    }

    $img_added_css.= "padding:{$options['image_padding_top']}px {$options['image_padding_right']}px {$options['image_padding_bottom']}px {$options['image_padding_left']}px;";

    return $img_added_css;
}

function tonjoo_ecae_excerpt($content, $width, $justify) {
    global $post;

    $options = get_option('tonjoo_ecae_options');
    $options = tonjoo_ecae_load_default($options);
    $postmeta = get_post_meta($post->ID, 'ecae_meta', true);
    $disable_excerpt = isset($postmeta['disable_excerpt']) && $postmeta['disable_excerpt'] == 'yes';

    if(function_exists('is_ecae_premium_exist') && $disable_excerpt) {
        return $content;
        exit;
    }

    $total_width = 0;
    $pos = strpos($content, '<!--more-->');
    $array_replace_list = array();

    // if read more
    if($pos) {
        // check shortcode optons
        if ($options['strip_shortcode'] == 'yes') {
            $content = strip_shortcodes($content);
        }

        $content = substr($content, 0, $pos);
    }    
    // If excerpt column is not empty
    else if ($post->post_excerpt != '') {
        $text = $post->post_excerpt;
        $content = "<p>" . implode( "</p>\n\n<p>", preg_split( '/\n(?:\s*\n)+/', $text ) ) . "</p>";

        // if featured image
        if ($options['show_image'] == 'featured-image') {
            // enable thumbnail for ecae
            add_filter('ecae-thumbnail-mode', 'ecae_enable_thumbnail');

            //check featured image;
            $featured_image = has_post_thumbnail(get_the_ID());
            $image = false;

            if($featured_image) {
                $image = get_the_post_thumbnail(get_the_ID(), $options['image_thumbnail_size']);
            }

            // disable thumbnail for non ecae
            remove_filter('ecae-thumbnail-mode', 'ecae_enable_thumbnail');

            $img_added_css = tonjoo_ecae_get_img_added_css($options);

            // only put image if there is image :p
            if($image) {
                if($options['image_position'] == 'left') {
                    $content = "<div class='ecae-image ecae-table-left'><div class='ecae-table-cell' style='$img_added_css'>" . $image . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                }
                else if($options['image_position'] == 'right') {
                    $content = "<div class='ecae-image ecae-table-right'><div class='ecae-table-cell' style='$img_added_css'>" . $image . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                }
                else {
                    $content = "<div class='ecae-image' style='$img_added_css'>" . $image . "</div>" . $content;
                }
            }
        }
    }
    else if ($width == 0) {
        $content = '';
    }
    else {
        // Do caption shortcode
        $content = ecae_convert_caption($content,$options);

        $caption_image_replace   = new eace_content_regex("|$", "/<div[^>]*class=\"[^\"]*wp-caption[^\"]*\".*>(.*)<img[^>]+\>(.*)<\/div>/",$options,true);
        $figure_replace          = new eace_content_regex("|:", "/<figure.*?\>([^`]*?)<\/figure>/",$options,true);
        $hyperlink_image_replace = new eace_content_regex("|#", "/<a[^>]*>(\n|\s)*(<img[^>]+>)(\n|\s)*<\/a>/",$options,true);
        $image_replace           = new eace_content_regex("|(", "/<img[^>]+\>/",$options,true );

        //biggest -> lowest the change code

        $html_replace = array();
        $extra_markup = $options['extra_html_markup'];
        $extra_markup = trim($extra_markup);

        //prevent white space explode
        if($extra_markup!='') {
            $extra_markup = explode('|',$extra_markup);
        }
        else {
            $extra_markup = array();
        }

        $extra_markup_tag=array('*=','(=',')=','_=','<=','>=','/=','\=',']=','[=','{=','}=','|=');

        //default order
        $array_replace_list['pre']='=@'; // syntax highlighter like crayon
        $array_replace_list['video']='=}';
        $array_replace_list['table']='={';
        $array_replace_list['p']='=!';
        $array_replace_list['b']='=&';
        $array_replace_list['a']='=*';
        $array_replace_list['i']='=)';
        $array_replace_list['h1']='=-';
        $array_replace_list['h2']='`=';
        $array_replace_list['h3']='!=';
        $array_replace_list['h4']='#=';
        $array_replace_list['h5']='$=';
        $array_replace_list['h6']='%=';
        $array_replace_list['ul']='=#';
        $array_replace_list['ol']='=$';
        $array_replace_list['strong']='=(';
        $array_replace_list['blockquote']='=^';

        foreach ($extra_markup as $markup) {
            $counter = 0;

            if(!isset($array_replace_list[$markup]))
                $array_replace_list[$markup]=$extra_markup_tag[$counter];

            $counter++;
        }

        //push every markup into processor
        foreach ($array_replace_list as $key=>$value) {
            //use image processing algorithm for table and video
            if($key=='video'||$key=='table')
                $push   = new eace_content_regex("{$value}", "/<{$key}.*?\>([^`]*?)<\/{$key}>/",$options,true);
            else
                $push   = new eace_content_regex("{$value}", "/<{$key}.*?\>([^`]*?)<\/{$key}>/",$options);

            array_push($html_replace, $push);
        }

        $pattern = get_shortcode_regex();

        if(!strpos('hana-flv-player', $pattern)) {
            $pattern = str_replace('embed','caption|hana-flv-player',$pattern);
        }

        $shortcode_replace = new eace_content_regex("+*", '/'.$pattern.'/s',$options);

        //trim image
        $option_image = $options['show_image'];

        if ($option_image == 'yes' || $option_image == 'first-image') {
            $number = false;
            //limit the image excerpt
            if ($option_image == 'first-image')
                $number = 1;

            $caption_image_replace->replace($content, $width, $number);
            $figure_replace->replace($content, $width, $number);
            $hyperlink_image_replace->replace($content, $width, $number);
            $image_replace->replace($content, $width, $number);
        }
        else {
            //remove image , this is also done for featured-image option
            $caption_image_replace->remove($content);
            $figure_replace->remove($content);
            $hyperlink_image_replace->remove($content);
            $image_replace->remove($content);
        }

        // check shortcode optons
        if ($options['strip_shortcode'] == 'yes') {
            $content = strip_shortcodes($content);
        }

        // Replace remaining tag
        foreach ($html_replace as $replace) {
             $replace->replace($content, $width,false,$total_width);
        }

        $shortcode_replace->replace($content, $width,false,$total_width);

        //use wp kses to fix broken element problem
        $content = wp_kses($content, array());

        if(strpos($content,'<!--STOP THE EXCERPT HERE-->') === false) {
            //give the stop mark so the plugin can stop
            $content=$content.'<!--STOP THE EXCERPT HERE-->';
        }

        //strip the text
        $content = substr($content, 0, strpos($content,'<!--STOP THE EXCERPT HERE-->'));

        //do the restore 3 times, avoid nesting
        $shortcode_replace->restore($content);

        foreach ($html_replace as $restore) $restore->restore($content, $width);
        foreach ($html_replace as $restore) $restore->restore($content, $width);
        foreach ($html_replace as $restore) $restore->restore($content, $width);

        $shortcode_replace->restore($content);
  
        $img_added_css = tonjoo_ecae_get_img_added_css($options);

        if ($option_image == 'yes') {
            $caption_image_replace->restore($content,false,true);
            $figure_replace->restore($content,false,true);
            $hyperlink_image_replace->restore($content,false,true);
            $image_replace->restore($content,false,true);
        }
        else if ($option_image == 'first-image') {
            //catch all of hyperlink and image on the content => '|#'  and '|(' and '|$'
            preg_match_all('/\|\([0-9]*\|\(|\|\#[0-9]*\|\#|\|\$[0-9]*\|\$|\|\:[0-9]*\|\:/', $content, $result, PREG_PATTERN_ORDER);

            if (isset($result[0])) {
                $remaining = array_slice($result[0], 0, 1);

                if(isset($remaining[0])) {
                    //delete remaining image
                    $content = preg_replace('/\|\:[0-9]*\|\:/', '', $content);
                    $content = preg_replace('/\|\([0-9]*\|\C/', '', $content);
                    $content = preg_replace('/\|\#[0-9]*\|\#/', '', $content);
                    $content = preg_replace('/\|\$[0-9]*\|\$/', '', $content);


                    if($options['image_position'] == 'left') {
                        $content = "<div class='ecae-image ecae-table-left'><div class='ecae-table-cell' style='$img_added_css'>" . $remaining[0] . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                    }
                    else if($options['image_position'] == 'right') {
                        $content = "<div class='ecae-image ecae-table-right'><div class='ecae-table-cell' style='$img_added_css'>" . $remaining[0] . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                    }
                    else {
                        $content = "<div class='ecae-image' style='$img_added_css'>" . $remaining[0] . "</div>" . $content;
                    }

                    $caption_image_replace->restore($content,1,true);
                    $figure_replace->restore($content, 1,true);
                    $hyperlink_image_replace->restore($content, 1,true);
                    $image_replace->restore($content, 1,true);
                }
            }
        }
        else if ($option_image == 'featured-image') {
            // enable thumbnail for ecae
            add_filter('ecae-thumbnail-mode', 'ecae_enable_thumbnail');

            //check featured image;
            $featured_image = has_post_thumbnail(get_the_ID());
            $image = false;

            if($featured_image) {
                $image = get_the_post_thumbnail(get_the_ID(), $options['image_thumbnail_size']);
            }

            // disable thumbnail for non ecae
            remove_filter('ecae-thumbnail-mode', 'ecae_enable_thumbnail');

            // only put image if there is image :p
            if($image) {
                if($options['image_position'] == 'left') {
                    $content = "<div class='ecae-image ecae-table-left'><div class='ecae-table-cell' style='$img_added_css'>" . $image . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                }
                else if($options['image_position'] == 'right') {
                    $content = "<div class='ecae-image ecae-table-right'><div class='ecae-table-cell' style='$img_added_css'>" . $image . "</div>" . "<div class='ecae-table-cell'>" . $content . '</div>' ;
                }
                else {
                    $content = "<div class='ecae-image' style='$img_added_css'>" . $image . "</div>" . $content;
                }
            }
        }

        // remove empty html tags
        if($options["strip_empty_tags"] == 'yes') {
            $content = strip_empty_tags($content);
        }

        //delete remaining image
        $content = preg_replace('/\|\([0-9]*\|\C/', '', $content);
        $content = preg_replace('/\|\#[0-9]*\|\#/', '', $content);

        //delete remaining
        $extra_markup_tag=array('*='.'(=',')=','_=','<=','>=','/=','\=',']=','[=','{=','}=','|=');

        foreach ($extra_markup_tag as $value) {
            $char = str_split($value);

            $content = preg_replace("/"."\\"."{$char[0]}"."\\"."{$char[1]}"."[0-9]*"."\\"."{$char[0]}"."\\"."{$char[1]}"."/", '', $content);
        }

        foreach($array_replace_list as $key=>$value) {
            $char = str_split($value);

            $content = preg_replace("/"."\\"."{$char[0]}"."\\"."{$char[1]}"."[0-9]*"."\\"."{$char[0]}"."\\"."{$char[1]}"."/", '', $content);
        }
    }


    /**
     * readmore text
     */
    $link = get_permalink();
    $readmore = "";
    $is_readmore = false;

    //remove last div is image position left / right
    if($options['image_position'] == 'left' || $options['image_position'] == 'right' && strpos($content, 'ecae-table-cell')) {
        if(strpos($content, 'ecae-table-cell')) $content = substr($content, 0, -6);
    }

    if (trim($options['read_more']) != '-') {
        //failsafe
        $options['read_more_text_before'] = isset($options['read_more_text_before'] )? $options['read_more_text_before']  : '...';

        $button_skin = explode('-PREMIUM', $options['button_skin']);
        $trim_readmore_before = trim($options['read_more_text_before']);

        $read_more_text_before = empty($trim_readmore_before) ? $options['read_more_text_before'] : $options['read_more_text_before']."&nbsp;&nbsp;";

        // localization with WPML or not
        if(function_exists('icl_t')) {
            $local_button_text = icl_t(TONJOO_ECAE, 'Readmore text', $options['read_more']);
            $local_before_button_text = icl_t(TONJOO_ECAE, 'Before readmore link', $read_more_text_before);
        }
        else {
            $local_button_text = __($options['read_more'], TONJOO_ECAE);
            $local_before_button_text = __($read_more_text_before, TONJOO_ECAE);
        }

        $readmore_link = " <a class='ecae-link' href='$link'><span>$local_button_text</span></a>";
        $readmore = "<p class='ecae-button {$button_skin[0]}' style='text-align:{$options['read_more_align']};' >$local_before_button_text $readmore_link</p>";

        // button_display_option
        if(! strpos($options['excerpt_method'],'-paragraph')) {
            if(strpos($content, '<!-- READ MORE TEXT -->')) $is_readmore = true;

            if($options['button_display_option'] == 'always_show') {
                $content = str_replace('<!-- READ MORE TEXT -->', '', $content);
                $content = $content . $readmore;
            }
            else if($options['button_display_option'] == 'always_hide') {
                $content = str_replace('<!-- READ MORE TEXT -->', '', $content);

                $is_readmore = false;
            }
            else {
                $content = str_replace('<!-- READ MORE TEXT -->', $readmore, $content);
            }
        }
    }

    /**
     * filter if 1st-paragraph mode
     */
    if(strpos($options['excerpt_method'],'-paragraph')) {
        $num_paragraph = substr($options['excerpt_method'], 0, 1);
        $content = get_per_paragraph(intval($num_paragraph), $content);

        global $content_pure;

        $len_content = strlen(wp_kses($content,array())) + 1;  // 1 is a difference between them
        $len_content_pure = strlen(wp_kses($content_pure,array()));

        // button_display_option
        if($options['button_display_option'] == 'always_show') {
            $content = $content . $readmore;
            $is_readmore = true;
        }
        else if($options['button_display_option'] == 'always_hide') {
            $content = $content;
        }
        else {
            if($len_content < $len_content_pure) {
                $content = $content . $readmore;
                $is_readmore = true;
            }
        }
    }

    // wrap with a container
    $justify = $justify != 'no' ? $justify : 'inherit';
    $content = "<div class='ecae' style='text-align:$justify'>" . $content . "</div>";

    //add last div is image position left / right
    if($options['image_position'] == 'left' || $options['image_position'] == 'right') {
        if(strpos($content, 'ecae-table-cell')) $content .= '</div>';
    }

    // remove empty html tags
    if($options["strip_empty_tags"] == 'yes') {
        $content = strip_empty_tags($content);
    }

    return "<!-- Begin :: Generated by Easy Custom Auto Excerpt -->$content<!-- End :: Generated by Easy Custom Auto Excerpt -->";
}

function ecae_enable_thumbnail($is_enable) {
    return true;
}