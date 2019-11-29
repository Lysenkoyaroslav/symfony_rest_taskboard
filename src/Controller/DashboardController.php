<?php

namespace App\Controller;

use App\Entity\Dashboard;
use App\Form\DashboardType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

class DashboardController extends AbstractFOSRestController
{
    /**
     *Lists all Dashboards.
     * @Rest\Get("/dashboards")
     *
     * @return Response
     */
    public function getDashboardAction()
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $dashboard = $repository->findall();
        return $this->handleView($this->view($dashboard));

    }

    /**
     * Lists one Dashboard by id
     * @Rest\Get("/dashboard/{id}")
     *
     * @return Response
     *
     */
    public function getDashboardByIdAction($id)
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $dashboard = $repository->find($id);

        if (empty($dashboard)) {
            throw new NotFoundHttpException();
        }

        return $this->handleView($this->view($dashboard));
    }

    /**
     * Changes Dashboard content
     * @Rest\Put("/dashboard/{id}")
     */

    public function changeDashboardContent($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $dashboard = $repository->find($id);

        if (empty($dashboard)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(DashboardType::class, $dashboard);
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
     * Creates Dashboard.
     * @Rest\Post("/dashboard")
     *
     * @return Response
     */
    public function postDashboardAction(Request $request)
    {
        $dashboard = new Dashboard();
        $form = $this->createForm(DashboardType::class, $dashboard);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($dashboard);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }

    /**
     * Removes Dashboard by id.
     * @Rest\Delete("/dashboard/{id}")
     *
     * @return Response
     */
    public function deleteDashboardAction($id)
    {
        $response = new Response();

        $repository = $this->getDoctrine()->getRepository(Dashboard::class);
        $dashboard = $repository->find($id);

        if (empty($dashboard)) {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($dashboard);
        $em->flush();

        return $response->setContent('Dashboard removed!');
    }

}