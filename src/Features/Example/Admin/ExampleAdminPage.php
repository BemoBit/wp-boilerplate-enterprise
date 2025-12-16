<?php
/**
 * Example Admin Page.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Admin;

/**
 * Class ExampleAdminPage
 *
 * Renders the Example feature admin page.
 */
final class ExampleAdminPage
{
    /**
     * Render the admin page.
     */
    public static function render(): void
    {
        if (! current_user_can('manage_wp_starter_plugin')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'wp-starter-plugin'));
        }

        $viewPath = WP_STARTER_PLUGIN_DIR . 'views/admin/example-page.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            self::renderDefaultView();
        }
    }

    /**
     * Render the default view if template is not found.
     */
    private static function renderDefaultView(): void
    {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p><?php esc_html_e('Welcome to the Example feature admin page.', 'wp-starter-plugin'); ?></p>
            <p><?php esc_html_e('This is a placeholder. Create your admin view at: views/admin/example-page.php', 'wp-starter-plugin'); ?></p>
        </div>
        <?php
    }
}
