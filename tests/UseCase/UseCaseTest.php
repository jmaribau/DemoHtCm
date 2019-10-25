<?php

declare(strict_types=1);

namespace App\Tests\UseCase;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Tests\Integration\Manager\Logger\OrderLoggerTest;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group usecase
 * @covers
 *
 * @internal
 */
class UseCaseTest extends WebTestCase
{
    /**
     * @var JWTEncoderInterface
     */
    private $authenticationEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    protected function setUp(): void
    {
        static::bootKernel();
        /** @var JWTEncoderInterface $jwtEncode */
        $jwtEncode = static::$container->get('lexik_jwt_authentication.encoder');
        $this->authenticationEncoder = $jwtEncode;
        /** @var EntityManager $entityManager */
        $entityManager = static::$container->get('doctrine');
        /** @var UserRepository $repository */
        $repository = $entityManager->getRepository(User::class);
        $this->userRepository = $repository;
        file_put_contents(OrderLoggerTest::LOG_FILE_PATH, null);
    }

    public function testUseCase(): void
    {
        // Admin create a new Coffee
        /** @var User $admin */
        $admin = $this->userRepository->findOneBy(['username' => 'admin']);
        $clientAdmin = $this->createClientUser($admin);
        $newCoffeeRequest = [
            'name' => 'ristretto',
            'intensity' => 10,
            'price' => 3,
            'stock' => 20,
        ];
        $clientAdmin->request('POST', '/api/coffees', $newCoffeeRequest);
        $responseAdmin = $clientAdmin->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $responseAdmin->getStatusCode());

        // Id of NewCoffee
        /** @var string $contentAdmin */
        $contentAdmin = $responseAdmin->getContent();
        $newCoffee = json_decode($contentAdmin, true);

        // Customer1 buys 3 units of coffee 1
        /** @var User $customer1 */
        $customer1 = $this->userRepository->findOneBy(['username' => 'customer_1']);
        $clientCustomer1 = $this->createClientUser($customer1);
        $orderCustomer1 = [
            'user' => $customer1->getId(),
            'coffee' => $newCoffee['id'],
            'quantity' => 3,
            'amount' => $newCoffee['price'] * 3,
        ];
        $clientCustomer1->request('POST', '/api/orders', $orderCustomer1);

        // Customer2 buys 10 units of coffee 1
        /** @var User $customer2 */
        $customer2 = $this->userRepository->findOneBy(['username' => 'customer_2']);
        $clientCustomer2 = $this->createClientUser($customer2);
        $orderCustomer2 = [
            'user' => $customer2->getId(),
            'coffee' => $newCoffee['id'],
            'quantity' => 10,
            'amount' => $newCoffee['price'] * 10,
        ];
        $clientCustomer2->request('POST', '/api/orders', $orderCustomer2);
        $responseCustomer2 = $clientCustomer2->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $responseCustomer2->getStatusCode());

        // Customer3 buys 10 units of coffee 1
        /** @var User $customer3 */
        $customer3 = $this->userRepository->findOneBy(['username' => 'customer_3']);
        $clientCustomer3 = $this->createClientUser($customer3);
        $orderCustomer3 = [
            'user' => $customer1->getId(),
            'coffee' => $newCoffee['id'],
            'quantity' => 10,
            'amount' => $newCoffee['price'] * 10,
        ];
        $clientCustomer3->request('POST', '/api/orders', $orderCustomer3);
        $responseCustomer3 = $clientCustomer3->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $responseCustomer3->getStatusCode());
    }

    private function createClientUser(User $user): Client
    {
        $token = $this->authenticationEncoder->encode(['username' => $user->getUsername()]);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
        $client->setServerParameter('HTTP_Content-Type', 'application/json');

        return $client;
    }
}
