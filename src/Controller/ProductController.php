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
        $enStock = $request->query->get('stock');
        $order = $request->query->get('orderby');



        $query = $em->createQueryBuilder();

        $query->select('p', 'pm')
            ->from('App\Entity\Product', 'p')
            ->where('p.visible = 1')
            ->join('p.productParameters', 'pm');

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

        if($option != null) {
                $query->join('p.options', 'o')
                    ->andWhere('o.name = :opt')
                    ->setParameter('opt', $option)
                    ->addSelect('o');
        }


        if($theme != null) {
            $query->join('p.themes', 't')
                ->andWhere('t.name = :theme')
                ->setParameter('theme', $theme)
                ->addSelect('t');
        }

        if($order != null && $order =='pasc') {
            $query->orderBy('p.price', 'ASC');

        }

        if($order != null && $order == 'pdesc'){
            $query->orderBy('p.price', 'DESC');
        }

        if($order != null && $order =='dasc') {
            $query->orderBy('p.created_at', 'ASC');

        }

        if($order != null && $order == 'ddesc'){
            $query->orderBy('p.created_at', 'DESC');
        }

        $products = $query->getQuery()->getResult();
        $endProducts = [];

        if($enStock == 1) {
            foreach($products as $product) {
                if($product->hasStock()) {
                    array_push($endProducts, $product);
                }
            }
        }

        $data = $this->get('serializer')->serialize($endProducts, 'json', ['groups' => "product"]);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/product/{id}", name="product")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getProduct($id)
    {
        $em = $this->getDoctrine()->getManager();

        $productId = $em->getRepository(Product::class)->find($id);

        if (!$productId) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $query = $em->createQueryBuilder();

        $query->select('p')
            ->from('App\Entity\Product', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $productId);

        $product = $query->getQuery()->getResult();

        $data = $this->get('serializer')->serialize($product, 'json', ['groups' => ["product", "category", "subcategory", "theme", "option", "comment", "user"]]);

        return new JsonResponse($data, 200, [], true);
    }





}
