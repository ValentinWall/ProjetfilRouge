<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivraisonController extends AbstractController
{
    /**
     * @Route("/livraison",name="/livraison")
     */
    public function index(): Response
    {
        return $this->render('livraison.html.twig',['title' => "livraison"]);
    }
}