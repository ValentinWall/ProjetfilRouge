<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart')]

class CartController extends AbstractController
{
    #[Route('/', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart()
        ]);
    }

    #[Route('/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id, Request $request): Response
    {
        $cartService->add($id);
        $this->addFlash('success', 'l\'article a bien été ajouté');
        if ($request->headers->get('referer') === $this->getParameter('domain') . '/cart/') {
            return $this->redirectToRoute('cart');
        }
        return $this->redirectToRoute('/accueil');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        $this->addFlash('success', 'Le panier à été vidé');
        return $this->redirectToRoute('cart');
    }
}
