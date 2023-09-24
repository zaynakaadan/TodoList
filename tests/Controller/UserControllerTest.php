<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $urlGenerator;
    private $userRepository;
    private $user;

    public function setUp(): void

    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);

        // Debugging: Output the user count for testing
        $userCount = $this->userRepository->count([]);
        var_dump($userCount); // Check if userCount is greater than 0
        
        $this->user = $this->userRepository->findOneByEmail('admin@todolist.com');
        if ($this->user) {
        echo "User found: " . $this->user->getEmail();
        } else {
            echo "User not found!";
        }
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        //Authentication on Symfony for the test with the user retrieved from the database
        $this->client->loginUser($this->user);
    }
    public function testListAction()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCreateAction(): void
    {
        // Generate a unique username
        $uniqueUsername = 'test_' . uniqid();
        // Generate a unique email address
        $uniqueEmail = 'admin_' . uniqid() . '@todolist.com';

        // Create an admin user
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));  
        //dump($crawler->html());     
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $uniqueUsername;
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = $uniqueEmail;
        $form['user[roles]'] = 'ROLE_ADMIN'; 
        //dump($form->getValues());
        // Add form submission 
        $this->client->submit($form);
        //dump($this->client->getResponse()->getContent());
        //dump($this->client->getResponse()->headers->get('location'));
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //dump($this->client->getResponse()->getContent());
        // Check if the admin user was created and assigned 'ROLE_ADMIN'
        $this->user = $this->userRepository->findOneByEmail('admin@todolist.com');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);

        // Generate a unique username
        $uniqueUsername = 'test_' . uniqid();
        // Generate a unique email address
        $uniqueEmail = 'user_' . uniqid() . '@todolist.com';
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $form = $crawler->selectButton('Ajouter')->form();
        $form['user[username]'] = $uniqueUsername;
        $form['user[password][first]'] = 'password';
        $form['user[password][second]'] = 'password';
        $form['user[email]'] = $uniqueEmail;
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditAction()
    {
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', array('id' => 69)));
        $form = $crawler->selectButton('Modifier')->form();
        $form['user[username]'] = 'Amodifier'. uniqid();
        $form['user[password][first]'] = 'newpassword';
        $form['user[password][second]'] = 'newpassword';
        $form['user[email]'] = 'amodifier_' . uniqid() . '@gmail.com';
        $form['user[roles]'] = 'ROLE_ADMIN';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }


}