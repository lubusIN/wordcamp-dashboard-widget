<?php
/**
 *
 * @link              http://www.lubus.in
 * @since             0.1.1
 * @package           WordCamp_Dashboard_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       WordCamp Dashboard Widget
 * Plugin URI:        https://github.com/lubusonline/wordcamp-dashboard-widget
 * Description:       Wordpress plugin to show upcoming WordCamp on wp-admin dashboard.
 * Version:           0.1.1
 * Author:            LUBUS, Ajit Bohra
 * Author URI:        http://www.lubus.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordcamp-dashboard-widget
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function lubus_wdw_activate() {
	lubus_wdw_get_wordcamp_data(); // Get data on activation 
}

/**
 * The code that runs during plugin deactivation.
 */
function lubus_wdw_deactivate() {
	delete_transient("lubus_wdw_wordcamp_JSON"); // Remove transient data for plugin
}

/**
 * Activation / Deactivation Hooks.
 */
register_activation_hook( __FILE__, 'lubus_wdw_activate' );
register_deactivation_hook( __FILE__, 'lubus_wdw_deactivate' );

/**
 * Enqueue styles
 */
wp_enqueue_style( "css-datatables", plugin_dir_url( __FILE__ ) . 'assets/css/jquery.dataTables.min.css', array(), "1.0", 'all' );
wp_enqueue_style( "css-style", plugin_dir_url( __FILE__ ) . 'assets/css/style.css', array(), "1.0", 'all' );

/**
 * Enqueue scripts
 */
wp_enqueue_script( "js-datatables", plugin_dir_url( __FILE__ ) . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), "1.0", false );
wp_enqueue_script( "js-script", plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery','js-datatables' ), "1.0", false );

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
 * Create the function to output the contents of our Dashboard Widget.
 */

function lubus_wdw_display_wordcamps() {
	$upcoming_wordcamps = lubus_wdw_get_wordcamp_data();
	if($upcoming_wordcamps){
?>
	    <table id="lubus-wordcamp" class="display">
		<thead>
			<tr>
				<th scope="col" class="column-primary">Event</th>
				<th id="date" scope="col">Date</th>
				<th scope="col">Location</th>
			</tr>
		</thead>
		<tbody>
		    <?php
			foreach($upcoming_wordcamps as $key => $value)
			{
				$timestamp = lubus_wdw_get_meta($value['post_meta'],"Start Date (YYYY-mm-dd)");
				?>
				<tr>
					<td class="column-primary" data-colname="Event">
						<a href="<?php echo lubus_wdw_get_meta($value['post_meta'],"URL") ?>" target="_new">
							<?php echo $value['title']; ?>
						</a>
					</td>
					<td data-colname="Date">
						<?php 
							echo date("d-m-Y", $timestamp);
						?>
					</td>
					<td data-colname="Location">
						<?php echo lubus_wdw_get_meta($value['post_meta'],"Location"); ?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
	<?php
	} else {
	?>
		<div class="wp-ui-notification" id="lubus_wdw_error">
			<p><span class="dashicons dashicons-dismiss"></span> Unable to connect to wordcamp API try again later ... !</p>
		</div>
		<p class="wp-ui-text-notification">If error persist <a href="https://github.com/lubusonline/wordcamp-dashboard-widget/issues/new" target="_new">click here</a> to create issue on github</p>
	<?php
	}
}

/**
 * Get Wordcamp Data
 */
function lubus_wdw_get_wordcamp_data(){
	  $transient = get_transient( 'lubus_wdw_wordcamp_JSON' );
	  if( ! empty( $transient ) ) {
		    return json_decode($transient,true);
	  } else {
	  		$api_url = 'https://central.wordcamp.org/wp-json/posts?type=wordcamp&filter[order]=DESC&filter[posts_per_page]=150';
			$out = wp_remote_get( $api_url);  // Call the API.

	  		if (lubus_wdw_check_response($out)) { 		
		  		// Get json data & filterit:
			    $parsed_json = json_decode($out['body'], true);
			    $upcoming_wordcamps = array();

			    // Create New JSON from filtered data
				foreach($parsed_json as $key => $value)
				{
					$wordcamp_date = lubus_wdw_get_meta($value['post_meta'],"Start Date (YYYY-mm-dd)");
					$today = date("Y-m-d");

					// Create new JSON
					if (date("Y-m-d",$wordcamp_date) >= $today) {
						$upcoming_wordcamps[] = $value;
						$upcoming_wordcamps = $upcoming_wordcamps;
					}
				}
				 set_transient( 'lubus_wdw_wordcamp_JSON',json_encode($upcoming_wordcamps), DAY_IN_SECONDS );
				 return $upcoming_wordcamps;
			}
			return false;
	  }
}

/**
 * Get meta from array
 */
function lubus_wdw_get_meta($meta_array,$meta_key){
	$meta_value = 'N/A';
	foreach($meta_array as $m_key => $m_value) {
			if ($m_value["key"] == $meta_key) {
				 $meta_value = $m_value["value"];
			}
       }
     return $meta_value;
}

/**
 * Given an HTTP response, check it to see if it is worth storing.
 */
function lubus_wdw_check_response( $response ) {
  if( ! is_array( $response ) ) { return FALSE; } // Is the response an array?
  if( is_wp_error( $response ) ) { return FALSE; } // Is the response a wp error?
  if( ! isset( $response['response'] ) ) { return FALSE; } // Is the response weird?
  if( ! isset( $response['response']['code'] ) ) { return FALSE; }   // Is there a status code?
  if( in_array( $response['response']['code'], lubus_wdw_bad_status_codes() ) ) { return FALSE; }   // Is the status code bad?
  
  return $response['response']['code'];  // We made it!  Return the status code, just for posterity's sake.
}

/**
 * A list of HTTP statuses that suggest that we have data that is not worth storing.
 */
function lubus_wdw_bad_status_codes() {
  return array( 404, 500 );
}
?>