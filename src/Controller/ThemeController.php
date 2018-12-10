<?php

namespace App\Controller;

use App\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ThemeController extends AbstractController
{
    /**
     * @Route("/themes", name="themes")
     * @Method({"GET"})
     * @return Response
     */
    public function getAllThemes()
    {

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository(Theme::class);
        $themes = $repository->findAll();

        $data = $this->get('serializer')->serialize($themes, 'json', ['groups' => "apiTheme"]);

        return new JsonResponse($data, 200, [], true);


    }
}
