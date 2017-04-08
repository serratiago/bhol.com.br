<?php

Class ECAE_The_Post {
	function __construct() {
		$options = get_option('tonjoo_ecae_options');
		$this->opt = tonjoo_ecae_load_default($options);		

		add_action('the_post', array($this, 'the_post'));
		add_filter('get_post_metadata', array($this, 'get_post_metadata'), 10, 4);
	}

	function the_post($post_object) {
		add_filter('ecae-post', array($this, 'ecae_post'));
	}

	function default_args() {
		return array(
			'default-content' => false,
			'ecae-content' => false
		);
	}

	function ecae_post($args) {
		global $post;
        
        $this->args = $this->default_args();
		$disabled_post_types = array('pjc_slideshow');

		// On disabled post types
		if(in_array(get_post_type(), $disabled_post_types)) {
			$this->args['default-content'] = true;
		}

		// RSS FEED
	    if(is_feed() && $this->opt['disable_on_feed'] == 'yes') {
	        $this->args['default-content'] = true;
	    }

	    // Special method
	    if(isset($this->opt['special_method']) && $this->opt['special_method'] == 'yes') {
	        global $is_main_query_ecae;

	        if(! $is_main_query_ecae) $this->args['default-content'] = true;
	    }

	    // setting location
	    if($this->opt['location_settings_type'] == 'basic') {
	    	$this->location_basic();
	    }
	    else {
	    	$this->location_advanced();
	    }
		
    	
    	return $this->args;
	}

	function location_basic() {
		if ($this->opt['home'] == "yes" && is_home()) {
            $this->args['ecae-content'] = true;
        }
        else if ($this->opt['front_page'] == "yes" && is_front_page()) {
            $this->args['ecae-content'] = true;
        }
        else if ($this->opt['search'] == "yes" && is_search()) {
            $this->args['ecae-content'] = true;
        }
        else if ($this->opt['archive'] == "yes" && is_archive()) {
            $this->args['ecae-content'] = true;
        }

        /**
         * excerpt in pages
         */
        $excerpt_in_page = $this->opt['excerpt_in_page'];
        $excerpt_in_page = trim($excerpt_in_page);

        if($excerpt_in_page != '') {
            $excerpt_in_page = explode('|', $excerpt_in_page);
        }
        else {
            $excerpt_in_page = array();
        }

        foreach ($excerpt_in_page as $key => $value) {
            if($value != '' && is_page($value)) {
                $this->args['ecae-content'] = true;
                break;
            }
        }
	}

	function location_advanced() {
		if(is_home()){
            $this->advExcLoc_do_excerpt('advanced_home','home_post_type','home_category');
		}
		else if(is_front_page()){
			$this->advExcLoc_do_excerpt('advanced_frontpage','frontpage_post_type','frontpage_category');
		}
		else if(is_archive()){
			$this->advExcLoc_do_excerpt('advanced_archive','archive_post_type','archive_category');
		}
		else if(is_search()){
			$this->advExcLoc_do_excerpt('advanced_search','search_post_type','search_category');
		}

		/**
         * excerpt in pages
         */
        if($this->opt['advanced_page_main'] != 'disable') {
            $type = 'advanced_page_main';
            $excerpt_in_page = $this->opt['excerpt_in_page_advanced'];

            if(is_array($excerpt_in_page)) {
                foreach ($excerpt_in_page as $key => $value) {
                    if($value == '' || !is_page($value)) continue;
                    
                    $this->args['ecae-content'] = true;
                    break;
                }
            }

            if($this->opt['advanced_page_main'] == 'selection') {
                $advanced_page = $this->opt['advanced_page'];
                $page_post_type = $this->opt['page_post_type'];
                $page_category = $this->opt['page_category'];

                if(count($advanced_page) > 0) {
                    foreach ($advanced_page as $key => $value) {
                        if($value == '' || !is_page($value)) continue; 

                        if(isset($page_post_type[$key])) {
                            $this->advExcLoc_excerpt_in_post_type($page_post_type[$key]);
                        }

                        if($this->args['ecae-content'] == false && isset($page_category[$key])) {
                        	$this->advExcLoc_excerpt_in_category($page_category[$key]);
                        }
                        else {
                        	$this->args['default-content'] = true;
                        }
                    }
                }
            } // end advanced_page_main
        } // end location_settings_type
	}

    function advExcLoc_do_excerpt($type,$post_type,$category) {
        switch ($this->opt[$type]) {
            case 'all':
                $this->args['ecae-content'] = true;
                break;

            case 'selection':
                $this->advExcLoc_excerpt_in_post_type($this->opt[$post_type]);

                if($this->args['ecae-content'] == false) {
                    $this->advExcLoc_excerpt_in_category($this->opt[$category]);
                }

            default:
                $this->args['default-content'] = true;
                break;
        }
    }

    function advExcLoc_excerpt_in_post_type($opt_post_type) {
        $current_post_type = get_post_type(get_the_ID());
        $excerpt_in_post_type = $opt_post_type;

        if(is_array($excerpt_in_post_type) && in_array($current_post_type, $excerpt_in_post_type)) {
            $this->args['ecae-content'] = true;
        }
    }

    function advExcLoc_excerpt_in_category($opt_category) {
        $taxonomies = get_the_taxonomies(get_the_ID());

        foreach ($taxonomies as $key => $value):
        
        $taxonomy = $key;
        $category = wp_get_post_terms(get_the_ID(),$taxonomy);

        foreach ($category as $n) {
            $current_category = $n->term_id;
            $excerpt_in_category = $opt_category;

            if(is_array($excerpt_in_category) && in_array($current_category, $excerpt_in_category)) {
                $this->args['ecae-content'] = true;

                break 2;
            }
        }
        
        endforeach;
    }

	function get_post_metadata($null, $object_id, $meta_key, $single) {
	    if($meta_key == '_thumbnail_id' && $this->opt['show_image'] == 'featured-image'):
        
        $is_enable = apply_filters('ecae-thumbnail-mode', false);
		$the_post = apply_filters('ecae-post', array());
		$is_ecae = isset($the_post['ecae-content']) ? $the_post['ecae-content'] : false;

		if($is_ecae && ! $is_enable) {
			return false;
		}	        
	    
        endif;
	}
}

$GLOBALS['ECAE_The_Post'] = new ECAE_The_Post();