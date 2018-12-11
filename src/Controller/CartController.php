<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductCart;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addCart(Request $request)
    {
        $productId = $request->request->get('product');

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findOneById($productId);
        /** @var User $user */
        $user = $em->getRepository(User::class)->findOneById(mt_rand(1, 8));

        /** @var Cart $cart */
        $cart = $user->getCart();

        if ($cart == null) {
            $cart = new Cart();
            $user->setCart($cart);
            $em->persist($cart);
        }

        dump($product);

        $cartHasProduct = new ProductCart();
        $cartHasProduct->setCart($cart);
        $cartHasProduct->setProduct($product);
        $cartHasProduct->setQuantity(2);

        $em->persist($cartHasProduct);

        $cart->addProductCart($cartHasProduct);
        $cart->setUser($user);

        $em->flush();

        $data = $this->get('serializer')->serialize($cart, 'json', ['groups' => "cart"]);

        return new JsonResponse($data, 200, [], true);
    }
}
