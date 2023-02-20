<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminController extends AbstractController
{
    private ProductRepository $repo;
    public function __construct(ProductRepository $repo)
   {
      $this->repo = $repo;
   }
    /**
     * @Route("/admin", name="app_admin")
     */
    public function addPro(Request $req, SluggerInterface $slugger): Response
    {
        $p = new Product();
        $form = $this->createForm(ProductType::class, $p);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            if($p->getCreated()===null){
                $p->setCreated(new \DateTime());
            }
            $imgFile = $form->get('file')->getData();
            if ($imgFile) {
                $newFilename = $this->uploadImage($imgFile,$slugger);
                $p->setImage($newFilename);
            }
            $this->repo->add($p,true);
            return $this->redirectToRoute('product_show', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render("product/form.html.twig",[
            'form' => $form->createView()
        ]);
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
