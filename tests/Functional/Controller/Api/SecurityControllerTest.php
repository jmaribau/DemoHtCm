<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\DataFixtures\AppDataFixtures;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group functional
 * @covers
 *
 * @internal
 */
class SecurityControllerTest extends WebTestCase
{
    /**
     * @var array
     */
    private static $userDataFixtures;
    /**
     * @var array
     */
    private static $orderDataFixtures;
    /**
     * @var array
     */
    private static $coffeeDataFixtures;

    /**
     * @var KernelBrowser
     */
    private $client;
    /**
     * @var string
     */
    private static $tokenRoleUser;
    /**
     * @var string
     */
    private static $tokenRoleAdmin;

    /**
     * @throws JWTEncodeFailureException
     */
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        /** @var JWTEncoderInterface $jwtEncode */
        $jwtEncode = static::$container->get('lexik_jwt_authentication.encoder');
        self::$tokenRoleUser = $jwtEncode->encode(['username' => 'customer_1']);
        self::$tokenRoleAdmin = $jwtEncode->encode(['username' => 'admin']);
    }

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_Content-Type', 'application/json');
    }

    /**
     * @dataProvider providerRoleUser
     *
     * @param string $url
     * @param string $method
     * @param int    $status
     * @param array  $data
     */
    public function testRoleUserSecurity(string $url, string $method, int $status, array $data = []): void
    {
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', self::$tokenRoleUser));
        $this->client->request($method, $url, $data);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider providerRoleAdmin
     *
     * @param string $url
     * @param string $method
     * @param int    $status
     * @param array  $body
     */
    public function jmtestSRoleAdminSecurity(string $url, string $method, int $status, array $body = []): void
    {
        $this->client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', self::$tokenRoleAdmin));
        $this->client->request($method, $url, $body);
        $this->assertEquals($status, $this->client->getResponse()->getStatusCode());
    }

    /**
     * @return array
     */
    public function providerUrl(): array
    {
        $appDataFixtures = new AppDataFixtures();
        self::$userDataFixtures = $appDataFixtures->getUserData();
        self::$orderDataFixtures = $appDataFixtures->getOrderData();
        self::$coffeeDataFixtures = $appDataFixtures->getCoffeeData();

        $user = self::$userDataFixtures[2];
        $user['username'] .= '_new';

        return [
            'GET coffees' => ['url' => 'api/coffees', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'GET coffee' => ['url' => 'api/coffees/2', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'POST coffees' => ['url' => 'api/coffees', 'method' => 'POST', 'status' => Response::HTTP_CREATED,
                'body' => self::$coffeeDataFixtures[2], ],
            'PUT coffees' => ['url' => 'api/coffees/2', 'method' => 'PUT', 'status' => Response::HTTP_OK,
                'body' => self::$coffeeDataFixtures[2], ],
            'DELETE coffee' => ['url' => 'api/coffees/5', 'method' => 'DELETE', 'status' => Response::HTTP_NO_CONTENT],
            'GET coffee orders' => ['url' => 'api/coffees/2/orders', 'method' => 'GET', 'status' => Response::HTTP_OK],

            'GET orders' => ['url' => 'api/orders', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'GET order' => ['url' => 'api/orders/2', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'POST orders' => ['url' => 'api/orders', 'method' => 'POST', 'status' => Response::HTTP_CREATED,
                'body' => self::$orderDataFixtures[2], ],
            'PUT orders' => ['url' => 'api/orders/2', 'method' => 'PUT', 'status' => Response::HTTP_OK,
                'body' => self::$orderDataFixtures[2], ],
            'DELETE order' => ['url' => 'api/orders/5', 'method' => 'DELETE', 'status' => Response::HTTP_NO_CONTENT],

            'GET users' => ['url' => 'api/users', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'GET user' => ['url' => 'api/users/2', 'method' => 'GET', 'status' => Response::HTTP_OK],
            'POST users' => ['url' => 'api/users', 'method' => 'POST', 'status' => Response::HTTP_CREATED,
                'body' => $user, ],
            'PUT users' => ['url' => 'api/users/2', 'method' => 'PUT', 'status' => Response::HTTP_OK,
                'body' => $user, ],
            'DELETE user' => ['url' => 'api/users/5', 'method' => 'DELETE', 'status' => Response::HTTP_NO_CONTENT],
            'GET user orders' => ['url' => 'api/users/4/orders', 'method' => 'GET', 'status' => Response::HTTP_OK],
        ];
    }

    /**
     * @return array
     */
    public function providerRoleUser(): array
    {
        $urls = array_map(static function ($var) {
            $var['status'] = Response::HTTP_FORBIDDEN;

            return $var;
        }, $this->providerUrl());

        $urls['GET coffees']['status'] = Response::HTTP_OK;
        $urls['GET coffee']['status'] = Response::HTTP_OK;
        $urls['POST orders']['status'] = Response::HTTP_CREATED;

        return $urls;
    }

    /**
     * @return array
     */
    public function providerRoleAdmin(): array
    {
        return $this->providerUrl();
    }
}
