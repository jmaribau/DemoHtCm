<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\DataFixtures\AppDataFixtures;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Client as Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 * @covers
 *
 * @internal
 */
class OrderControllerTest extends WebTestCase
{
    /** @var Client */
    protected $client;

    /**
     * @var string
     */
    private static $token;

    /**
     * @var array
     */
    private static $dataFixtures;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        /** @var JWTEncoderInterface $jwtEncode */
        $jwtEncode = static::$container->get('lexik_jwt_authentication.encoder');
        self::$token = $jwtEncode->encode(['username' => 'admin']);
        self::$dataFixtures = (new AppDataFixtures())->getOrderDataForTest();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', self::$token));
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
    }

    public function testGetOrders(): void
    {
        $expected = array_values(self::$dataFixtures);
        $this->client->request('GET', '/api/orders');

        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertCount(count($expected), $data);
        $this->assertEquals($expected, $data);
    }

    public function testGetOrder(): void
    {
        $index = 2;
        $expected = self::$dataFixtures[$index];
        $this->client->request('GET', '/api/orders/'.$index);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($expected, $data);
    }

    public function testPostOrder(): void
    {
        $expected = self::$dataFixtures[2];

        $this->client->request('POST', '/api/orders', $expected);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $expected['id'] = count(self::$dataFixtures) + 1;

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($expected, $data);
    }

    public function testPutOrder(): void
    {
        $index = 2;
        $modified = self::$dataFixtures[$index];
        $modified['quantity'] = 123;

        $this->client->request('PUT', '/api/orders/'.$index, $modified);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($modified, $data);
    }

    public function testDeleteOrder(): void
    {
        $this->client->request('DELETE', '/api/orders/4');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
