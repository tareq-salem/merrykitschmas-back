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

        $data = $this->get('serializer')->serialize($subcategories, 'json', ['groups' => 'Subapi']);

        return new JsonResponse($data, 200, [], true);


    }
}
