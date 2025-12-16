<?php
/**
 * Example REST API Controller.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Features\Example\Api;

use Starter\Features\Example\Data\ExampleDTO;
use Starter\Features\Example\Services\ExampleService;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;

/**
 * Class ExampleController
 *
 * REST API controller for Example resources.
 */
final class ExampleController extends WP_REST_Controller
{
    /**
     * The namespace for this controller's routes.
     *
     * @var string
     */
    protected $namespace = 'wp-starter-plugin/v1';

    /**
     * The base of this controller's routes.
     *
     * @var string
     */
    protected $rest_base = 'examples';

    /**
     * Constructor.
     *
     * @param ExampleService $service The example service.
     */
    public function __construct(
        private readonly ExampleService $service,
    ) {}

    /**
     * Register the routes for the controller.
     */
    public function registerRoutes(): void
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getItems'],
                    'permission_callback' => [$this, 'getItemsPermissions'],
                    'args' => $this->getCollectionParams(),
                ],
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'createItem'],
                    'permission_callback' => [$this, 'createItemPermissions'],
                    'args' => $this->getEndpointArgsForItemSchema(WP_REST_Server::CREATABLE),
                ],
                'schema' => [$this, 'getPublicItemSchema'],
            ]
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                [
                    'methods' => WP_REST_Server::READABLE,
                    'callback' => [$this, 'getItem'],
                    'permission_callback' => [$this, 'getItemPermissions'],
                    'args' => [
                        'id' => [
                            'description' => __('Unique identifier for the example.', 'wp-starter-plugin'),
                            'type' => 'integer',
                            'required' => true,
                        ],
                    ],
                ],
                [
                    'methods' => WP_REST_Server::EDITABLE,
                    'callback' => [$this, 'updateItem'],
                    'permission_callback' => [$this, 'updateItemPermissions'],
                    'args' => $this->getEndpointArgsForItemSchema(WP_REST_Server::EDITABLE),
                ],
                [
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => [$this, 'deleteItem'],
                    'permission_callback' => [$this, 'deleteItemPermissions'],
                ],
                'schema' => [$this, 'getPublicItemSchema'],
            ]
        );
    }

    /**
     * Check if a given request has access to get items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function getItemsPermissions(WP_REST_Request $request): bool|WP_Error
    {
        return current_user_can('read');
    }

    /**
     * Check if a given request has access to get a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error True if the request has read access, WP_Error object otherwise.
     */
    public function getItemPermissions(WP_REST_Request $request): bool|WP_Error
    {
        return $this->getItemsPermissions($request);
    }

    /**
     * Check if a given request has access to create items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error True if the request has create access, WP_Error object otherwise.
     */
    public function createItemPermissions(WP_REST_Request $request): bool|WP_Error
    {
        return current_user_can('manage_wp_starter_plugin');
    }

    /**
     * Check if a given request has access to update a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error True if the request has update access, WP_Error object otherwise.
     */
    public function updateItemPermissions(WP_REST_Request $request): bool|WP_Error
    {
        return $this->createItemPermissions($request);
    }

    /**
     * Check if a given request has access to delete a specific item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return bool|WP_Error True if the request has delete access, WP_Error object otherwise.
     */
    public function deleteItemPermissions(WP_REST_Request $request): bool|WP_Error
    {
        return $this->createItemPermissions($request);
    }

    /**
     * Retrieve a collection of items.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function getItems($request): WP_REST_Response|WP_Error
    {
        $items = $this->service->getAll($request->get_params());

        $data = array_map(fn(ExampleDTO $item) => $this->prepareItemForResponse($item, $request), $items);

        return rest_ensure_response($data);
    }

    /**
     * Retrieve a single item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function getItem($request): WP_REST_Response|WP_Error
    {
        $id = (int) $request->get_param('id');
        $item = $this->service->get($id);

        if (! $item) {
            return new WP_Error(
                'rest_not_found',
                __('Example not found.', 'wp-starter-plugin'),
                ['status' => 404]
            );
        }

        return rest_ensure_response($this->prepareItemForResponse($item, $request));
    }

    /**
     * Create a single item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function createItem($request): WP_REST_Response|WP_Error
    {
        $dto = ExampleDTO::fromArray($request->get_params());
        $id = $this->service->create($dto);

        if (! $id) {
            return new WP_Error(
                'rest_cannot_create',
                __('Could not create example.', 'wp-starter-plugin'),
                ['status' => 500]
            );
        }

        $item = $this->service->get($id);
        $response = rest_ensure_response($this->prepareItemForResponse($item, $request));
        $response->set_status(201);

        return $response;
    }

    /**
     * Update a single item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function updateItem($request): WP_REST_Response|WP_Error
    {
        $id = (int) $request->get_param('id');
        $dto = ExampleDTO::fromArray(array_merge(['id' => $id], $request->get_params()));

        if (! $this->service->update($id, $dto)) {
            return new WP_Error(
                'rest_cannot_update',
                __('Could not update example.', 'wp-starter-plugin'),
                ['status' => 500]
            );
        }

        $item = $this->service->get($id);

        return rest_ensure_response($this->prepareItemForResponse($item, $request));
    }

    /**
     * Delete a single item.
     *
     * @param WP_REST_Request $request Full details about the request.
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function deleteItem($request): WP_REST_Response|WP_Error
    {
        $id = (int) $request->get_param('id');

        if (! $this->service->delete($id)) {
            return new WP_Error(
                'rest_cannot_delete',
                __('Could not delete example.', 'wp-starter-plugin'),
                ['status' => 500]
            );
        }

        return rest_ensure_response(['deleted' => true, 'id' => $id]);
    }

    /**
     * Prepare the item for the REST response.
     *
     * @param ExampleDTO|null $item    The item to prepare.
     * @param WP_REST_Request $request Request object.
     * @return array<string, mixed> The prepared item data.
     */
    private function prepareItemForResponse(?ExampleDTO $item, WP_REST_Request $request): array
    {
        if (! $item) {
            return [];
        }

        return $item->toArray();
    }

    /**
     * Get the item schema.
     *
     * @return array<string, mixed> The item schema.
     */
    public function get_item_schema(): array
    {
        return [
            '$schema' => 'http://json-schema.org/draft-04/schema#',
            'title' => 'example',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'description' => __('Unique identifier for the example.', 'wp-starter-plugin'),
                    'type' => 'integer',
                    'context' => ['view', 'edit'],
                    'readonly' => true,
                ],
                'title' => [
                    'description' => __('The title for the example.', 'wp-starter-plugin'),
                    'type' => 'string',
                    'context' => ['view', 'edit'],
                    'required' => true,
                ],
                'content' => [
                    'description' => __('The content for the example.', 'wp-starter-plugin'),
                    'type' => 'string',
                    'context' => ['view', 'edit'],
                ],
                'status' => [
                    'description' => __('The status of the example.', 'wp-starter-plugin'),
                    'type' => 'string',
                    'enum' => ['active', 'inactive', 'draft'],
                    'context' => ['view', 'edit'],
                    'default' => 'active',
                ],
            ],
        ];
    }
}
