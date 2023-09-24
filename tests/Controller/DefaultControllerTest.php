<?php 

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    private $client;
    private $urlGenerator;
    private $userRepository;
    private $user;
    
    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('admin@todolist.com');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->client->loginUser($this->user);
    }

    public function testIndexAction(): void
    {
        $this->client->request(Request::METHOD_GET, $this->client->getContainer()->get('router')->generate('task_list'));
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
} 