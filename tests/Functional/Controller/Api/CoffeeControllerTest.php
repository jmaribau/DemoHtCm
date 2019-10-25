<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\DataFixtures\AppDataFixtures;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 * @covers
 *
 * @internal
 */
class CoffeeControllerTest extends WebTestCase
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
        self::$dataFixtures = (new AppDataFixtures())->getCoffeeDataForTest();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', self::$token));
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
    }

    protected function tearDown(): void
    {
    }

    public function testGetCoffees(): void
    {
        $expected = array_values(self::$dataFixtures);
        $this->client->request('GET', '/api/coffees');
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertCount(count($expected), $data);
        $this->assertEquals($expected, $data);
    }

    public function testGetCoffee(): void
    {
        $index = 1;
        $expected = self::$dataFixtures[$index];
        $this->client->request('GET', '/api/coffees/'.$index);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($expected, $data);
    }

    public function testPostCoffee(): void
    {
        $expected = $send = self::$dataFixtures[2];

        $this->client->request('POST', '/api/coffees', $send);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $expected['id'] = count(self::$dataFixtures) + 1;

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($expected, $data);
    }

    public function testPutCoffee(): void
    {
        $index = 2;
        $modified = self::$dataFixtures[$index];
        $modified['name'] .= '_mod';

        $this->client->request('PUT', '/api/coffees/'.$index, $modified);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($modified, $data);
    }

    public function testDeleteCoffee(): void
    {
        $this->client->request('DELETE', '/api/coffees/5');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetCoffeeOrders(): void
    {
        $this->client->request('GET', '/api/coffees/2/orders');
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $data);
        $this->assertEquals(7, $data[0]['id'] + $data[1]['id']);
    }
}
