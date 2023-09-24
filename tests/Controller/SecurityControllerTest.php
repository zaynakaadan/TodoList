<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    public function testLogoutCheck(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/logout');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}