<?php
/**
 * Plugin Name: SupaPress
 * Plugin URI: http://www.supadu.com
 * Description: Quickly and easily connect your WordPress site to your book data.
 * Version: 1.0.0
 * Author: Supadu
 * Author URI: http://www.supadu.com
 * Text Domain: supapress
 * Domain Path: /languages/
 * License: GPL2
 */

/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( defined('WP_DEBUG') && WP_DEBUG === true ) {
	error_reporting( E_ALL );
	ini_set( 'display_errors', '1' );
}

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

defined( 'ABSPATH' ) or die( 'Illegal Access!' );

define( 'SUPAPRESS_VERSION', '1.0.0' );

define( 'SUPAPRESS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

define( 'SUPAPRESS_PLUGIN_DIR', untrailingslashit( dirname( __FILE__ ) ) );

define( 'SUPAPRESS_PLUGIN_URL', untrailingslashit( plugins_url( '', __FILE__ ) ) );

define( 'SUPAPRESS_DEFAULT_SERVICE_URL', 'http://folioservices.lb.supadu.com/' );

define( 'SUPAPRESS_DEFAULT_SERVICE_API', '9819864f6ff0e8a5a097c99224cfd18a' );

define( 'SUPAPRESS_DEFAULT_NO_BOOKS_MESSAGE', 'No books found...' );

require_once SUPAPRESS_PLUGIN_DIR . '/settings.php';