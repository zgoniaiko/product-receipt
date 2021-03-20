<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Product;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ProductsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testGetCollection(): void
    {
        $response = static::createClient()->request('GET', '/api/products');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
              '@context' => '/api/contexts/Product',
              '@id' => '/api/products',
              '@type' => 'hydra:Collection',
              'hydra:totalItems' => 3,
          ]);

        self::assertCount(3, $response->toArray()['hydra:member']);
        self::assertMatchesResourceCollectionJsonSchema(Product::class);
    }

    public function testCreateProduct(): void
    {
        $response = static::createClient()->request('POST', '/api/products', ['json' => [
            'barcode' => '0123456789123',
            'name' => 'Test product',
            'cost' => '10.01',
            'vatClass' => 21,
        ]]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@type' => 'Product',
            'barcode' => '0123456789123',
            'name' => 'Test product',
            'cost' => '10.01',
            'vatClass' => 21,
        ]);
        self::assertRegExp('~^/api/products/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Product::class);
    }

    public function testCreateInvalidProduct(): void
    {
        static::createClient()->request('POST', '/api/products', ['json' => [
            'barcode' => 'invalid',
            'vatClass' => 20,
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This value should not be blank.
vatClass: This value should be either 6 or 21.',
        ]);
    }
}