<?php
/**
 * PHPUnit Bootstrap File.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

// Composer autoloader
$autoloader = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// WordPress test suite
$wpTestsDir = getenv('WP_TESTS_DIR') ?: '/tmp/wordpress-tests-lib';

if (! file_exists($wpTestsDir . '/includes/functions.php')) {
    echo "Could not find WordPress test suite at: {$wpTestsDir}\n";
    echo "Run: bin/install-wp-tests.sh <db-name> <db-user> <db-pass> [db-host] [wp-version]\n";
    exit(1);
}

// Load WordPress test functions
require_once $wpTestsDir . '/includes/functions.php';

/**
 * Manually load the plugin for testing.
 */
function _manually_load_plugin(): void {
    require dirname(__DIR__) . '/wp-starter-plugin.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Start up the WordPress testing environment
require $wpTestsDir . '/includes/bootstrap.php';
