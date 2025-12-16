<?php
/**
 * Plugin Name:       WP Starter Plugin
 * Plugin URI:        https://github.com/developer/wp-starter-plugin
 * Description:       Enterprise-grade WordPress plugin boilerplate with Feature-Based (DDD) architecture.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.2
 * Author:            Developer Name
 * Author URI:        https://developer.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-starter-plugin
 * Domain Path:       /languages
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter;

defined('ABSPATH') || exit;

/**
 * Plugin version.
 */
define('WP_STARTER_PLUGIN_VERSION', '1.0.0');

/**
 * Plugin directory path.
 */
define('WP_STARTER_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * Plugin directory URL.
 */
define('WP_STARTER_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Plugin basename.
 */
define('WP_STARTER_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Minimum PHP version required.
 */
define('WP_STARTER_PLUGIN_MIN_PHP', '8.2');

/**
 * Minimum WordPress version required.
 */
define('WP_STARTER_PLUGIN_MIN_WP', '6.0');

// Autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Lifecycle hooks (must be in main file)
register_activation_hook(__FILE__, [Core\Activator::class, 'activate']);
register_deactivation_hook(__FILE__, [Core\Deactivator::class, 'deactivate']);

// Bootstrap the plugin
add_action('plugins_loaded', static function (): void {
    (new Core\Plugin())->boot();
});
