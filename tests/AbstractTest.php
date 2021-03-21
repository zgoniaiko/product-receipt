<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

abstract class AbstractTest extends ApiTestCase
{
    private $token;
    private $clientWithCredentials;

    use RefreshDatabaseTrait;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => [
            'content-type' => 'application/ld+json',
            'authorization' => 'Bearer ' . $token
        ]]);
    }

    /**
     * Use other credentials if needed.
     */
    protected function getToken($body = []): string
    {
        if ($this->token) {
            return $this->token;
        }

        $response = static::createClient([], ['headers' => ['content-type' => 'application/json']])
            ->request('POST', '/authentication_token', ['body' => $body ? json_encode($body) : json_encode([
            'email' => 'cash@example.com',
            'password' => 'cash',
        ])]);

        $data = json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }
}
