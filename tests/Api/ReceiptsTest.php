<?php

namespace App\Tests\Api;

use App\Entity\Receipt;
use App\Tests\AbstractTest;

class ReceiptsTest extends AbstractTest
{
    public function testCreateReceipt(): void
    {
        $response = $this->createClientWithCredentials()->request('POST', '/api/receipts', ['json' => [
            'status' => 'open',
        ]]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/Receipt',
            '@type' => 'Receipt',
            'status' => 'open',
        ]);
        self::assertRegExp('~^/api/receipts/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Receipt::class);
    }

    public function testCreateEmptyStatusReceipt(): void
    {
        $response = $this->createClientWithCredentials()->request('POST', '/api/receipts', ['json' => [
        ]]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
             '@context' => '/api/contexts/Receipt',
             '@type' => 'Receipt',
             'status' => 'open',
        ]);
        self::assertRegExp('~^/api/receipts/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Receipt::class);
    }

    public function testCreateInvalidReceipt(): void
    {
        $this->createClientWithCredentials()->request('POST', '/api/receipts', ['json' => [
            'status' => 'invalid',
        ]]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'status: This value should be either "open" or "fihished".',
        ]);
    }

    public function testReceiptAddProduct(): void
    {
        $response = $this->createClientWithCredentials()->request('PUT', '/api/receipts/2/add-product', ['json' => [
            'barcode' => '0026102689783',
        ]]);

        self::assertResponseStatusCodeSame(200);

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
             '@context' => '/api/contexts/Receipt',
             '@type' => 'Receipt',
             'status' => 'open',
        ]);
        self::assertRegExp('~^/api/receipts/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Receipt::class);
    }

    public function testReceiptProductAmount(): void
    {
        $response = $this->createClientWithCredentials()->request('PUT', '/api/receipts/2/product-amount', ['json' => [
            'amount' => '3',
        ]]);

        self::assertResponseStatusCodeSame(200);

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
             '@context' => '/api/contexts/Receipt',
             '@type' => 'Receipt',
             'status' => 'open',
        ]);
        self::assertRegExp('~^/api/receipts/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Receipt::class);
    }

    public function testFihishReceipt(): void
    {
        $response = $this->createClientWithCredentials()->request('PUT', '/api/receipts/2/finish', ['json' => [
        ]]);

        self::assertResponseStatusCodeSame(200);

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
             '@context' => '/api/contexts/Receipt',
             '@type' => 'Receipt',
             'status' => 'finished',
        ]);
        self::assertRegExp('~^/api/receipts/\d+$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Receipt::class);
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
           ['POST', '/api/receipts'],
           ['GET', '/api/receipts/2'],
           ['PUT', '/api/receipts/2/add-product'],
           ['PUT', '/api/receipts/2/product-amount'],
           ['PUT', '/api/receipts/2/finish'],
       ];
    }
}