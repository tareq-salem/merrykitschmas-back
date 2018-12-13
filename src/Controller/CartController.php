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
     * @Route("/cart/add", name="cart")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addProductInCart(Request $request, Security $security)
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
        $sameProduct = false;

        $em = $this->getDoctrine()->getManager();

        /** @var Product $product */
        $product = $em->getRepository(Product::class)->findOneById($productId);

        /** @var Cart $cart */
        $cart = $user->getCart();

        if ($cart == null) {
            $cart = new Cart();
            $cart->setUser($user);
        }

        /** @var ProductCart $existingProductsInCart */
        $existingProductsInCart = $em->getRepository(ProductCart::class)->findBy(["product" => $product, "cart" => $cart]);
        $sizeOfExistingProduct = null;
        $quantityOfExistingProduct = null;

        //Si le produit(ID) que j'ajoute n'existe pas dans mon cart
        if ($existingProductsInCart == null) {

            $newCartProduct = new ProductCart();
            $newCartProduct->setProduct($product);
            $newCartProduct->setQuantity($quantity);
            $newCartProduct->setSize($size);
            if ($option != null) {
                $newCartProduct->addProductCartOptionProduct($option);
            }
            $cart->addProductCart($newCartProduct);

            $em->persist($newCartProduct);
            $sameProduct = true;
        }

        //Si le produit(ID) que j'ajoute existe dans mon cart
        if($existingProductsInCart) {
            //Je fais une boucle sur tous mes produits du cart qui ont le même ID que celui que j'ajoute pour comparer les tailles
            foreach($existingProductsInCart as $existingProductInCart) {

                $sizeOfExistingProduct = $existingProductInCart->getSize();
                $quantityOfExistingProduct = $existingProductInCart->getQuantity();

                //Si la taille est la même, j'additionne les quantités
                if($sizeOfExistingProduct == $size) {
                    $existingProductInCart->setQuantity($quantityOfExistingProduct + $quantity);
                    $em->persist($existingProductInCart);
                    $sameProduct = true;
                }
            }
        }

        //Si les produits sont les mêmes, mais la taille diffèrent, on ajoute un nouveau produit avec une taille différente
        if ($sameProduct == false) {
            $newCartProduct = new ProductCart();
            $newCartProduct->setProduct($product);
            $newCartProduct->setQuantity($quantity);
            $newCartProduct->setSize($size);
            if ($option != null) {
                $newCartProduct->addProductCartOptionProduct($option);
            }
            $cart->addProductCart($newCartProduct);

            $em->persist($newCartProduct);
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
        $message = null;

        if($user == null) {
            throw new AuthenticationException();
        }

        $em = $this->getDoctrine()->getManager();

        /** @var User $user */
        $cart = $user->getCart();

        if ($cart == null) {
            $message = "No cart for ".$user->getUsername() ;
        }

        if($cart) {
            $em->remove($cart);
            $em->flush();
            $message = "Cart deleted for ".$user->getUsername();
        }

        $data = $this->get('serializer')->serialize($message, 'json');
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/cart/", name="get-cart")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function getCart(Request $request, Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();

        if($user == null) {
            throw new AuthenticationException();
        }

        /** @var User $user */
        $cart = $user->getCart();

        if ($cart == null) {
            $message = "No cart for ".$user->getUsername();
            $data = $this->get('serializer')->serialize($message, 'json');
        } else {
            $data = $this->get('serializer')->serialize($cart, 'json', ['groups' => ["cart", "productCart", "option", "size"]]);
        }

        return new JsonResponse($data, 200, [], true);
    }
}
