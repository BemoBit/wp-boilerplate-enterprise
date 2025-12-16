<?php
/**
 * Main Plugin Bootstrapper.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Core;

use DI\Container;
use DI\ContainerBuilder;
use Starter\Shared\Contracts\ServiceProviderInterface;

/**
 * Class Plugin
 *
 * Main plugin bootstrapper that initializes the DI container
 * and registers all service providers.
 */
final class Plugin
{
    /**
     * DI Container instance.
     */
    private Container $container;

    /**
     * List of service providers to register.
     *
     * @var array<class-string<ServiceProviderInterface>>
     */
    private array $providers = [
        \Starter\Features\Example\ExampleProvider::class,
    ];

    /**
     * Boot the plugin.
     *
     * Builds the DI container and schedules provider registration.
     */
    public function boot(): void
    {
        $this->buildContainer();
        $this->registerProviders();
        $this->loadTextDomain();
    }

    /**
     * Build the DI Container.
     */
    private function buildContainer(): void
    {
        $builder = new ContainerBuilder();

        $configPath = WP_STARTER_PLUGIN_DIR . 'config/container.php';
        if (file_exists($configPath)) {
            $builder->addDefinitions($configPath);
        }

        $this->container = $builder->build();
    }

    /**
     * Register all service providers.
     */
    private function registerProviders(): void
    {
        foreach ($this->providers as $providerClass) {
            /** @var ServiceProviderInterface $provider */
            $provider = $this->container->get($providerClass);
            $provider->register();
        }
    }

    /**
     * Load plugin text domain for translations.
     */
    private function loadTextDomain(): void
    {
        load_plugin_textdomain(
            'wp-starter-plugin',
            false,
            dirname(WP_STARTER_PLUGIN_BASENAME) . '/languages'
        );
    }

    /**
     * Get the DI container instance.
     */
    public function getContainer(): Container
    {
        return $this->container;
    }
}
