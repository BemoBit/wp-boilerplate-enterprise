<?php
/**
 * Example Feature Provider.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example;

use Starter\Features\Example\Admin\ExampleAdminPage;
use Starter\Features\Example\Api\ExampleController;
use Starter\Features\Example\Frontend\ExampleShortcode;
use Starter\Features\Example\Services\ExampleService;
use Starter\Shared\Abstracts\AbstractServiceProvider;

/**
 * Class ExampleProvider
 *
 * Service provider for the Example feature.
 * This is a sample feature to demonstrate the plugin architecture.
 */
final class ExampleProvider extends AbstractServiceProvider
{
    /**
     * Constructor.
     *
     * @param ExampleService    $service    The example service.
     * @param ExampleController $controller The REST API controller.
     */
    public function __construct(
        private readonly ExampleService $service,
        private readonly ExampleController $controller,
    ) {}

    /**
     * Register the feature hooks.
     */
    public function register(): void
    {
        // Admin hooks
        if (is_admin()) {
            $this->addAction('admin_menu', [$this, 'registerAdminMenu']);
            $this->addAction('admin_enqueue_scripts', [$this, 'enqueueAdminAssets']);
        }

        // Frontend hooks
        $this->addAction('init', [$this, 'registerShortcodes']);
        $this->addAction('wp_enqueue_scripts', [$this, 'enqueueFrontendAssets']);

        // REST API hooks
        $this->addAction('rest_api_init', [$this->controller, 'registerRoutes']);

        // Custom hooks for event-driven communication
        $this->addAction('wp_starter_plugin_example_created', [$this, 'onExampleCreated'], 10, 2);
    }

    /**
     * Register admin menu pages.
     */
    public function registerAdminMenu(): void
    {
        add_menu_page(
            __('Example Feature', 'wp-starter-plugin'),
            __('Example', 'wp-starter-plugin'),
            'manage_wp_starter_plugin',
            'wp-starter-plugin-example',
            [ExampleAdminPage::class, 'render'],
            'dashicons-admin-generic',
            30
        );
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook The current admin page hook.
     */
    public function enqueueAdminAssets(string $hook): void
    {
        if (! str_contains($hook, 'wp-starter-plugin')) {
            return;
        }

        wp_enqueue_style(
            'wp-starter-plugin-admin',
            WP_STARTER_PLUGIN_URL . 'assets/css/admin.css',
            [],
            WP_STARTER_PLUGIN_VERSION
        );

        wp_enqueue_script(
            'wp-starter-plugin-admin',
            WP_STARTER_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            WP_STARTER_PLUGIN_VERSION,
            true
        );

        wp_localize_script('wp-starter-plugin-admin', 'wpStarterPlugin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('wp-starter-plugin/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    /**
     * Register shortcodes.
     */
    public function registerShortcodes(): void
    {
        add_shortcode('wp_starter_example', [ExampleShortcode::class, 'render']);
    }

    /**
     * Enqueue frontend assets.
     */
    public function enqueueFrontendAssets(): void
    {
        if (! is_singular() || ! has_shortcode(get_post()->post_content ?? '', 'wp_starter_example')) {
            return;
        }

        wp_enqueue_style(
            'wp-starter-plugin-frontend',
            WP_STARTER_PLUGIN_URL . 'assets/css/frontend.css',
            [],
            WP_STARTER_PLUGIN_VERSION
        );

        wp_enqueue_script(
            'wp-starter-plugin-frontend',
            WP_STARTER_PLUGIN_URL . 'assets/js/frontend.js',
            [],
            WP_STARTER_PLUGIN_VERSION,
            true
        );
    }

    /**
     * Handle example created event.
     *
     * This demonstrates event-driven communication between features.
     *
     * @param Data\ExampleDTO $dto The example DTO.
     * @param int             $id  The created example ID.
     */
    public function onExampleCreated(Data\ExampleDTO $dto, int $id): void
    {
        // Handle the event - e.g., log, notify, etc.
        do_action('wp_starter_plugin_log', 'Example created: ' . $id);
    }
}
