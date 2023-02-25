<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class MainController extends AbstractController
{
    private ProductRepository $repo;
    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }
    /**
     * @Route("/", name="homepage")
     */
    public function indexPageAction(): Response
    {
        $products = $this->repo->findAll();
        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
    
    /**
     * @Route("/aboutUs", name="about_us")
     */
    public function aboutUs(): Response
    {
        return $this->render('main/about.html.twig', []);
    }

    /**
     * @Route("/admin", name="adminPage")
     */
    public function adminPageAction(): Response
    {
        return $this->render('admin.html.twig', []);
    }
}
