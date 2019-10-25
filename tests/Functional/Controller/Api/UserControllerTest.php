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
class UserControllerTest extends WebTestCase
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

    /**
     * @var array
     */
    private static $dataFixturesTest;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        /** @var JWTEncoderInterface $jwtEncode */
        $jwtEncode = static::$container->get('lexik_jwt_authentication.encoder');
        self::$token = $jwtEncode->encode(['username' => 'admin']);
        self::$dataFixtures = (new AppDataFixtures())->getUserData();
        self::$dataFixturesTest = (new AppDataFixtures())->getUserDataForTest();
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', self::$token));
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
    }

    public function testGetUsers(): void
    {
        $expected = array_values(self::$dataFixturesTest);
        $this->client->request('GET', '/api/users');

        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertCount(count(self::$dataFixturesTest), $data);
        $this->assertEquals($expected, $data);
    }

    public function testGetUser(): void
    {
        $index = 2;
        $expected = self::$dataFixturesTest[2];
        $this->client->request('GET', '/api/users/'.$index);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($expected, $data);
    }

    public function testPostUser(): void
    {
        $user = self::$dataFixtures[2];
        $user['username'] .= '_new';

        $this->client->request('POST', '/api/users', $user);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $user['id'] = count(self::$dataFixturesTest) + 1;
        unset($user['password']);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($user, $data);
    }

    public function testPutUser(): void
    {
        $index = 2;
        $user = self::$dataFixtures[$index];
        $user['username'] .= '_mod';

        $this->client->request('PUT', '/api/users/'.$index, $user);
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        unset($user['password']);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $this->assertEquals($user, $data);
    }

    public function testDeleteUser(): void
    {
        $this->client->request('DELETE', '/api/users/5');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testGetUserOrders(): void
    {
        $this->client->request('GET', '/api/users/4/orders');
        $response = $this->client->getResponse();
        /** @var string $content */
        $content = $response->getContent();
        $data = json_decode($content, true);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertCount(2, $data);
        $this->assertEquals(9, $data[0]['id'] + $data[1]['id']);
    }
}
