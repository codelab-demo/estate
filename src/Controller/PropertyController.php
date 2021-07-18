<?php

namespace App\Controller;

use App\Document\Estate;
use App\Repository\EstateRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/property-{id}", name="property")
     */
    public function index(EstateRepository $estateRepository, $id): Response
    {

        $estate = $estateRepository->find($id);
        $homeEstates = $estateRepository->findRandom();

        $closest = $estateRepository->findClosestEstates($estate->getLocation()['coordinates'][0],$estate->getLocation()['coordinates'][1],$estate->getId());

        return $this->render('Portal/property.html.twig', [
            'estate' => $estate,
            'homeEstates' => $homeEstates,
            'closestEstates' => $closest,
            'title'=> 'Nieruchomość - '.$estate->getCity().' - '.$estate->getId()
        ]);



    }
}
