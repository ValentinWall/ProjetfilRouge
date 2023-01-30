<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Panier;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/cart')]

class CartController extends AbstractController
{
    
    #[Route('/', name: 'cart')]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cartService->getCart(),
            'total' => $cartService->getTotal()
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

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(Cartservice $cartService, int $id): Response
    {
        $cartService->remove($id);
        $this->addFlash('success', 'Le produit a bien été retiré du panier');
        return $this->redirectToRoute('cart');
    }

    #[Route('/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        $this->addFlash('success', 'Le panier à été vidé');
        return $this->redirectToRoute('cart');
    }

    #[Route('/delete/{id}', name: 'cart_delete')]
    public function delete(CartService $cartService, int $id) : Response
    {
        $cartService->delete($id);
        $this->addFlash('success', 'Le produit a bien été supprimé du panier');
        return $this->redirectToRoute('cart');
    }
}
