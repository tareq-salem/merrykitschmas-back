<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\Product;
use App\Entity\Category;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getSearchResult(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Product::class)->createQueryBuilder('p');

        $terms = $request->query->get('q');

        $query->where('p.visible = 1')
            ->andWhere('p.name LIKE :query')
            ->setParameter('query', '%'.$terms.'%');

        $products = $query->getQuery()->getResult();;

        $data = $this->get('serializer')->serialize($products, 'json', ['groups' => "product"]);
        return new JsonResponse($data, 200, [], true);
    }
}
