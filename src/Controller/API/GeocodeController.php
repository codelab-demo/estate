<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeocodeController extends AbstractController
{
    /**
     * @Route("/api/geocode", name="api_geocode")
     */
    public function index(Request $request): Response
    {


        $geocoder = new \OpenCage\Geocoder\Geocoder($_ENV['GEO_API']);
        $lat = $request->query->get('latitude');
        $long = $request->query->get('longtitude');
        $result = $geocoder->geocode($lat . "," . $long, [
            'language' => 'pl'
        ]);

        $city = (isset($result['results'][0]['components']['city']) ? $result['results'][0]['components']['city'] : (isset($result['results'][0]['components']['town']) ? $result['results'][0]['components']['town'] : (isset($result['results'][0]['components']['village']) ? $result['results'][0]['components']['village'] : null)));
        $address = (isset($result['results'][0]['components']['road']) ? $result['results'][0]['components']['road'] : null);
        $house_numer = (isset($result['results'][0]['components']['house_number']) ? $result['results'][0]['components']['house_number'] : (isset($result['results'][0]['components']['street_number']) ? $result['results'][0]['components']['street_number'] : null));
        $country = (isset($result['results'][0]['components']['country']) ? $result['results'][0]['components']['country'] : null);

        return new JsonResponse(array('city' => $city, 'address' => $address, 'country' => $country, 'house_number' => $house_numer));

    }
}
