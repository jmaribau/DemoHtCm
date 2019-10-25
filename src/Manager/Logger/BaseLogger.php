<?php

declare(strict_types=1);

namespace App\Manager\Logger;

use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BaseLogger.
 */
class BaseLogger
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var UserInterface|null
     */
    protected $user;

    /**
     * LoggerManager constructor.
     *
     * @param LoggerInterface     $logger
     * @param SerializerInterface $serializer
     * @param Security            $security
     */
    public function __construct(LoggerInterface $logger, SerializerInterface $serializer, Security $security)
    {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->user = $security->getUser();
    }
}
