<?php
/**
 *
 * Display upcoming WordCamp
 *
 * @link              http://www.lubus.in
 * @since             0.1
 * @package           WordCamp_Dashboard_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       WordCamp Dashboard Widget
 * Plugin URI:        https://github.com/lubusIN/wordcamp-dashboard-widget
 * Description:       Wordpress plugin to show upcoming WordCamps on wp-admin dashboard.
 * Version:           0.5
 * Author:            LUBUS, Ajit Bohra
 * Author URI:        http://www.lubus.in
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wordcamp-dashboard-widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin Activation: The code that runs during plugin activation.
 */
function lubus_wdw_activate() {
	lubus_wdw_get_wordcamp_data(); // Get data on activation.
}
register_activation_hook( __FILE__, 'lubus_wdw_activate' );

/**
 * Plugin Deactivation: The code that runs during plugin deactivation.
 */
function lubus_wdw_deactivate() {
	delete_transient( 'lubus_wdw_wordcamp_JSON' ); // Remove transient data for plugin.
}
register_deactivation_hook( __FILE__, 'lubus_wdw_deactivate' );

/**
 * Shortcode styles.
 */
function lubus_wdw_styles() {
	wp_register_style( 'css-datatables', plugin_dir_url( __FILE__ ) . 'assets/css/jquery.dataTables.min.css', array(), '1.0', 'all' );
	wp_register_style( 'css-style', plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), '1.0', 'all' );

	wp_enqueue_style( 'css-datatables' );
	wp_enqueue_style( 'css-style' );
}

/**
 * Enqueue styles
 *
 * @param string $hook HookName.
 */
function lubus_wdw_add_styles( $hook ) {
	if ( 'index.php' !== $hook ) { return; } // Only if its main dashboard page
	lubus_wdw_styles();
}
add_action( 'admin_enqueue_scripts', 'lubus_wdw_add_styles' );

/**
 * Shortcode scripts
 */
function lubus_wdw_scripts() {
	wp_register_script( 'js-datatables', plugin_dir_url( __FILE__ ) . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), '1.0', false );
	wp_register_script( 'js-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery', 'js-datatables' ), '1.0', false );

	wp_enqueue_script( 'js-datatables' );
	wp_enqueue_script( 'js-script' );
}

/**
 * Enqueue Scripts
 *
 * @param string $hook HookName.
 */
function lubus_wdw_add_scripts( $hook ) {
	if ( 'index.php' !== $hook ) { return; }  // Only if its main dashboard page
	lubus_wdw_scripts();
}
add_action( 'admin_enqueue_scripts', 'lubus_wdw_add_scripts' );

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function lubus_wdw_add_widget() {
	wp_add_dashboard_widget(
		'lubus_wdw_wordcamp_widget',  // Widget slug.
		'Upcoming WordCamps',      // Title.
		'lubus_wdw_display_wordcamps' // Display function.
	);
}
add_action( 'wp_dashboard_setup', 'lubus_wdw_add_widget' );

/**
 * Shortcode to display Upcoming WordCamp
 *
 * @param  array $atts attributes array.
 * @return mixed        WordCamp Data.
 */
function cs_wordcamps( $atts ) {

	// Shortcode attributes.
	$atts = shortcode_atts(
		array(
			'pagesize' 	=> '10',
			'theme' 		=> 'none',
			'country' 	=> 'india',
		),
		$atts,
		'wordcamps'
	);

	lubus_wdw_styles();
	lubus_wdw_scripts();
	lubus_wdw_display_wordcamps();

}
add_shortcode( 'wordcamps', 'cs_wordcamps' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function lubus_wdw_display_wordcamps() {
	$upcoming_wordcamps = lubus_wdw_get_wordcamp_data(); // Get data.

	// Generate tables if contains data and not a WP_ERROR.
	if ( $upcoming_wordcamps && ! is_wp_error( $upcoming_wordcamps )  ) {
?>
	<table id="lubus-wordcamp" class="display">
		<thead>
			<tr>
				<th scope="col" class="column-primary">Location</th>
				<th id="date" scope="col">Date</th>
				<th scope="col">Twitter</th>
			</tr>
		</thead>
		<tbody>
		    <?php
			foreach ( $upcoming_wordcamps as $key => $value ) {
				$timestamp = lubus_wdw_get_meta( $value['post_meta'],'Start Date (YYYY-mm-dd)' );
				?>
				<tr>
					<td class="column-primary" data-colname="Location">
						<a href="<?php echo esc_html( lubus_wdw_get_meta( $value['post_meta'],'URL' ) ) ?>" target="_new" title="WordCamp Homepage">
							<?php echo esc_html( lubus_wdw_get_meta( $value['post_meta'],'Location' ) ); ?>
						</a>
					</td>

					<td data-colname="Date" data-order="<?php echo esc_html( date( 'Y-m-d', $timestamp ) ); ?>">
						<?php echo esc_html( date( 'd/m/Y', $timestamp ) ); ?>
					</td>

					<td data-colname="Twitter">
						<a href="<?php echo esc_html( lubus_wdw_get_twitter_data( $value['post_meta'],'Twitter' ,'url' ) ); ?>" target="_new" title="Twitter profile">
							<span class="wdw_hashtag">@</span>
						</a>

						<span class="wdw_sep">|</span>

						<a href="<?php echo esc_html( lubus_wdw_get_twitter_data( $value['post_meta'],'WordCamp Hashtag','hashtag' ) ); ?>" target="_new" title="Twitter Hashtag">
							<span class="wdw_hashtag">#</span>
						</a>

					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	<?php
	} else {
		// Show error if unable to display or fetch data.
	?>
		<div class="wp-ui-notification" id="lubus_wdw_error">
			<p><span class="dashicons dashicons-dismiss"></span> Unable to connect to WordCamp API try reloading the page</p>
		</div>
		<p class=".wp-ui-text-primary">If error persist <a href="https://github.com/lubusIN/wordcamp-dashboard-widget/issues/new" target="_new">click here</a> to create issue on github with the following error message</p>
		<p>
			<?php
			// Developer friend 'error message' for troubleshooting.
			if ( is_wp_error( $upcoming_wordcamps ) ) {
				   $error_string = $upcoming_wordcamps->get_error_message();
				   echo '<p class="wp-ui-text-notification">' . esc_html( $error_string ) . '</p>';
			}
			?>
		</p>
	<?php
	}
}

/**
 * Get Wordcamp Data
 */
function lubus_wdw_get_wordcamp_data() {
	$transient = get_transient( 'lubus_wdw_wordcamp_JSON' ); // Get data from wordpress transient/cache.
	if ( ! empty( $transient ) ) {
		    return json_decode( $transient,true );
	} else {
	  	$api_url = 'https://central.wordcamp.org/wp-json/posts?type=wordcamp&filter[order]=DESC&filter[posts_per_page]=300';
			$request_args = array( 'sslverify' => false, 'timeout' => 10 );
			$api_response = ( function_exists( 'vip_safe_wp_remote_get' ) ? vip_safe_wp_remote_get( $api_url, $request_args ) : wp_remote_get( $api_url, $request_args ) );  // Call the API.

	  	if ( lubus_wdw_check_response( $api_response ) ) {
		  		// Get json data & filterit.
			    $parsed_json = json_decode( $api_response['body'], true );
			    $upcoming_wordcamps = array();

			// Create New JSON from filtered data.
			foreach ( $parsed_json as $key => $value ) {
					$wordcamp_date = lubus_wdw_get_meta( $value['post_meta'], 'Start Date (YYYY-mm-dd)' );
					$today = date( 'Y-m-d' );

				// Create new JSON.
				// Check if data is not empty, N/A or less then.
				if ( '' !== $wordcamp_date && 'N/A' !== $wordcamp_date && date( 'Y-m-d', $wordcamp_date ) >= $today ) {
						$upcoming_wordcamps[] = $value;
				}
			}

			// Store data to wordpress transient/cache.
			set_transient( 'lubus_wdw_wordcamp_JSON', wp_json_encode( $upcoming_wordcamps ), DAY_IN_SECONDS );
			return $upcoming_wordcamps;
		}

		if ( is_wp_error( $api_response ) ) {
				return  $api_response;
		}
			return false;
	}
}

/**
 * Get meta from array
 *
 * @param  array  $meta_array Meta Array.
 * @param  string $meta_key   Meta Key.
 * @return string             Meta Value.
 */
function lubus_wdw_get_meta( $meta_array, $meta_key ) {
	$meta_value = 'N/A';
	foreach ( $meta_array as $m_key => $m_value ) {
		if ( $m_value['key'] === $meta_key ) {
		 	$meta_value = $m_value['value'];
		}
	}
	return $meta_value;
}


/**
 * Get twitter data
 *
 * @param  array  $meta_array Meta array.
 * @param  string $meta_key   Meta key.
 * @param  string $data_type  Twitter Data ( URL / Hashtag ).
 * @return string             [description].
 */
function lubus_wdw_get_twitter_data( $meta_array, $meta_key, $data_type ) {
	$twitter_data = lubus_wdw_get_meta( $meta_array,$meta_key ); // Get inconsistent twitter data.

	$regx_twitter_url = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
	$regx_twitter_hashtag = '/\S*#(?:\[[^\]]+\]|\S+)/';

	$twitter_url = 'https://www.twitter.com/';

	switch ( $data_type ) {
		case 'url':
			 	$twitter_url = ( preg_match( $regx_twitter_url,$twitter_data ) ? $twitter_url = $twitter_data : $twitter_url .= $twitter_data );
			break;

		case 'hashtag':
				$twitter_url = ( preg_match( $regx_twitter_hashtag,$twitter_data ) ? $twitter_url .= $twitter_data : $twitter_url .= '#' . $twitter_data );
			break;
	}

	return $twitter_url;
}

/**
 * Given an HTTP response, check it to see if it is worth storing.
 *
 * @param  array $response request response array.
 * @return string          HTTP status code.
 */
function lubus_wdw_check_response( $response ) {
	if ( ! is_array( $response ) ) { return false; } // Is the response an array?
	if ( is_wp_error( $response ) ) { return false; } // Is the response a wp error?
	if ( ! isset( $response['response'] ) ) { return false; } // Is the response weird?
	if ( ! isset( $response['response']['code'] ) ) { return false; }   // Is there a status code?
	if ( in_array( $response['response']['code'], lubus_wdw_bad_status_codes(),true ) ) { return false; }   // Is the status code bad?

	return $response['response']['code'];  // We made it!  Return the status code, just for posterity's sake.
}

/**
 * A list of HTTP statuses that suggest that we have data that is not worth storing.
 */
function lubus_wdw_bad_status_codes() {
	return array( 404, 500 );
}
?>
