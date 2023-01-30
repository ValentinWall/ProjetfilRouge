<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    /**
     * @Route("/",name="/accueil")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('accueil.html.twig',[
            'title' => "AccueilController",
            'products' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/Mentions', name:'mentions')]

    public function mention(): Response
    {
        return $this->render('Mentions.html.twig');
    }
}