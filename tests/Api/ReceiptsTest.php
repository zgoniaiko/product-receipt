<?php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Receipt;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class ReceiptsTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testCreateReceipt(): void
    {
        $response = static::createClient()->request('POST', '/api/receipts', ['json' => [
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
        $response = static::createClient()->request('POST', '/api/receipts', ['json' => [
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
        static::createClient()->request('POST', '/api/receipts', ['json' => [
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

    public function testFihishReceipt(): void
    {
        $response = static::createClient()->request('PUT', '/api/receipts/2/finish', ['json' => [
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

}