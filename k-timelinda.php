<?php
/*
	Plugin Name: Timelinda
	Plugin URI: https://janio.sarmento.org/
	Description: Extremely Lightweigth Pure CSS Responsive Vertical Timeline
	Version: 1.0.1
	Author: Janio Sarmento
	Author URI: https://janio.sarmento.org
	Author2: Fabio Lobo
	Text Domain: k_timelinda
	Domain Path: /languages
	License: GPLv2
*/

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Options

if ( !class_exists('K_Timelinda_Options') ) {

	class K_Timelinda_Options {

		function __construct() {
			add_action('admin_menu', array( $this, 'k_timelinda_menu' ) );
			add_action('admin_init', array( $this, 'k_timelinda_settings' ));
			add_action( 'plugins_loaded', array( $this, 'k_timelinda_textdomain'));
			if ( isset($_GET['page']) == 'k_timelinda' ) add_action('admin_enqueue_scripts', array( $this, 'k_timelinda_scripts' ));

			register_deactivation_hook( __FILE__, array($this, 'k_timelinda_deactivation') );
		}

		function k_timelinda_textdomain() {
			load_plugin_textdomain( 'k_timelinda', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		}

		function k_timelinda_menu() {
			add_options_page(
				__( 'Timelinda', 'k_timelinda' ),
				__( 'Timelinda', 'k_timelinda' ),
				'manage_options',
				'k_timelinda',
				array(
					$this,
					'k_timelinda_page'
				)
			);
		}

		function k_timelinda_page() { ?>
			<div class="wrap">
				<div id="poststuff">
					<div id="post-body" class="timelinda metabox-holder columns-2">

						<div id="post-body-content">

							<form method="post" action="options.php">
								<h1><?php esc_html_e( 'Timelinda', 'k_timelinda' ); ?></h1>

								<div class="welcome-panel">
									<div class="welcome-panel-content">

										<h1><?php esc_html_e('How it works?', 'k_timelinda' ); ?></h1>

										<p><?php esc_html_e('Use [timeline][/timeline] with [timeline_event title="text" subtitile="text" align="right/left"]text[/timeline_event] within.', 'k_timelinda' ); ?></p>
										<p><?php esc_html_e('Examples:', 'k_timelinda' ); ?></p>
										<p><code>[timeline]</code></p>
										<p>
											<code>&nbsp;&nbsp;[timeline_event title="<?php esc_html_e('Your title', 'k_timelinda' ); ?>" subtitle="<?php esc_html_e('Your subtitile', 'k_timelinda' ); ?>"]</code><br>
											<code>&nbsp;&nbsp;&nbsp;&nbsp;<?php esc_html_e('Your text here!', 'k_timelinda' ); ?></code><br>
											<code>&nbsp;&nbsp;[/timeline_event]</code>
										</p>
										<p>
											<code>&nbsp;&nbsp;[timeline_event title="<?php esc_html_e('Another title', 'k_timelinda' ); ?>" subtitle="<?php esc_html_e('Another subtitile', 'k_timelinda' ); ?>"]<?php esc_html_e('Your second text here!', 'k_timelinda' ); ?>[/timeline_event]</code>
										</p>
										<p>
											<code>&nbsp;&nbsp;[timeline_event]<?php esc_html_e('Just a text now', 'k_timelinda' ); ?>[/timeline_event]</code>
										</p>
										<p><code>&nbsp;&nbsp;[timeline_event title="<?php esc_html_e('Just a title now', 'k_timelinda' ); ?>"][/timeline_event]</code></p>
										<p><code>&nbsp;&nbsp;[timeline_event align="left"]<?php esc_html_e('Ok, I want this to be left aligned.', 'k_timelinda' ); ?>[/timeline_event]</code></p>
										<p><code>[/timeline]</code></p>
										<p><?php esc_html_e('As you can see above, the "align" property is optional – Also "title" and "subtitle" if you only want to use text.', 'k_timelinda' ); ?></p>

										<br>

									</div>
								</div>

								<h1><?php esc_html_e( 'Settings', 'k_timelinda' ); ?></h1>

								<?php settings_fields('k_timelinda_fields_section');

									do_settings_sections('k_timelinda-fields');

								submit_button(); ?>
							</form>

						</div>

						<?php require_once( plugin_dir_path( __FILE__ ) . 'admin/sidebar.php' ); ?>

					</div>
				</div>
			</div>
		<?php }

		function k_timelinda_settings() {
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-text-size');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-line-height');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-title-size');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-subtitle-size');

			register_setting('k_timelinda_fields_section', 'k_timelinda-field-timelinda-color');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-border-color');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-title-color');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-subtitle-color');
			register_setting('k_timelinda_fields_section', 'k_timelinda-field-text-color');
			
			add_settings_section('k_timelinda_fields_section', '', null, 'k_timelinda-fields');

			add_settings_field('k_timelinda-field-text-size', __( 'Text size', 'k_timelinda' ), array($this, 'k_timelinda_field_text_size'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-line-height', __( 'Line height', 'k_timelinda' ), array($this, 'k_timelinda_field_line_height'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-title-size', __( 'Title size', 'k_timelinda' ), array($this, 'k_timelinda_field_title_size'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-subtitle-size', __( 'Subtitle size', 'k_timelinda' ), array($this, 'k_timelinda_field_subtitle_size'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			
			add_settings_field('k_timelinda-field-timelinda-color', __( 'Timelinda color', 'k_timelinda' ), array($this, 'k_timelinda_field_timelinda_color'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-border-color', __( 'Border color', 'k_timelinda' ), array($this, 'k_timelinda_field_border_color'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-title-color', __( 'Title color', 'k_timelinda' ), array($this, 'k_timelinda_field_title_color'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-subtitle-color', __( 'Subtitle color', 'k_timelinda' ), array($this, 'k_timelinda_field_subtitle_color'), 'k_timelinda-fields', 'k_timelinda_fields_section');
			add_settings_field('k_timelinda-field-text-color', __( 'Text color', 'k_timelinda' ), array($this, 'k_timelinda_field_text_color'), 'k_timelinda-fields', 'k_timelinda_fields_section');
		}
		
		function k_timelinda_deactivation() {
			delete_option('k_timelinda-field-text-size');
			delete_option('k_timelinda-field-line-height');
			delete_option('k_timelinda-field-title-size');
			delete_option('k_timelinda-field-subtitle-size');

			delete_option('k_timelinda-field-timelinda-color');
			delete_option('k_timelinda-field-border-color');
			delete_option('k_timelinda-field-title-color');
			delete_option('k_timelinda-field-subtitle-color');
			delete_option('k_timelinda-field-text-color');
		}

		function k_timelinda_field_text_size() { ?>
			<input class="regular-text" id="k_timelinda-field-text-size" type="text" name="k_timelinda-field-text-size" value="<?php echo get_option('k_timelinda-field-text-size', '14px'); ?>">

			<p class="description"><?php esc_html_e( 'Default: 14px', 'k_timelinda' ); ?></p>
		<?php }

		function k_timelinda_field_line_height() { ?>
			<input class="regular-text" id="k_timelinda-field-line-height" type="text" name="k_timelinda-field-line-height" value="<?php echo get_option('k_timelinda-field-line-height', '1rem'); ?>">

			<p class="description"><?php esc_html_e( 'Default: 1rem', 'k_timelinda' ); ?></p>
		<?php }

		function k_timelinda_field_title_size() { ?>
			<input class="regular-text" id="k_timelinda-field-title-size" type="text" name="k_timelinda-field-title-size" value="<?php echo get_option('k_timelinda-field-title-size', '1.5rem'); ?>">

			<p class="description"><?php esc_html_e( 'Default: 1.5rem', 'k_timelinda' ); ?></p>
		<?php }

		function k_timelinda_field_subtitle_size() { ?>
			<input class="regular-text" id="k_timelinda-field-subtitle-size" type="text" name="k_timelinda-field-subtitle-size" value="<?php echo get_option('k_timelinda-field-subtitle-size', '1.25rem'); ?>">

			<p class="description"><?php esc_html_e( 'Default: 1.25rem', 'k_timelinda' ); ?></p>
		<?php }

		function k_timelinda_field_timelinda_color() { ?>
			<input class="regular-text color-picker" id="k_timelinda-field-timelinda-color" type="text" name="k_timelinda-field-timelinda-color" value="<?php echo get_option('k_timelinda-field-timelinda-color', '#6f8e3b'); ?>">
		<?php }

		function k_timelinda_field_border_color() { ?>
			<input class="regular-text color-picker" id="k_timelinda-field-border-color" type="text" name="k_timelinda-field-border-color" value="<?php echo get_option('k_timelinda-field-border-color', '#cedcaa'); ?>">
		<?php }

		function k_timelinda_field_title_color() { ?>
			<input class="regular-text color-picker" id="k_timelinda-field-title-color" type="text" name="k_timelinda-field-title-color" value="<?php echo get_option('k_timelinda-field-title-color', '#9db954'); ?>">
		<?php }

		function k_timelinda_field_subtitle_color() { ?>
			<input class="regular-text color-picker" id="k_timelinda-field-subtitle-color" type="text" name="k_timelinda-field-subtitle-color" value="<?php echo get_option('k_timelinda-field-subtitle-color', '#a4a4a4'); ?>">
		<?php }

		function k_timelinda_field_text_color() { ?>
			<input class="regular-text color-picker" id="k_timelinda-field-text-color" type="text" name="k_timelinda-field-text-color" value="<?php echo get_option('k_timelinda-field-text-color', '#888888'); ?>">
		<?php }

		function k_timelinda_scripts() {
			wp_enqueue_style( 'wp-color-picker' ); 

			wp_enqueue_script('k-color-picker', plugin_dir_url( __FILE__ ) . 'admin/assets/js/color-picker.js', array( 'wp-color-picker' ), false, '1.0');
			wp_enqueue_script('jquery-color');
		}
	}

	new K_Timelinda_Options;

}

// Assets
function k_timelinda_enqueue_scripts() {
	global $post;
	wp_register_style( 'timelinda-style', plugin_dir_url(__FILE__) . 'assets/css/style.css?v='.microtime() );
	
	if ( has_shortcode( $post->post_content, 'timeline' ) ) {
		wp_enqueue_style ( 'timelinda-style' );
	}
}
add_action( 'wp_enqueue_scripts', 'k_timelinda_enqueue_scripts', 100 );

function k_timelinda_custom_css() {
	global $post;

	if ( has_shortcode( $post->post_content, 'timeline' ) ) { ?>

		<style type="text/css" id="timelinda-style-custom-css">
			.timelinda {
				color: <?php echo get_option('k_timelinda-field-text-color', '#888888'); ?>;
				font-size: <?php echo get_option('k_timelinda-field-text-size', '14px'); ?>;
				line-height: <?php echo get_option('k_timelinda-field-line-height', '1.5rem'); ?>;
			}

			.timelinda::before,
			.timelinda__event::after {
				background: <?php echo get_option('k_timelinda-field-timelinda-color', '#6f8e3b'); ?>;
			}

			.timelinda__event::after {
				border-color: <?php echo get_option('k_timelinda-field-border-color', '#cedcaa'); ?>;
			}

			.timelinda__event__title {
				font-size: <?php echo get_option('k_timelinda-field-title-size', '1.5rem'); ?>;
				color: <?php echo get_option('k_timelinda-field-title-color', '#9db954'); ?>;
			}

			.timelinda__event__subtitle {
				font-size: <?php echo get_option('k_timelinda-field-subtitle-size', '1.25rem'); ?>;
				color: <?php echo get_option('k_timelinda-field-subtitle-color', '#a4a4a4'); ?>;
			}
		</style>

	<?php }
}
add_action( 'wp_head', 'k_timelinda_custom_css', 101 );

// Shortcodes

if ( !shortcode_exists( '[timeline]' ) ) {
	function k_timelinda( $atts, $content = '' ) {

		$array = array(
			'<p>' => '',
			'</p>' => '',
		);

		$content = strtr( $content, $array );

		return '<div class="timelinda">' . do_shortcode( $content ) . '</div>';
	}
	add_shortcode( 'timeline', 'k_timelinda' );
}

if ( !shortcode_exists('[timeline_event]') ) {
	function k_timelinda_event( $atts, $content = '' ) {
		extract( shortcode_atts( array(
			'title' => '',
			'subtitle' => '',
			'align' => ''
		), $atts ) );

		if ( $title) $title = '<div class="timelinda__event__title">' . $title . '</div>';
		if ( $subtitle) $subtitle = '<div class="timelinda__event__subtitle">' . $subtitle . '</div>';
		if ( $content) $content = '<div class="timelinda__event__text">' . $content . '</div>';

		if ( $align == 'left') {
			$align = ' timelinda__event--left';
		} elseif ( $align == 'right') {
			$align = ' timelinda__event--right';
		} else {
			$align = '';
		}

		return '<div class="timelinda__event' . $align . '">' .
			$title .
			$subtitle .
			$content .
		'</div>';
	}
	add_shortcode( 'timeline_event', 'k_timelinda_event' );
}

// Plugin info

function k_timelinda_add_extra_headers() {
	return array( 'Author2' );
}
add_filter( 'extra_plugin_headers', 'k_timelinda_add_extra_headers' );

function k_timelinda_custom_row_meta( $links, $file ) {
	if ( strpos( $file, 'timelinda.php' ) !== false ) {
		$new_links = array(
			'fl' => '<a href="https://www.fabiolobo.com.br" target="_blank" rel="noopener">Fabio Lobo</a>'
		);
		
		$links = array_merge( $links, $new_links );
	}
	
	return $links;
}
add_filter( 'plugin_row_meta', 'k_timelinda_custom_row_meta', 10, 2 );

function k_timelinda_plugin_links( $links ) {
	$links = array_merge( array(
		'<a href="' . esc_url( admin_url( '/options-general.php' ) ) . '?page=k_timelinda">' . __( 'Settings', 'k' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'k_timelinda_plugin_links' );