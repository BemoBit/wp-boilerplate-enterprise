<?php
/**
 * Plugin Permissions Configuration.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

return [
    /**
     * Custom capabilities for the plugin.
     */
    'capabilities' => [
        'manage_wp_starter_plugin' => [
            'administrator',
        ],
        'edit_wp_starter_plugin' => [
            'administrator',
            'editor',
        ],
        'view_wp_starter_plugin' => [
            'administrator',
            'editor',
            'author',
        ],
    ],

    /**
     * Role-based access for features.
     */
    'features' => [
        'example' => [
            'view' => 'view_wp_starter_plugin',
            'edit' => 'edit_wp_starter_plugin',
            'manage' => 'manage_wp_starter_plugin',
        ],
    ],
];
