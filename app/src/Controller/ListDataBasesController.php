<?php

namespace App\Controller;

use App\Form\ListDatabaseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListDataBasesController extends AbstractController
{
    /**
     * @Route("/list/data/bases", name="app_list_data_bases")
     */
    public function index(): Response
    {
        $form = $this->createForm(ListDatabaseFormType::class);

        return $this->render(
            'list.html.twig',
            [ 'mainForm' => $form->createView()]
        );
    }
}
