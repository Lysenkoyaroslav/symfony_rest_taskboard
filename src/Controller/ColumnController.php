<?php

namespace App\Controller;

use App\Entity\Columns;
use App\Form\ColumnType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class ColumnController extends AbstractFOSRestController
{
    /**
     *Lists all Columns.
     * @Rest\Get("/columns")
     *
     * @return Response
     */
    public function getColumnAction()
    {
        $repository = $this->getDoctrine()->getRepository(Columns::class);
        $column = $repository->findall();
        return $this->handleView($this->view($column));

    }

    /**
     * Lists one Column by id
     * @Rest\Get("/column/{id}")
     *
     * @return Response
     *
     */
    public function getColumnByIdAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Columns::class);
        $column = $repository->find($id);

        if (empty($column)) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($column));
    }

    /**
     * Changes Column name
     * @Rest\Put("/column/{id}")
     */

    public function changeColumn($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Columns::class);
        $column = $repository->find($id);

        if (empty($column)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(ColumnType::class, $column);
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
     * Creates Column.
     * @Rest\Post("/column")
     *
     * @return Response
     */
    public function postColumnAction(Request $request)
    {
        $column = new Columns();
        $form = $this->createForm(ColumnType::class, $column);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($column);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Removes Column by id.
     * @Rest\Delete("/column/{id}")
     *
     * @return Response
     */
    public function deleteColumnAction($id)
    {
        $response = new Response();

        $repository = $this->getDoctrine()->getRepository(Columns::class);
        $column = $repository->find($id);

        if (empty($column)) {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($column);
        $em->flush();

        return $response->setContent('Column removed!');
    }

}