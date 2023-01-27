<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    /**
     * @Route("/messagerie",name="/messagerie")
     */
    public function index(): Response
    {
        return $this->render('messagerie.html.twig',['title' => "messagerie"]);
    }
}