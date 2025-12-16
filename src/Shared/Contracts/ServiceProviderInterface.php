<?php
/**
 * Service Provider Interface.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Shared\Contracts;

/**
 * Interface ServiceProviderInterface
 *
 * All service providers must implement this interface.
 */
interface ServiceProviderInterface
{
    /**
     * Register the service provider.
     *
     * This method should register all hooks, filters, and services
     * that the provider is responsible for.
     */
    public function register(): void;
}
