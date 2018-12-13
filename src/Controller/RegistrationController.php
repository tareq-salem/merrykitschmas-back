<?php

namespace App\Controller;

use App\Security\LoginFormAuthenticator;
use App\Security\TokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/api/register", name="user__api_registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function registerFromApi(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'csrf_protection' => false
        ]);

        $form->submit($request->request->all());
        // 2) handle the submit (will only happen on POST)
        //$form->handleRequest($request);



        if ($form->isValid()) {



            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());

            $username = $user->getUsername();
            $apiToken = $passwordEncoder->encodePassword($user,$username.$password);

            $user->setPassword($password);
            $user->setIsactive(1);
            $user->setRoles(["ROLE_USER"]);
            $user->setCreatedAt(new \DateTime());
            $user->setApiToken( $apiToken);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            $data = $this->get('serializer')->serialize($user, 'json', ['groups' => ["token", "user"]]);

            return new JsonResponse($data, 200, [], true);
        }

        dump($form->getErrors());

        return new JsonResponse($form->getErrors(), 200, [], false);

    }

}
