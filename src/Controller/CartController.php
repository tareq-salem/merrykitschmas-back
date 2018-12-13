<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\ProductCart;
use App\Entity\User;
use App\Service\SecurityService;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CartController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     * @Method({"GET"})
     * @param Request $request
     */
    public function test(Request $request, Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();

        dump($user);
        $data = $this->get('serializer')->serialize($user, 'json', ['groups' => "user"]);
        return new JsonResponse($data, 200, [], true);
    }


    /**
     * @Route("/cart", name="cart")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addCart(Request $request, Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();

        if($user == null) {
            throw new AuthenticationException();
        }

        $productId = $request->request->get('product');
        $size = $request->request->get('size');
        $quantity = $request->request->get('quantity');
        $option = $request->request->get('option');

        $em = $this->getDoctrine()->getManager();

        /** @var Product $product */
        $product = $em->getRepository(Product::class)->findOneById($productId);

        /** @var Cart $cart */
        $cart = $user->getCart();

        if ($cart == null) {
            $cart = new Cart();
            $cart->setUser($user);
        }

        /** @var ProductCart $existingProductInCart */
        $existingProductInCart = $em->getRepository(ProductCart::class)->findOneBy(["product" => $product, "cart" => $cart]);
        $sizeOfExistingProduct = null;
        $quantityOfExistingProduct = null;

        if($existingProductInCart) {
            $sizeOfExistingProduct = $existingProductInCart->getSize();
            $quantityOfExistingProduct = $existingProductInCart->getQuantity();
        }

        if ($existingProductInCart == null) {

            $newCartProduct = new ProductCart();
            $newCartProduct->setProduct($product);
            $newCartProduct->setQuantity($quantity);
            $newCartProduct->setSize($size);
            if ($option != null) {
                $newCartProduct->addProductCartOptionProduct($option);
            }
            $cart->addProductCart($newCartProduct);

            $em->persist($newCartProduct);

        } elseif($sizeOfExistingProduct == $size) {

            $existingProductInCart->setQuantity($quantityOfExistingProduct + $quantity);


            $em->persist($existingProductInCart);

        }

        $em->persist($cart);
        $em->flush();

        $data = $this->get('serializer')->serialize($cart, 'json', ['groups' => ["cart", "productCart", "option", "size"]]);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/cart/delete", name="delete-cart")
     * @Method({"DELETE"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteCart(Request $request, Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();
        dump($user);
        if($user == null) {
            throw new AuthenticationException();
        }

        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $cart = $user->getCart();
        dump($cart);

        if ($cart == null) {

            throw new AuthenticationException();
        }

        if($cart) {

            $em->remove($cart);
            $em->flush();
        }

        $data = $this->get('serializer')->serialize($user, 'json', ['groups' => "user"]);
        return new JsonResponse($data, 200, [], true);
    }
}
