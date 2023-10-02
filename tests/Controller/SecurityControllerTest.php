<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class SecurityControllerTest extends WebTestCase
{
    public function testHomepageLoginWithAdmin(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'Administrateur',
            '_password' => 'password'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
    }

    public function testLoginWithInvalidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'xxxx@xxxx.com',
            '_password' => 'incorrectpassword'
        ]);
        $client->submit($form);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testLogoutCheck(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/logout');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
   
}