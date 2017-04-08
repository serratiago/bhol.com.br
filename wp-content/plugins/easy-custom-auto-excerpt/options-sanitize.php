<?php

function ecae_sanitize_options($opt) {
	/** 
	 * sanitize_text_field
	 */
	if(isset($opt['excerpt_method'])) {
		$opt['excerpt_method'] = sanitize_text_field($opt['excerpt_method']);
	}

	if(isset($opt['strip_shortcode'])) {
		$opt['strip_shortcode'] = sanitize_text_field($opt['strip_shortcode']);
	}
	
	if(isset($opt['strip_empty_tags'])) {
		$opt['strip_empty_tags'] = sanitize_text_field($opt['strip_empty_tags']);
	}
	
	if(isset($opt['disable_on_feed'])) {
		$opt['disable_on_feed'] = sanitize_text_field($opt['disable_on_feed']);
	}
	
	if(isset($opt['special_method'])) {
		$opt['special_method'] = sanitize_text_field($opt['special_method']);
	}
	
	if(isset($opt['justify'])) {
		$opt['justify'] = sanitize_text_field($opt['justify']);
	}
	
	if(isset($opt['extra_html_markup'])) {
		$opt['extra_html_markup'] = sanitize_text_field($opt['extra_html_markup']);
	}
	
	if(isset($opt['show_image'])) {
		$opt['show_image'] = sanitize_text_field($opt['show_image']);
	}
	
	if(isset($opt['image_position'])) {
		$opt['image_position'] = sanitize_text_field($opt['image_position']);
	}
	
	if(isset($opt['image_width_type'])) {
		$opt['image_width_type'] = sanitize_text_field($opt['image_width_type']);
	}
	
	if(isset($opt['image_thumbnail_size'])) {
		$opt['image_thumbnail_size'] = sanitize_text_field($opt['image_thumbnail_size']);
	}
	
	if(isset($opt['location_settings_type'])) {
		$opt['location_settings_type'] = sanitize_text_field($opt['location_settings_type']);
	}
	
	if(isset($opt['home'])) {
		$opt['home'] = sanitize_text_field($opt['home']);
	}
	
	if(isset($opt['front_page'])) { 
		$opt['front_page'] = sanitize_text_field($opt['front_page']);
	}

	if(isset($opt['archive'])) {
		$opt['archive'] = sanitize_text_field($opt['archive']);
	}

	if(isset($opt['search'])) {
		$opt['search'] = sanitize_text_field($opt['search']);
	}
	
	if(isset($opt['advanced_home'])) {
		$opt['advanced_home'] = sanitize_text_field($opt['advanced_home']);
	}
	
	if(isset($opt['advanced_frontpage'])) {
		$opt['advanced_frontpage'] = sanitize_text_field($opt['advanced_frontpage']);
	}
	
	if(isset($opt['advanced_archive'])) {
		$opt['advanced_archive'] = sanitize_text_field($opt['advanced_archive']);
	}
	
	if(isset($opt['advanced_search'])) {
		$opt['advanced_search'] = sanitize_text_field($opt['advanced_search']);
	}
	
	if(isset($opt['advanced_page_main'])) {
		$opt['advanced_page_main'] = sanitize_text_field($opt['advanced_page_main']);
	}
	
	if(isset($opt['button_display_option'])) {
		$opt['button_display_option'] = sanitize_text_field($opt['button_display_option']);
	}
	
	if(isset($opt['read_more'])) {
		$opt['read_more'] = sanitize_text_field($opt['read_more']);
	}
	
	if(isset($opt['read_more_text_before'])) {
		$opt['read_more_text_before'] = sanitize_text_field($opt['read_more_text_before']);
	}
	
	if(isset($opt['readmore_inline'])) {
		$opt['readmore_inline'] = sanitize_text_field($opt['readmore_inline']);
	}
	
	if(isset($opt['read_more_align'])) {
		$opt['read_more_align'] = sanitize_text_field($opt['read_more_align']);
	}
	
	if(isset($opt['button_font'])) {
		$opt['button_font'] = sanitize_text_field($opt['button_font']);
	}
	
	if(isset($opt['button_skin'])) {
		$opt['button_skin'] = sanitize_text_field($opt['button_skin']);
	}
	
	if(isset($opt['license_key'])) {
		$opt['license_key'] = sanitize_text_field($opt['license_key']);
	}

	/**
	 * absint
	 */
	if(isset($opt['width'])) {
		$opt['width'] = absint($opt['width']);
	}

	if(isset($opt['image_width'])) {
		$opt['image_width'] = absint($opt['image_width']);
	}

	if(isset($opt['image_padding_top'])) {
		$opt['image_padding_top'] = absint($opt['image_padding_top']);
	}

	if(isset($opt['image_padding_right'])) {
		$opt['image_padding_right'] = absint($opt['image_padding_right']);
	}

	if(isset($opt['image_padding_bottom'])) {
		$opt['image_padding_bottom'] = absint($opt['image_padding_bottom']);
	}

	if(isset($opt['image_padding_left'])) {
		$opt['image_padding_left'] = absint($opt['image_padding_left']);
	}

	if(isset($opt['advanced_home_width'])) {
		$opt['advanced_home_width'] = absint($opt['advanced_home_width']);
	}

	if(isset($opt['advanced_frontpage_width'])) {
		$opt['advanced_frontpage_width'] = absint($opt['advanced_frontpage_width']);
	}

	if(isset($opt['advanced_archive_width'])) {
		$opt['advanced_archive_width'] = absint($opt['advanced_archive_width']);
	}

	if(isset($opt['advanced_search_width'])) {
		$opt['advanced_search_width'] = absint($opt['advanced_search_width']);
	}

	if(isset($opt['advanced_page_main_width'])) {
		$opt['advanced_page_main_width'] = absint($opt['advanced_page_main_width']);
	}

	if(isset($opt['button_font_size'])) {
		$opt['button_font_size'] = absint($opt['button_font_size']);
	}

	return $opt;
}