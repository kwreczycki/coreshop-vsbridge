<?php

namespace CoreShop2VueStorefrontBundle\Tests\Functional;

use Pimcore\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

abstract class ApiTestCase extends WebTestCase
{
    /**
     * @param string $username
     * @param string $password
     *
     * @return Client
     */
    public function createAuthenticatedClient(string $username, string $password)
    {
        $data = ['username' => $username, 'password' => $password];

        $authenticatedClient = static::createClient(['environment' => 'test']);

        $authenticatedClient->request('POST', '/vsbridge/user/login', $data);

        $tokenResponse = json_decode($authenticatedClient->getResponse()->getContent(), true);

        $authenticatedClient->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $tokenResponse['result']));

        return $authenticatedClient;
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @return Client
     */
    public function createCustomer(string $email, string $password)
    {
        $data = [
            "customer" => [
                "email" => $email,
                "firstname" => "John",
                "lastname" => "Kowalski"
            ],
            "password" => $password
        ];

        $client = static::createClient(['environment' => 'test']);

        $client->request('POST', '/vsbridge/user/create', $data);

        return $client;
    }

    public function expectedCustomer(): string
    {
        return file_get_contents(__DIR__ . '/Fixtures/Response/customer.json');
    }

    /**
     * @param $keys
     * @param $stack
     * @param string $message
     */
    protected function assertArrayHasKeys($keys, $stack, $message = '')
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $stack, $message);
        }
    }
}
