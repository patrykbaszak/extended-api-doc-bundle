<?php

declare(strict_types=1);

namespace PBaszak\ExtendedApiDoc\Tests\E2e;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

#[Group('e2e')]
class ControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        parent::setUp();
    }

    #[Test]
    public function shouldReturn200WhenGetRequestToApiDoc(): void
    {
        $this->client->request('GET', '/api/doc');

        self::assertResponseIsSuccessful();
    }

    #[Test]
    public function shouldReturn200WhenGetRequestToApiDocJson(): void
    {
        $this->client->request('GET', '/api/doc.json');

        $response = $this->client->getResponse();

        self::assertResponseIsSuccessful();
        self::assertJson($response->getContent());

        $json = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('openapi', $json);
        self::assertArrayHasKey('/api/status', $json['paths']);
    }

    #[Test]
    public function shouldReturn200WhenGetRequestToApiDocYaml(): void
    {
        $this->client->request('GET', '/api/doc.yaml');

        $response = $this->client->getResponse();

        self::assertResponseIsSuccessful();
        self::assertStringContainsString('openapi: 3.0.0', $response->getContent());
        self::assertStringContainsString('/api/status:', $response->getContent());
    }
}
