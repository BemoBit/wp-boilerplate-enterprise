<?php
/**
 * Database Migrator.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Infrastructure\Database;

/**
 * Class Migrator
 *
 * Handles database migrations.
 */
final class Migrator
{
    /**
     * Run all migrations.
     */
    public static function up(): void
    {
        self::createTables();
        self::updateVersion();
    }

    /**
     * Rollback all migrations.
     */
    public static function down(): void
    {
        self::dropTables();
    }

    /**
     * Create custom database tables.
     */
    private static function createTables(): void
    {
        global $wpdb;
        $charsetCollate = $wpdb->get_charset_collate();

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        // Example table - customize as needed
        // $tableName = $wpdb->prefix . 'starter_example';
        // $sql = "CREATE TABLE {$tableName} (
        //     id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        //     title varchar(255) NOT NULL,
        //     status varchar(20) DEFAULT 'active',
        //     created_at datetime DEFAULT CURRENT_TIMESTAMP,
        //     updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        //     PRIMARY KEY (id),
        //     KEY status (status)
        // ) {$charsetCollate};";
        // dbDelta($sql);
    }

    /**
     * Drop custom database tables.
     */
    private static function dropTables(): void
    {
        global $wpdb;

        // Example: Drop tables on uninstall
        // $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}starter_example");
    }

    /**
     * Update the database version.
     */
    private static function updateVersion(): void
    {
        update_option('wp_starter_plugin_db_version', WP_STARTER_PLUGIN_VERSION);
    }

    /**
     * Get the current database version.
     */
    public static function getVersion(): string
    {
        return (string) get_option('wp_starter_plugin_db_version', '0.0.0');
    }

    /**
     * Check if migration is needed.
     */
    public static function needsMigration(): bool
    {
        return version_compare(self::getVersion(), WP_STARTER_PLUGIN_VERSION, '<');
    }
}
