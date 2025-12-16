<?php
/**
 * Example Unit Test.
 *
 * @package WPStarterPlugin
 */

declare(strict_types=1);

namespace Starter\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Starter\Features\Example\Data\ExampleDTO;

/**
 * Class ExampleTest
 *
 * Example unit test to demonstrate testing structure.
 */
final class ExampleTest extends TestCase
{
    /**
     * Test that ExampleDTO can be created from array.
     */
    public function testExampleDtoFromArray(): void
    {
        $data = [
            'id' => 1,
            'title' => 'Test Title',
            'content' => 'Test Content',
            'status' => 'active',
        ];

        $dto = ExampleDTO::fromArray($data);

        $this->assertSame(1, $dto->id);
        $this->assertSame('Test Title', $dto->title);
        $this->assertSame('Test Content', $dto->content);
        $this->assertSame('active', $dto->status);
    }

    /**
     * Test that ExampleDTO can be converted to array.
     */
    public function testExampleDtoToArray(): void
    {
        $dto = new ExampleDTO(
            id: 1,
            title: 'Test Title',
            content: 'Test Content',
            status: 'active'
        );

        $array = $dto->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('content', $array);
        $this->assertArrayHasKey('status', $array);
    }

    /**
     * Test that ExampleDTO handles default values.
     */
    public function testExampleDtoDefaults(): void
    {
        $dto = ExampleDTO::fromArray([
            'id' => 1,
            'title' => 'Test',
        ]);

        $this->assertSame('', $dto->content);
        $this->assertSame('active', $dto->status);
        $this->assertNull($dto->createdAt);
        $this->assertNull($dto->updatedAt);
    }
}
