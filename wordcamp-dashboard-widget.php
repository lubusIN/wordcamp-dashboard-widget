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
 * Contributors: lubus, ajitbohra
 * Plugin Name:       WordCamp Dashboard Widget
 * Plugin URI:        https://github.com/lubusIN/wordcamp-dashboard-widget
 * Description:       Wordpress plugin to show upcoming WordCamps on wp-admin dashboard.
 * Version:           0.6
 * Author:            LUBUS, Ajit Bohra
 * Author URI:        http://www.lubus.in
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wordcamp-dashboard-widget
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

/**
 * Plugin Activation: The code that runs during plugin activation.
 */
function lubus_wdw_activate()
{
    lubus_wdw_get_wordcamp_data(); // Get data on activation.
}
register_activation_hook(__FILE__, 'lubus_wdw_activate');

/**
 * Plugin Deactivation: The code that runs during plugin deactivation.
 */
function lubus_wdw_deactivate()
{
    delete_transient('lubus_wdw_wordcamp_JSON_v2'); // Remove transient data for plugin.
}
register_deactivation_hook(__FILE__, 'lubus_wdw_deactivate');

/**
 * Shortcode styles.
 */
function lubus_wdw_styles()
{
    wp_register_style('css-datatables', plugin_dir_url(__FILE__) . 'assets/css/jquery.dataTables.min.css', array(), '1.0', 'all');
    wp_register_style('css-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), '1.0', 'all');


    wp_enqueue_style('dashicons');
    wp_enqueue_style('css-datatables');
    wp_enqueue_style('css-style');
}

/**k
 * Enqueue styles
 *
 * @param string $hook HookName.
 */
function lubus_wdw_add_styles($hook)
{
    if ('index.php' !== $hook) {
        return;
    } // Only if its main dashboard page
    lubus_wdw_styles();
}
add_action('admin_enqueue_scripts', 'lubus_wdw_add_styles');

/**
 * Shortcode scripts
 */
function lubus_wdw_scripts()
{
    wp_register_script('js-datatables', plugin_dir_url(__FILE__) . 'assets/js/jquery.dataTables.min.js', array( 'jquery' ), '1.0', false);
    wp_register_script('js-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array( 'jquery', 'js-datatables' ), '1.0', false);

    wp_enqueue_script('js-datatables');
    wp_enqueue_script('js-script');
}

/**
 * Enqueue Scripts
 *
 * @param string $hook HookName.
 */
function lubus_wdw_add_scripts($hook)
{
    if ('index.php' !== $hook) {
        return;
    }  // Only if its main dashboard page
    lubus_wdw_scripts();
}
add_action('admin_enqueue_scripts', 'lubus_wdw_add_scripts');

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function lubus_wdw_add_widget()
{
    wp_add_dashboard_widget(
        'lubus_wdw_wordcamp_widget',  // Widget slug.
        'Upcoming WordCamps',      // Title.
        'lubus_wdw_display_wordcamps' // Display function.
    );
}
add_action('wp_dashboard_setup', 'lubus_wdw_add_widget');

/**
 * Shortcode to display Upcoming WordCamp
 *
 * @param  array $atts attributes array.
 * @return mixed        WordCamp Data.
 */
function cs_wordcamps($atts)
{
    // Shortcode attributes.
    shortcode_atts(
        array(
            'pagesize'    => '10',
            'theme'    => 'none',
            'country'    => 'india',
        ),
        $atts,
        'wordcamps'
    );

    lubus_wdw_styles();
    lubus_wdw_scripts();

    ob_start();
    lubus_wdw_display_wordcamps();
    return ob_get_clean();
}
add_shortcode('wordcamps', 'cs_wordcamps');

/**
 * Visual Composer Integration.
 */
if (defined('WPB_VC_VERSION')) {
    add_action('vc_before_init', 'lubus_wdw_VC_integration');
    /**
     * Support for Visual Composer.
     */
    function lubus_wdw_vc_integration()
    {
        vc_map(array(
            'name' => __('WordCamps Listing', 'vc_extend'),
            'description' => __('Display Upcoming WordCamps', 'vc_extend'),
            'base' => 'wordcamps',
            'class' => '',
            'controls' => 'full',
            'icon' => plugin_dir_url(__FILE__) . 'assets/images/asterisk_yellow.png', // or css class name which you can reffer in your css file later. Example: 'vc_extend_my_class'.
            'category' => __('Content', 'js_composer'),
            'params' => array(
                array(
                     'type' => 'text',
                     'holder' => 'div',
                     'class' => '',
                     'heading' => __('Hit Save - No Settings'),
                     'param_name' => 'lubus_wdw_country',
                     'value' => __('Hit Save - No Settings'),
                     'description' => __('Coming Soon ...'),
                    ),
                ),
            )
        );
    }
}

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function lubus_wdw_display_wordcamps()
{
    $upcoming_wordcamps = lubus_wdw_get_wordcamp_data(); // Get data.

    // Generate tables if contains data and not a WP_ERROR.
    if ($upcoming_wordcamps && ! is_wp_error($upcoming_wordcamps)) {
        ?>
	<table class="display lubus-wordcamp-table">
		<thead>
			<tr>
				<th scope="col" class="column-primary">Location</th>
				<th id="date" scope="col">Date</th>
				<th scope="col">Twitter</th>
			</tr>
		</thead>
		<tbody>
		    <?php
            foreach ($upcoming_wordcamps as $key => $value) {
                $timestamp = $value['Start Date (YYYY-mm-dd)']; ?>
				<tr>
					<td class="column-primary" data-colname="Location">
						<a href="<?php echo esc_html($value['URL']) ?>" target="_new" title="WordCamp Homepage">
							<?php echo esc_html($value['Location']); ?>
						</a>
					</td>

					<td data-colname="Date" data-order="<?php echo esc_html(date('Y-m-d', $timestamp)); ?>">
						<?php echo esc_html(date(get_option('date_format'), $timestamp)); ?>
					</td>

					<td data-colname="Twitter">
						<?php $twitter_handle = lubus_wdw_get_twitter_data($value['Twitter'], 'url'); ?>
						<?php if ($twitter_handle) : ?>
							<a href="<?php echo esc_html($twitter_handle); ?>" target="_new" title="Twitter profile">
								<span class="wdw_hashtag">@</span>
							</a>
						<?php else : ?>
							<span class="wdw_hashtag wdw_na">-</span>
						<?php endif ?>

						<span class="wdw_sep">|</span>

						<?php $twitter_hashtag = lubus_wdw_get_twitter_data($value['WordCamp Hashtag'], 'hashtag'); ?>
						<?php if ($twitter_hashtag) : ?>
							<a href="<?php echo esc_html($twitter_hashtag); ?>" target="_new" title="Twitter Hashtag">
								<span class="wdw_hashtag wdw_na">#</span>
							</a>
						<?php else : ?>
							<span class="wdw_hashtag">-</span>
						<?php endif ?>


					</td>
				</tr>
				<?php

            } ?>
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
            if (is_wp_error($upcoming_wordcamps)) {
                $error_string = $upcoming_wordcamps->get_error_message();
                echo '<p class="wp-ui-text-notification">' . esc_html($error_string) . '</p>';
            } ?>
		</p>
	<?php

    }
}

/**
 * Get Wordcamp Data
 */
function lubus_wdw_get_wordcamp_data()
{
    $transient = get_transient('lubus_wdw_wordcamp_JSON_v2'); // Get data from wordpress transient/cache.
    if (! empty($transient)) {
        return json_decode($transient, true);
    } else {
        $api_url = 'https://central.wordcamp.org/wp-json/wp/v2/wordcamps?per_page=100&status=wcpt-scheduled&order=desc'; // API URL.
        $request_args = array( 'sslverify' => false, 'timeout' => 10 );
        $api_response = (function_exists('vip_safe_wp_remote_get') ? vip_safe_wp_remote_get($api_url, $request_args) : wp_remote_get($api_url, $request_args));  // Call the API.

        if (lubus_wdw_check_response($api_response)) {
            // Get json data & store data to wordpress transient/cache.
            $upcoming_wordcamps = json_decode($api_response['body'], true);
            set_transient('lubus_wdw_wordcamp_JSON_v2', wp_json_encode($upcoming_wordcamps), DAY_IN_SECONDS);
            return $upcoming_wordcamps;
        }

        if (is_wp_error($api_response)) {
            return  $api_response;
        }
        return false;
    }
}

/**
 * Get twitter data
 *
 * @param  string $data   Twitter Data (From APi).
 * @param  string $data_type  Twitter Data ( URL / Hashtag ).
 * @return string             [description].
 */
function lubus_wdw_get_twitter_data($data, $data_type)
{
    $twitter_data = $data; // Get inconsistent twitter data.
    if ($twitter_data) {
        $regx_twitter_url = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
        $regx_twitter_hashtag = '/\S*#(?:\[[^\]]+\]|\S+)/';
        $twitter_url = 'https://www.twitter.com/';
        switch ($data_type) {
            case 'url':
                    $twitter_url = (preg_match($regx_twitter_url, $twitter_data) ? $twitter_url = $twitter_data : $twitter_url .= $twitter_data);
                break;
            case 'hashtag':
                    $twitter_url = (preg_match($regx_twitter_hashtag, $twitter_data) ? $twitter_url .= $twitter_data : $twitter_url .= '#' . $twitter_data);
                break;
            default:
                    $twitter_url = null;
                break;
        }
    } else {
        $twitter_url = null;
    }

    return $twitter_url;
}

/**
 * Given an HTTP response, check it to see if it is worth storing.
 *
 * @param  array $response request response array.
 * @return string          HTTP status code.
 */
function lubus_wdw_check_response($response)
{
    if (! is_array($response)) {
        return false;
    } // Is the response an array?
    if (is_wp_error($response)) {
        return false;
    } // Is the response a wp error?
    if (! isset($response['response'])) {
        return false;
    } // Is the response weird?
    if (! isset($response['response']['code'])) {
        return false;
    }   // Is there a status code?
    if (in_array($response['response']['code'], lubus_wdw_bad_status_codes(), true)) {
        return false;
    }   // Is the status code bad?

    return $response['response']['code'];  // We made it!  Return the status code, just for posterity's sake.
}

/**
 * A list of HTTP statuses that suggest that we have data that is not worth storing.
 */
function lubus_wdw_bad_status_codes()
{
    return array( 404, 500 );
}
?>
