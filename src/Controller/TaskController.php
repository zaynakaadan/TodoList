<?php

namespace App\Controller;

use App\Entity\Task;
use AppBundle\Form\TaskType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends  AbstractController
{
    private $entityManager; 

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Inject the EntityManager
    }

    #[Route('/tasks', name: 'task_list')]
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->entityManager->getRepository('AppBundle:Task')->findAll()]);
    }

    
    #[Route('/tasks/create', name:'task_create')]
     
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->entityManager;

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    
      #[Route('/tasks/{id}/edit', name:'task_edit')]
     
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

   
      #[Route('/tasks/{id}/toggle', name:'task_toggle')]
     
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    
    #[Route('/tasks/{id}/delete', name:'task_delete')]
     
    public function deleteTaskAction(Task $task)
    {
        $em = $this->entityManager;
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
