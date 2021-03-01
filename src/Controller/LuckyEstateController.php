<?php

namespace App\Controller;

use App\Repository\EstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LuckyEstateController extends AbstractController
{
    /**
     * @Route("/lucky-estate", name="lucky_estate")
     */
    public function index(EstateRepository $estateRepository): Response
    {
        $estate = $estateRepository->findLucky();
        $closest = $estateRepository->findClosestEstates($estate->getLocation()['coordinates'][0],$estate->getLocation()['coordinates'][1],$estate->getId());
        return $this->render('Portal/property.html.twig', [
            'estate' => $estate,
            'closestEstates' => $closest,
            'title'=> 'Nieruchomość - '.$estate->getCity().' - '.$estate->getId()
        ]);
    }
}
