<?php

namespace App\Controller;

use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            return $this->render('cart/index.html.twig',[
                'carts'=>$carts
            ]);   
        }
        else{
            return $this->redirectToRoute('homepage');
        }
    }

}
