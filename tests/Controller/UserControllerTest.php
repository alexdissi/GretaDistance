<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testRegistrationsPerDay(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/registrationsday');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testSearchUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/search', ['search' => 'John']);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testNew(): void
    {
        $client = static::createClient();
        $client->request('GET', '/user/new');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
