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
     * @Route("/car/add/{id}", name="add_cart")
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


}
