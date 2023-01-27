<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $managerRegistry;
    private $productRepository;

    public function __construct(ManagerRegistry $managerRegistry, ProductRepository $productRepository)
    {
        $this->managerRegistry = $managerRegistry;
        $this->productRepository = $productRepository;
    }

    #[Route('/product', name: 'product')]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findBy([], ['name' => 'ASC'])
        ]);
    }

    #[Route('/admin/product', name: 'admin_product')]
    public function adminIndex(): Response
    {
        return $this->render('product/adminList.html.twig', [
            'products'=> $this->productRepository->findall()
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger, ManagerRegistry $managerRegistry): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData();

            if (empty($infoImg)) {
                $this->addFlash('danger', 'Le produit n\'a pas pu être créé : l\'image principale est obligatoire mais n\'a pas été renseignée.');
                return $this->redirectToRoute('product_create');
            } elseif (empty($form['alt']->getData())) {
                $this->addFlash('danger', 'Le texte alternatif pour l\'image pricnipale est obligatoire.');
                return $this->redirectToRoute('product_create');
            }

            $imgName = time() . '-1.' . $infoImg->guessExtension();
            $infoImg->move($this->getParameter('product_img_dir'), $imgName);
            $product->setImg($imgName);
            
            $manager = $this->managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

        $this->addFlash('success', 'Le produit a bien été créé'); // message de succès (message flash)

        return $this->redirectToRoute('admin_product');
        }


        return $this->render('product/create.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'product_delete')]
    public function delete(Product $product): Response
    {

        $imgpath = $this->getParameter('product_img_dir') . '/' . $product->getImg();
        if (file_exists($imgpath)) {
            unlink($imgpath);
        }

        $manager = $this->managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();
        $this->addFlash('success', 'Le produit a bien été supprimée');

        return $this->redirectToRoute('admin_product');
    }
    
}
