<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     * @Method({"GET"})
     * @param Security $security
     */
    public function getUserInfo(Security $security)
    {
        /** @var User $user */
        $user = $security->getUser();

        if($user == null) {
            throw new AuthenticationException();
        }

        $data = $this->get('serializer')->serialize($user, 'json', ['groups' => ["user", "cart"]]);
        return new JsonResponse($data, 200, [], true);
    }
}
