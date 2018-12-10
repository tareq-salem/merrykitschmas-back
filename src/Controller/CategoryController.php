<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Subcategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CategoryController extends AbstractController
{
//    /**
//     * @Route("/category/add/{name}", name="category_add")
//     * @Method({"POST", "PUT"})
//     * @param Request $name
//     * @return Response
//     */
//    public function addCategory( $name)
//    {
//
//        //$name = $request->query->get('name');
//
//        //dump($name);
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $category = new Category();
//        $category->setName($name);
//
//        $entityManager->persist($category);
//        $entityManager->flush();
//
//
//         return new Response('Saved new category with id '.$category->getId());
//
//    }

    /**
     * @Route("/categories", name="categories")
     * @Method({"GET"})
     * @return Response
     */
    public function getAllCategory()
    {
        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Category::class);
        $categories = $repository->findAll();

        $data = $this->get('serializer')->serialize($categories, 'json', ['groups' => "api"]);

        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/category/{id}", name="category")
     * @Method({"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getCategory($id)
    {
        $em = $this->getDoctrine()->getManager();

        $categoryId = $em->getRepository(Category::class)->find($id);

        if (!$categoryId) {
            throw $this->createNotFoundException(
                'No Category found for id '.$id
            );
        }

        $query = $em->createQueryBuilder();

        $query->select('c')
            ->from('App\Entity\Category', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $categoryId);

        $category = $query->getQuery()->getResult();
        $data = $this->get('serializer')->serialize($category, 'json', ['groups' => "api"]);

        return new JsonResponse($data, 200, [], true);
    }


//    /**
//     * @Route("/category/delete/{id}", name="category_delete")
//     * @Method({"DELETE"})
//     * @param Request $id
//     * @return Response
//     */
//    public function deleteCategory($id)
//    {
//
//        //$id = $request->query->get('id');
//
//        dump($id);
//
//        $entityManager = $this->getDoctrine()->getManager();
//        $category = $entityManager->getRepository(Category::class)->find($id);
//
//        if (!$category) {
//            throw $this->createNotFoundException(
//                'No category found for id '.$id
//            );
//        }
//
//        $entityManager->remove($category);
//        $entityManager->flush();
//
//
//        return new Response('category with id  '. $id . ' deleted');
//
//
//    }


}
