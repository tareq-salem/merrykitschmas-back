<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Query\Expr\Join;
use App\Repository\ProductRepository;


class ProductController extends AbstractController
{
//    /**
//     * @Route("/product/add/{name}", name="product_add")
//     * @Method({"POST", "PUT"})
//     * @param Request $request
//     * @return Response
//     */
//    public function addProduct(Request $request)
//    {
//
//        $name = $request->query->get('name');
//        $price = $request->query->get('price');
//        $description = $request->query->get('description');
//
//        //dump($name);
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $product = new Product();
//        $product->setName($name);
//        $product->setPrice($price);
//        $product->setDescription($description);
//
//
//        $entityManager->persist( $product);
//        $entityManager->flush();
//
//
//        return new Response('Saved new category with id '.$product->getId());
//
//    }

    /**
     * @Route("/products", name="products")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getAllProduct(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $request->query->get('cat');
        $subcategory = $request->query->get('sub');
        $theme = $request->query->get('theme');
        $option = $request->query->get('opt');

        $query = $em->createQueryBuilder();

        $query->select('p')
            ->from('App\Entity\Product', 'p')
            ->where('p.visible = 1');

        if($category != null) {
            $query->join('p.categories', 'c')
                ->andWhere('c.name = :cat')
                ->setParameter('cat', $category)
                ->addSelect('c');
        }

        if($subcategory != null) {
            $query->join('p.subcategories', 's')
                ->andWhere('s.name = :sub')
                ->setParameter('sub', $subcategory)
                ->addSelect('s');
        }

        if($theme != null) {
            $query->join('p.themes', 't')
                ->andWhere('t.name = :theme')
                ->setParameter('theme', $theme)
                ->addSelect('t');
        }

//        $query->orderBy('p.price', 'ASC');

        $products = $query->getQuery()->getResult();
        $data = $this->get('serializer')->serialize($products, 'json', ['groups' => "api"]);

        return new JsonResponse($data, 200, [], true);
    }



}
