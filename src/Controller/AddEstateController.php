<?php

namespace App\Controller;

use App\Document\Estate;
use Doctrine\ODM\MongoDB\DocumentManager;
use Gedmo\Sluggable\Util\Urlizer;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddEstateController extends AbstractController
{

    private $router;
    private $logger;

    public function __construct(RouterInterface $router, LoggerInterface $logger)
    {
        $this->router = $router;
        $this->logger = $logger;
    }

    /**
     * @Route("/add-estate", name="add_estate")
     */
    public function index(Request $request,Estate $estate,DocumentManager $dm, ValidatorInterface $validator): Response
    {

        $features_array = [
            'Klimatyzacja'=>false,
            'Garaż'=>false,
            'Piwnica'=>false,
            'Balkon'=>false,
            'Centralne ogrzewanie'=>false,
            'Ogród'=>false,
            'Basen'=>false,
            'Taras'=>false,
        ];

        if ($request->isMethod('POST')) {
            $lat = floatVal($request->request->get('lat'));
            $long = floatVal($request->request->get('long'));
            $address = filter_var($request->request->get('address'), FILTER_SANITIZE_STRING);
            $city = filter_var($request->request->get('city'), FILTER_SANITIZE_STRING);
            $description = trim(filter_var($request->request->get('description'), FILTER_SANITIZE_STRING));
            $price = filter_var($request->request->get('price'), FILTER_SANITIZE_NUMBER_FLOAT);
            $rooms = filter_var($request->request->get('rooms'), FILTER_SANITIZE_NUMBER_INT);
            $floor = filter_var($request->request->get('floor'), FILTER_SANITIZE_NUMBER_INT);
            $sq = filter_var($request->request->get('sq'), FILTER_SANITIZE_NUMBER_FLOAT);
            $type = filter_var($request->request->get('type'), FILTER_SANITIZE_STRING);
            $features = $request->request->get('features');

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('image');
            if($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/images/';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename =  Urlizer::urlize($originalFilename) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $violations = $validator->validate(
                    $uploadedFile,
                    new Image([
                        'maxSize' => '2M'
                    ])
                );
                // jeśli są błądy w zdjęciu
                if ($violations->count() > 0) {
                    /** @var ConstraintViolation $violation */
                    $violation = $violations[0];
                    $errors[] = 'Wrong image.';
                    $this->addFlash('error', $violation->getMessage());
                } else {
                // jeśli brak błedów
//                if (empty($errors)) {

                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );

                $estate = new Estate();
                $estate->setImages(array('images/'.$newFilename));
                $estate->setDescription($description);
                $estate->setType($type);
                $estate->setLocation(array('type'=> "Point", 'coordinates'=> array( $lat,$long )));
                $estate->setCity($city);
                $estate->setAddress($address);
                $estate->setName($address);
                $estate->setPrice($price);
                $estate->setEstateDetails(array('pokoje'=>$rooms, 'powierzchnia'=>$sq,'piętro'=>$floor));

                foreach($features as $key=>$item) {
                    $features_array[$item] = true;
                    }

                $estate->setFeatures($features_array);


                $dm->persist($estate);
                $dm->flush();
                return new RedirectResponse($this->router->generate('property',['id' =>$estate->getId()]));
                }
            }

        }



        return $this->render('Portal/add_state.html.twig', [
            'controller_name' => 'AddEstateController',
        ]);
    }
}
