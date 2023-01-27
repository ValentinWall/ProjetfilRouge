<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    // private CategoryRepository $categoryRepository;
    // public function __construct(CategoryRepository $caterepo)
    // {
    //     $this->categoryRepository=$caterepo;
    // }

    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories'=> $categoryRepository->findAll()
        ]);
    }

    #[Route('/admin/category', name: 'admin_category')]
    public function adminIndex(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/adminList.html.twig', [
            'categories'=> $categoryRepository->findAll()
        ]);
    }

    #[Route('/admin/category/create', name: 'category_create')]
    public function create(Request $request, SluggerInterface $slugger, ManagerRegistry $managerRegistry): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();

        $this->addFlash('success', 'La catégorie a bien été créée'); // message de succès (message flash)

        return $this->redirectToRoute('admin_category');
        }


        return $this->render('category/create.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category, ManagerRegistry $managerRegistry): Response
    {
        // if (!$category->getProduct()->isEmpty()) {
        //     $this->addFlash('danger', 'Des produits sont associés à cette catégorie. Merci de supprimer ces derniers avant de supprimer cette catégorie.');
        // } else {
        //     $img = $this->getParameter('category_img_dir') . '/' . $category->getImg();
        //     if ($category->getImg() !== null && file_exists($img)) {
        //         unlink($img);
        //     }
            $manager = $managerRegistry->getManager();
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('success', 'La catégorie a bien été supprimée');
        //}
        return $this->redirectToRoute('admin_category');
    }
}
