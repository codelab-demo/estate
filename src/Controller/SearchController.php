<?php

namespace App\Controller;

use App\Repository\EstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @Route("/search-{type}", name="search-type", requirements={"type" = "na\ wynajem|na\ sprzedaż"})
     * @Route("/search-{city}", name="search-city")

     */
    public function index(EstateRepository $estateRepository, Request $request,$city = null, $type=null): Response
    {

        $postData = array('type'=>$type, 'location'=>$city, 'minRooms'=>null, 'minPrice'=>null, 'maxPrice'=>null);
        if ($request->isMethod('POST')) {
            $type = filter_var($request->request->get('propertiesPropertyType'),FILTER_SANITIZE_STRING);
            $location = filter_var($request->request->get('propertiesLocation'),FILTER_SANITIZE_STRING);

            $minPrice = $request->request->get('propertiesMinPrice');
            $maxPrice = $request->request->get('propertiesMaxPrice');

            $error = false;
            $error = $this->validateInput($minPrice, $maxPrice);

            $postData = array('type'=>$type, 'location'=>$location, 'minPrice'=>$minPrice, 'maxPrice'=>$maxPrice);
            if(!$error) {
                $searchResults = $estateRepository->findBySearch($type, $location,$minPrice,$maxPrice);
            } else {
                $searchResults = $estateRepository->findBySearch($type,$city);
            }
        } else {
            $searchResults = $estateRepository->findBySearch($type,$city);
        }


        $locations = $estateRepository->findPopularPlaces();
        return $this->render('Portal/search.html.twig', [
            'locations' => $locations,
            'searchResults' => $searchResults,
            'postData' => $postData
        ]);
    }

    /**
     * @param $minPrice
     * @param $maxPrice
     * @return bool
     */
    public function validateInput($minPrice, $maxPrice): bool
    {
        $error = false;

        if ($minPrice > $maxPrice) {
            $error = true;
            $this->addFlash('error', 'Wartość minimalna nie możę być wyższa od maksymalnej');
        }

        if ($minPrice < 0 || $maxPrice < 0) {
            $error = true;
            $this->addFlash('error', 'Wartość nie mogą być ujemne');

        }
        return $error;
    }
}
