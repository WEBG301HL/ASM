<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

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
     * @Route("/search", name="home_search")
     */
    public function searchPro(ProductRepository $repo, Request $req): Response
    {
            $search = $req->request->get('search');
                 $products = $repo->findPro($search);
                return $this->render('search/index.html.twig', [
                'products'=>$products,
                'search'=>$search
            ]);
            }
    
     /**
     * @Route("/product/{id}", name="product_read",requirements={"id"="\d+"})
     */
    public function readPro(Product $p): Response
    {
        return $this->render('detail.html.twig', [
            'p'=>$p
        ]);
    }
}



