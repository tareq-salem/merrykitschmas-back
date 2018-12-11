<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;




class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     * @Method({"PUT"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function addComment(Request $request)
    {

        $content = $request->request->get('content');
        $productId = $request->request->get('product');

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneById(mt_rand(1, 8));
        $product = $em->getRepository(Product::class)->findOneById($productId);

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setProduct($product);
        $comment->setContent($content);
        $comment->setVisible(1);
        $comment->setCreatedAt(new \DateTime());

        $em->persist($comment);
        $em->flush();

        $data = $this->get('serializer')->serialize($comment, 'json', ['groups' => ["comment", "user"]]);

        return new JsonResponse($data, 200, [], true);
    }
}
