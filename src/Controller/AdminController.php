<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\ProductType;
use App\Form\SupplierType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\SupplierRepository;
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
    private CategoryRepository $crepo;
    private SupplierRepository $srepo;
    public function __construct(ProductRepository $repo, UserRepository $urepo, CategoryRepository $crepo, SupplierRepository $srepo)
   {
      $this->repo = $repo;
      $this->urepo = $urepo;
      $this->crepo = $crepo;
      $this->srepo = $srepo;
   }

   /**
     * @Route("/", name="app_admin")
     */
    public function index(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            return $this->render('admin/index.html.twig', []);
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


    /**
     * @Route("/products/add", name="product_add")
    */
    public function addProduct(Request $req, SluggerInterface $slugger,Security $security): Response
    {
         if ($security->isGranted('ROLE_ADMIN')) {
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
            return $this->render("admin/product/add.html.twig",[
                'form' => $form->createView()
            ]);
        }else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

    /**
     * @Route("/products/edit/{id}", name="product_edit",requirements={"id"="\d+"})
     */
    public function editPro(Request $req, Product $p, SluggerInterface $slugger, Security $security): Response
    {
            if ($security->isGranted('ROLE_ADMIN')) {
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
            return $this->render("admin/product/edit.html.twig",[
                'form' => $form->createView()
            ]);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

     /**
     * @Route("/product/delete/{id}",name="product_delete",requirements={"id"="\d+"})
     */
    
     public function deletePro(Request $request, Security $security, Product $p): Response
     {
        if ($security->isGranted('ROLE_ADMIN')) {
            $this->repo->remove($p,true);
            return $this->redirectToRoute('product_show', [], Response::HTTP_SEE_OTHER);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
     }
    

    // ------------------------------------------------------------------------------------- //

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

    /**
     * @Route("/category", name="category_show")
     */
    public function catShow(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $category = $this->crepo->findAll();
            return $this->render('admin/category/index.html.twig', [
            'category'=>$category
            ]);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }


    /**
     * @Route("/category/add", name="category_add")
    */
    public function addCategory(Request $req,Security $security): Response
    {
         if ($security->isGranted('ROLE_ADMIN')) {
            $c = new Category();
            $form = $this->createForm(CategoryType::class, $c);

            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()){
                $this->crepo->add($c,true);
                return $this->redirectToRoute('category_show', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render("admin/category/add.html.twig",[
                'form' => $form->createView()
            ]);
        }else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

    /**
     * @Route("/category/sortby", name="category_sort")
     */
    public function sortByName(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
        $category = $this->crepo->catSortBy("asc");
        return $this->render('admin/category/index.html.twig',[
            'category'=>$category
        ]);
        }else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

    /**
     * @Route("/category/delete/{id}",name="category_delete",requirements={"id"="\d+"})
     */
    
     public function deleteCat(Request $request, Category $c): Response
     {
         $this->crepo->remove($c,true);
         return $this->redirectToRoute('category_show', [], Response::HTTP_SEE_OTHER);
     }
     

      /**
     * @Route("/category/edit/{id}", name="category_edit",requirements={"id"="\d+"})
     */
    public function editCat(Request $req, Category $c,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CategoryType::class, $c);   

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->crepo->add($c,true);
            return $this->redirectToRoute('category_show', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render("admin/category/edit.html.twig",[
            'form' => $form->createView()
        ]);
    }

    //  =============================================================================== //
     
    /**
     * @Route("/supplier", name="supplier_show")
     */
    public function supShow(Request $req, Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $supplier = $this->srepo->findAll();
            return $this->render('admin/supplier/index.html.twig', [
            'supplier'=>$supplier
            ]);
        }
        else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

     /**
     * @Route("/supplier/add", name="supplier_add")
    */
    public function addSup(Request $req,Security $security): Response
    {
         if ($security->isGranted('ROLE_ADMIN')) {
            $c = new Supplier();
            $form = $this->createForm(SupplierType::class, $c);

            $form->handleRequest($req);
            if($form->isSubmitted() && $form->isValid()){
                $this->srepo->add($c,true);
                return $this->redirectToRoute('supplier_show', [], Response::HTTP_SEE_OTHER);
            }
            return $this->render("admin/supplier/add.html.twig",[
                'form' => $form->createView()
            ]);
        }else{
            return $this->render("error_admin.html.twig",[
            ]);
        }
    }

        /**
     * @Route("/supplier/edit/{id}", name="supplier_edit",requirements={"id"="\d+"})
     */
    public function editSup(Request $req, Supplier $c, SluggerInterface $slugger,Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')){
        $form = $this->createForm(SupplierType::class, $c);   

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->srepo->add($c,true);
            return $this->redirectToRoute('supplier_show', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render("admin/supplier/edit.html.twig",[
            'form' => $form->createView()
        ]);
    }
}


    /**
     * @Route("/supplier/delete/{id}",name="supplier_delete",requirements={"id"="\d+"})
     */
    
     public function deleteSup(Request $request, supplier $p): Response
     {
         $this->srepo->remove($p,true);
         return $this->redirectToRoute('supplier_show', [], Response::HTTP_SEE_OTHER);
     }

    // ========================================================================= //

    /**
     * @Route("/accounts", name="account_show")
     */
    public function accShow(Security $security): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $accounts = $this->urepo->findAll();
            return $this->render('admin/account/index.html.twig', [
                'accounts'=>$accounts
            ]);
            }
            else{
                return $this->render("error_admin.html.twig",[
                ]);
            }
    }

    /**
     * @Route("/accounts/delete/{id}",name="account_delete",requirements={"id"="\d+"})
     */
    
     public function deleteAcc(Request $request, Security $security, User $u): Response
     {
        if ($security->isGranted('ROLE_ADMIN')) {
         $this->urepo->remove($u,true);
         return $this->redirectToRoute('account_show', [], Response::HTTP_SEE_OTHER);
     }
     else{
        return $this->render("error_admin.html.twig",[
        ]);
    }
}

}
    


