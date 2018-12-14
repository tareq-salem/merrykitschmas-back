<?php

namespace App\Controller;

use App\Entity\Subcategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubcategoryController extends AbstractController
{
//    /**
//     * @Route("/subcategory/add/{name}", name="subcategory_add")
//     * @Method({"POST", "PUT"})
//     * @param Request $name
//     * @return Response
//     */
//    public function addSubCategory($name)
//    {
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $subcategory = new Subcategory();
//        $subcategory->setName($name);
//
//        $entityManager->persist($subcategory);
//        $entityManager->flush();
//
//
//        return new Response('Saved new category with id '.$subcategory->getId());
//
//    }

    /**
     * @Route("/subcategories", name="subcategories")
     * @Method({"GET"})
     * @return Response
     */
    public function getAllSubCategory()
    {


        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Subcategory::class);
        $subcategories = $repository->findAll();

        $data = $this->get('serializer')->serialize($subcategories, 'json', ['groups' => 'subcategory']);

        return new JsonResponse($data, 200, [], true);


    }

    /**
     * @Route("/subcategory/{id}", name="subcategory")
     * @Method({"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getSubcategory($id)
    {
        $em = $this->getDoctrine()->getManager();

        $subcategoryId = $em->getRepository(Subcategory::class)->find($id);

        if (!$subcategoryId) {
            throw $this->createNotFoundException(
                'No Category found for id '.$id
            );
        }

        $query = $em->createQueryBuilder();

        $query->select('s')
            ->from('App\Entity\Subcategory', 's')
            ->where('s.id = :id')
            ->setParameter('id', $subcategoryId);

        $subcategory = $query->getQuery()->getResult();
        $data = $this->get('serializer')->serialize($subcategory, 'json', ['groups' => "subcategory"]);

        return new JsonResponse($data, 200, [], true);
    }
}
