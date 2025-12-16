<?php
/**
 * Plugin Deactivator.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Core;

/**
 * Class Deactivator
 *
 * Handles plugin deactivation tasks.
 */
final class Deactivator
{
    /**
     * Run deactivation tasks.
     *
     * This method is called when the plugin is deactivated.
     */
    public static function deactivate(): void
    {
        self::clearScheduledEvents();
        self::clearTransients();
        self::flushRewriteRules();
    }

    /**
     * Clear all scheduled cron events.
     */
    private static function clearScheduledEvents(): void
    {
        wp_clear_scheduled_hook('wp_starter_plugin_daily_maintenance');
    }

    /**
     * Clear plugin transients.
     */
    private static function clearTransients(): void
    {
        delete_transient('wp_starter_plugin_cache');
    }

    /**
     * Flush rewrite rules.
     */
    private static function flushRewriteRules(): void
    {
        flush_rewrite_rules();
    }
}
