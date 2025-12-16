<?php
/**
 * DI Container Configuration.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

use Starter\Features\Example\Api\ExampleController;
use Starter\Features\Example\ExampleProvider;
use Starter\Features\Example\Models\ExampleModel;
use Starter\Features\Example\Services\ExampleService;
use Starter\Infrastructure\Cache\CacheManager;
use Starter\Infrastructure\Http\HttpClient;
use Starter\Infrastructure\Queue\QueueManager;

use function DI\autowire;
use function DI\create;
use function DI\get;

return [
    // Infrastructure
    CacheManager::class => autowire(),
    HttpClient::class => autowire(),
    QueueManager::class => autowire(),

    // Example Feature
    ExampleModel::class => autowire(),
    ExampleService::class => autowire()
        ->constructorParameter('cache', get(CacheManager::class)),
    ExampleController::class => autowire()
        ->constructorParameter('service', get(ExampleService::class)),
    ExampleProvider::class => autowire()
        ->constructorParameter('service', get(ExampleService::class))
        ->constructorParameter('controller', get(ExampleController::class)),
];
