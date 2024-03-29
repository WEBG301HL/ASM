<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
     /**
     * @Route("/cart", name="app_cart")
     */
    public function showCart(CartRepository $repo): Response
    {
        $user = $this->getUser();
        if ($user != "") {
            $carts = $repo->cartShow($user->getId());
            $total= $repo->totalPricecart($user);
            return $this->render('cart/index.html.twig',[
                'carts'=>$carts,
                'total'=>$total[0]['Total']
            ]);   
        }
        else{
            return $this->redirectToRoute('homepage');
        }
    }

     /**
     * @Route("/cart/sortASC", name="sortbyASC")
     */
    public function sortByASC(CartRepository $repo, Request $req): Response
    {
        $user = $this->getUser();
        $carts = $repo->cartOrderBy($user->getId(), "asc");
        $total= $repo->totalPricecart($user);
        return $this->render('cart/index.html.twig',[
            'carts'=>$carts,
            'total'=>$total[0]['Total']
        ]);
    }

     /**
     * @Route("/cart/sortDESC", name="sortbyDESC")
     */
    public function sortByDESC(CartRepository $repo, Request $req): Response
    {
        $user = $this->getUser();
        $carts = $repo->cartOrderBy($user->getId(), "desc");
        $total= $repo->totalPricecart($user);
        return $this->render('cart/index.html.twig',[
            'carts'=>$carts,
            'total'=>$total[0]['Total']
        ]);
    }   

    /**
     * @Route("/cart/add/{id}", name="add_cart")
     */
    public function addCart(Product $p, Request $req, CartRepository $repo): Response
    {
        $qty =  $req->query->get('quantity_input');
        $qty = (integer) $qty;
        $cart = new Cart();
        $cart->setQuantity($qty);
        $cart->setProcart($p);
        $cart->setUsercart($this->getUser());
        $repo->add($cart, true);
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete")
     */
    public function deleteCart(Request $req, Cart $cart, CartRepository $repo): Response
    {
        $repo->remove($cart,true);
        return $this->redirectToRoute('app_cart');
    }


}
