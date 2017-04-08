<?php
/**
 * Plugin Name: Custom JS
 * Plugin URI: https://www.seosthemes.com/custom-js/
 * Contributors: seosbg
 * Author: seosbg
 * Description: Custom JS is easy to use. Custom JS add custom JS in your theme.
 * Version: 1.0.0
 * License: GPL2
*/

	//Add Admin Setting
	add_action('admin_menu', 'cjs_menu');
	function cjs_menu() {
		add_menu_page('Custom JS', 'Custom JS', 'administrator', 'cjs-settings-group', 'cjs_settings_page', plugins_url('custom-js/images/icon.png')
    );

    add_action('admin_init', 'cjs_register_settings');
}

	// Register Setting
	function cjs_register_settings() {
		register_setting( 'cjs-settings-group', 'cjs_head' );
		register_setting( 'cjs-settings-group', 'cjs_footer' );
	}

	// Admin Enqueue Scripts
	
	function cjs_admin_styles() {
		wp_register_style( 'cjs_admin', plugin_dir_url(__FILE__) . 'css/admin.css' );
		wp_enqueue_style( 'cjs_admin');
	}
	
	add_action( 'admin_enqueue_scripts', 'cjs_admin_styles' );
		
	function cjs_settings_page() { ?>
		
		<div class="wrap">
			<h2><?php _e( 'Custom JS', 'cjs' ); ?></h2>
			<form name="cjs_form" action="options.php" method="post" >
				<?php settings_fields( 'cjs-settings-group' ); ?>
			<?php do_settings_sections( 'cjs-settings-group' ); ?>
			
				<div id="cjs_wrap">
			<div class="cjs">
				<a target="_blank" href="https://seosthemes.com/">
					<div class="btn s-red">
						 <?php _e('SEOS', 'cjs'); echo ' <img class="ss-logo" src="' . plugins_url( 'images/logo.png' , __FILE__ ) . '" alt="logo" />';  _e(' THEMES', 'cjs'); ?>
					</div>
				</a>
			</div>											
					<strong><?php _e('Add JS in Head', 'cjs'); ?></strong>
					<div>
						<textarea style="width: 100%; min-height: 500px;" name="cjs_head"><?php echo esc_attr(get_option( 'cjs_head' )); ?></textarea>
					</div>
					
					<strong><?php _e('Add JS in Footer', 'cjs'); ?></strong>					
					<div>
						<textarea style="width: 100%; min-height: 500px;" name="cjs_footer"><?php echo esc_attr(get_option( 'cjs_footer' )); ?></textarea>
					</div>
					
						<?php submit_button(); ?>			
				</div>
			</form>

			
		</div>
		<div class="clear"></div>
	<?php
	}
	
/************************* Add JS in Head *************************/	

	function cjs_add_head (){ ?>
			<?php echo get_option( 'cjs_head' ); ?>
	<?php }
	add_action('wp_head','cjs_add_head');
	
/************************* Add JS in Footer *************************/	

	function cjs_add_footer (){ ?>
			<?php echo get_option( 'cjs_footer' ); ?>
	<?php }
	add_action('wp_footer','cjs_add_footer');
	
/************************* Translation *************************/	
	
	function cjs_language_load() {
	  load_plugin_textdomain('cjs_language_load', FALSE, basename(dirname(__FILE__)) . '/languages');
	}
	add_action('init', 'cjs_language_load');