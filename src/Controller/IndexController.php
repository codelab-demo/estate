<?php

namespace App\Controller;

use App\Document\Estate;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(DocumentManager $dm): Response
    {
        $repository = $dm->getRepository(Estate::class);
        $homeEstates = $dm->getRepository(Estate::class)
            ->findRandom();
        $topPlaces = $repository->findPopularPlaces(3);

         return $this->render('Portal/index.html.twig', [
            'homeEstates' => $homeEstates,
            'topPlaces' => $topPlaces
        ]);
    }
}
