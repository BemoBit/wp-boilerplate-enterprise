<?php
/**
 * Plugin Activator.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Core;

use Starter\Infrastructure\Database\Migrator;

/**
 * Class Activator
 *
 * Handles plugin activation tasks.
 */
final class Activator
{
    /**
     * Run activation tasks.
     *
     * This method is called when the plugin is activated.
     */
    public static function activate(): void
    {
        self::checkRequirements();
        self::runMigrations();
        self::setDefaultOptions();
        self::addCapabilities();
        self::scheduleCronEvents();
        self::flushRewriteRules();
    }

    /**
     * Check minimum requirements.
     */
    private static function checkRequirements(): void
    {
        if (version_compare(PHP_VERSION, WP_STARTER_PLUGIN_MIN_PHP, '<')) {
            wp_die(
                sprintf(
                    /* translators: %s: Minimum PHP version */
                    esc_html__('WP Starter Plugin requires PHP %s or higher.', 'wp-starter-plugin'),
                    WP_STARTER_PLUGIN_MIN_PHP
                ),
                esc_html__('Plugin Activation Error', 'wp-starter-plugin'),
                ['back_link' => true]
            );
        }

        global $wp_version;
        if (version_compare($wp_version, WP_STARTER_PLUGIN_MIN_WP, '<')) {
            wp_die(
                sprintf(
                    /* translators: %s: Minimum WordPress version */
                    esc_html__('WP Starter Plugin requires WordPress %s or higher.', 'wp-starter-plugin'),
                    WP_STARTER_PLUGIN_MIN_WP
                ),
                esc_html__('Plugin Activation Error', 'wp-starter-plugin'),
                ['back_link' => true]
            );
        }
    }

    /**
     * Run database migrations.
     */
    private static function runMigrations(): void
    {
        Migrator::up();
    }

    /**
     * Set default plugin options.
     */
    private static function setDefaultOptions(): void
    {
        $defaults = [
            'enabled' => true,
            'debug_mode' => false,
        ];

        add_option('wp_starter_plugin_version', WP_STARTER_PLUGIN_VERSION);
        add_option('wp_starter_plugin_settings', $defaults);
    }

    /**
     * Add custom capabilities to roles.
     */
    private static function addCapabilities(): void
    {
        $admin = get_role('administrator');
        if ($admin) {
            $admin->add_cap('manage_wp_starter_plugin');
        }
    }

    /**
     * Schedule cron events.
     */
    private static function scheduleCronEvents(): void
    {
        if (! wp_next_scheduled('wp_starter_plugin_daily_maintenance')) {
            wp_schedule_event(time(), 'daily', 'wp_starter_plugin_daily_maintenance');
        }
    }

    /**
     * Flush rewrite rules.
     */
    private static function flushRewriteRules(): void
    {
        flush_rewrite_rules();
    }
}
