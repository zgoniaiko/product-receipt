<?php

namespace App\Tests\Api;

use App\Entity\Product;
use App\Tests\AbstractTest;

class ProductsTest extends AbstractTest
{
    public function testGetCollection(): void
    {
        $token = $this->getToken([
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $response = $this->createClientWithCredentials($token)->request('GET', '/api/products');

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
        $token = $this->getToken([
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $response = $this->createClientWithCredentials($token)->request('POST', '/api/products', ['json' => [
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
        $token = $this->getToken([
            'email' => 'admin@example.com',
            'password' => 'admin',
        ]);
        $this->createClientWithCredentials($token)->request('POST', '/api/products', ['json' => [
            'barcode' => 'invalid',
            'vatClass' => 20,
        ]]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: This value should not be blank.
vatClass: This value should be either 6 or 21.',
        ]);
    }

    public function testGetProduct(): void
    {
        $response = $this->createClientWithCredentials()->request('GET', '/api/products/0672201000037');

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products/0672201000037',
            '@type' => 'Product',
            'barcode' => '0672201000037',
            'name' => 'Jacobs Cronat Gold Instant Coffee 200g',
            'cost' => '12.02',
            'vatClass' => 6,
        ]);

        self::assertRegExp('~^/api/products/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Product::class);
    }

    /**
     * @dataProvider urlProvider
     */
    public function testAnonymousAccessRestricted(string $method, string $url): void
    {
        static::createClient()->request($method, $url, ['json' => [
        ]]);

        self::assertResponseStatusCodeSame(401);
    }

    public function urlProvider(): array
    {
        return [
            ['GET', '/api/products'],
            ['POST', '/api/products'],
            ['GET', '/api/products/0672201000037'],
        ];
    }
}
