<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    /**
     * @Route("/api/login", name="api_login")
     */
    public function loginApi(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $username = $request->request->get('username');

        /** @var User $user */
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($passwordEncoder->isPasswordValid($user, $request->request->get('password'))) {
            $data = $this->get('serializer')->serialize($user, 'json', ['groups' => ["token", "user"]]);
            return new JsonResponse($data, 200, [], true);
        }

        return new JsonResponse('Error Authentication');
    }

    /**
     * @Route("/test/security", name="secu")
     * @param Request $request
     * @param Security $security
     * @return JsonResponse
     */
    public function testSecurity(Request $request, Security $security) {

        /** @var User $user */
        $user = $security->getUser();

        $data = $this->get('serializer')->serialize($user, 'json', ['groups' => "user"]);

        return new JsonResponse($data, 200, [], true);
    }
}
