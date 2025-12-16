<?php
/**
 * Example Admin Page View.
 *
 * @package WPStarterPlugin
 */

defined('ABSPATH') || exit;
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="wp-starter-plugin-admin">
        <div class="wp-starter-plugin-admin__header">
            <p><?php esc_html_e('Welcome to the Example feature admin page.', 'wp-starter-plugin'); ?></p>
        </div>

        <div class="wp-starter-plugin-admin__content">
            <div class="card">
                <h2><?php esc_html_e('Getting Started', 'wp-starter-plugin'); ?></h2>
                <p><?php esc_html_e('This is a placeholder admin page. Customize it to fit your needs.', 'wp-starter-plugin'); ?></p>
            </div>

            <div class="card">
                <h2><?php esc_html_e('Documentation', 'wp-starter-plugin'); ?></h2>
                <p><?php esc_html_e('Check out the README.md file for documentation on how to use this boilerplate.', 'wp-starter-plugin'); ?></p>
            </div>
        </div>
    </div>
</div>
