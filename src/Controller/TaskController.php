<?php
namespace App\Controller;

use App\Entity\Columns;
use App\Entity\Tasks;
use App\Form\TaskType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskController extends AbstractFOSRestController
{

    /**
     *Lists all Tasks.
     * @Rest\Get("/tasks")
     *
     * @return Response
     */
    public function getTasksAction()
    {
        $repository = $this->getDoctrine()->getRepository(Tasks::class);
        $Task = $repository->findall();
        return $this->handleView($this->view($Task));
    }

    /**
     * Lists one Task by id
     * @Rest\Get("/task/{id}")
     *
     * @return Response
     *
     */
    public function getTaskByIdAction(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Tasks::class);
        $task = $repository->find($id);

        if (empty($task)) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($task));
    }

    /**
     * Creates Task.
     * @Rest\Post("/task")
     *
     * @return Response
     */
    public function postTaskAction(Request $request)
    {
        $task = new Tasks();
        $form = $this->createForm(TaskType::class, $task);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Replaces Task to Column
     * @Rest\Put("/move/{taskId}/to/{columnId}")
     */
    public function changeTaskColumn($taskId, $columnId)
    {
        $tRepository = $this->getDoctrine()->getRepository(Tasks::class);
        $cRepository = $this->getDoctrine()->getRepository(Columns::class);
        $task = $tRepository->find($taskId);
        $column = $cRepository->find($columnId);

        if (empty($task)) {
            throw new HttpException(401, 'Task id not found');
        }

        if (empty($column)) {
            throw new HttpException(401, 'Column id not found');
        }

        $task->setColumns($column);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->handleView($this->view($task));
    }

    /**
     * Changes Task content
     * @Rest\Put("/task/{id}")
     */
    public function changeTaskContent(int $id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Tasks::class);
        $task = $repository->find($id);

        if (empty($task)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TaskType::class, $task);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_OK));
        }
        
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Removes Task by id.
     * @Rest\Delete("/task/{id}")
     *
     * @return Response
     */
    public function deleteTaskAction(int $id)
    {
        $response = new Response();
        $repository = $this->getDoctrine()->getRepository(Tasks::class);
        $task = $repository->find($id);

        if (empty($task)) {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $response->setContent('Task removed!');
    }
}
