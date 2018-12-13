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
        $terms = explode(' ', $terms);

        $query->where('p.visible = 1')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('p.subcategories', 's')
            ->leftJoin('p.themes', 't');

        for($i = 0; $i < count($terms); $i++) {
            $query->andWhere('p.name LIKE :query'.$i.' OR c.name LIKE :query'.$i.' OR s.name LIKE :query'.$i.' OR t.name LIKE :query'.$i)
            ->setParameter('query'.$i, '%'.$terms[$i].'%');
        }

        $products = $query->getQuery()->getResult();

        $data = $this->get('serializer')->serialize($products, 'json', ['groups' => "product"]);
        return new JsonResponse($data, 200, [], true);
    }
}
