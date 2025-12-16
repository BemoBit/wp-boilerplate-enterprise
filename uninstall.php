<?php
/**
 * Plugin Uninstall Handler.
 *
 * This file is executed when the plugin is deleted from WordPress.
 * It removes all plugin data from the database.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

// If uninstall not called from WordPress, exit.
defined('WP_UNINSTALL_PLUGIN') || exit;

/**
 * Clean up plugin data on uninstall.
 */
function wp_starter_plugin_uninstall(): void {
    global $wpdb;

    // Delete plugin options
    delete_option('wp_starter_plugin_version');
    delete_option('wp_starter_plugin_db_version');
    delete_option('wp_starter_plugin_settings');
    delete_option('wp_starter_plugin_queue');

    // Delete all plugin transients
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_wp_starter_%'
        )
    );
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
            '_transient_timeout_wp_starter_%'
        )
    );

    // Drop custom tables (uncomment when you have custom tables)
    // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}starter_examples");

    // Delete user meta
    delete_metadata('user', 0, 'wp_starter_plugin_preferences', '', true);

    // Remove custom capabilities
    $roles = wp_roles();
    foreach ($roles->role_objects as $role) {
        $role->remove_cap('manage_wp_starter_plugin');
    }

    // Clear any scheduled cron events
    wp_clear_scheduled_hook('wp_starter_plugin_daily_maintenance');

    // Flush rewrite rules
    flush_rewrite_rules();
}

wp_starter_plugin_uninstall();
