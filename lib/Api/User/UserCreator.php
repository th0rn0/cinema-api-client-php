<?php

namespace DCG\Cinema\Api\User;

use DCG\Cinema\Request\ClientInterface;
use DCG\Cinema\Model\User;

class UserCreator
{
    private $client;
    private $userFactory;

    public function __construct(
        ClientInterface $client,
        UserFactory $userFactory
    ) {
        $this->client = $client;
        $this->userFactory = $userFactory;
    }

    /**
     * @param array $data
     * @return User
     * @throws \Exception
     */
    public function createUser($data)
    {
        $clientResponse = $this->client->postUnauthenticated('users', json_encode($data));
        return $this->userFactory->createFromClientResponseData($clientResponse->getData());
    }
}
