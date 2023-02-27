<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


/**
* @Route("/admin")
*/
class AdminController extends AbstractController
{
    private ProductRepository $repo;
    private UserRepository $urepo;
    public function __construct(ProductRepository $repo, UserRepository $urepo)
   {
      $this->repo = $repo;
      $this->urepo = $urepo;
   }

   /**
     * @Route("/", name="app_admin")
     */
    public function index(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
        $accounts = $this->urepo->findAll();
        return $this->render('admin/index.html.twig', [
            'accounts'=>$accounts
        ]);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

    
    /**
     * @Route("/products", name="product_show")
     */
    public function proShow(Request $req, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $products = $this->repo->findAll();
            return $this->render('admin/product/index.html.twig', [
            'products'=>$products
            ]);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

    public function uploadImage($imgFile, SluggerInterface $slugger): ?string{
        $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();
        try {
            $imgFile->move(
                $this->getParameter('image_dir'),
                $newFilename
            );
        } catch (FileException $e) {
            echo $e;
        }
        return $newFilename;
    }

}
