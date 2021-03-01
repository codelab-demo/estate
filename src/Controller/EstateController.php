<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Document\Estate;
use Doctrine\ODM\MongoDB\DocumentManager as DocumentManager;

class EstateController
{
    /**
     * @Route("/add", methods={"GET"})
     */
    public function mongoTest(DocumentManager $dm)
    {
        $estate = new Estate();
        $estate->setName("Testowa 81");
        $estate->setCity("Wałbrzych");
        $estate->setPrice(12323.50);
        $estate->setType("For Rent");
        $estate->setDescription('<p>Lorem Ipsum jest tekstem stosowanym jako przykładowy wypełniacz w przemyśle poligraficznym. Został po raz pierwszy użyty w XV w. przez nieznanego drukarza do wypełnienia tekstem próbnej książki. Pięć wieków później zaczął być używany przemyśle elektronicznym, pozostając praktycznie niezmienionym. Spopularyzował się w latach 60. XX w. wraz z publikacją arkuszy Letrasetu, zawierających fragmenty Lorem Ipsum, a ostatnio z zawierającym różne wersje Lorem Ipsum oprogramowaniem przeznaczonym do realizacji druków na komputerach osobistych, jak Aldus PageMaker</p>

<p>Ogólnie znana teza głosi, iż użytkownika może rozpraszać zrozumiała zawartość strony, kiedy ten chce zobaczyć sam jej wygląd. Jedną z mocnych stron używania Lorem Ipsum jest to, że ma wiele różnych „kombinacji” zdań, słów i akapitów, w przeciwieństwie do zwykłego: „tekst, tekst, tekst”, sprawiającego, że wygląda to „zbyt czytelnie” po polsku. Wielu webmasterów i designerów używa Lorem Ipsum jako domyślnego modelu tekstu i wpisanie w internetowej wyszukiwarce ‘lorem ipsum’ spowoduje znalezienie bardzo wielu stron, które wciąż są w budowie. Wiele wersji tekstu ewoluowało i zmieniało się przez lata, czasem przez przypadek, czasem specjalnie (humorystyczne wstawki itd).</p>');
        $estate->setImages(array('architecture-1836070_1920.jpg'));
        $estate->setLocation(array('type'=> "Point", 'coordinates'=> array( -73.97, 40.77 )));


        $dm->persist($estate);
        $dm->flush();
        return new JsonResponse(array('Status' => 'OK'));
    }
	
	/**
     * @Route("/getDocuments", methods={"GET"})
     */
    public function getMongo(DocumentManager $dm)
    {
        // $estate = new Estate();
        // $estate->setName("Testowa");
        // $estate->setLocation("Vincent");


        // $dm->persist($estate);
        // $dm->flush();
		$repository = $dm->getRepository(Estate::class);
		$results =  $repository->findAll();
		$results = $repository->findBy(['name' => 'Testowa']);
		
		$builder = $dm->createAggregationBuilder(Estate::class);
		$builder
		->hydrate(Estate::class)
		->unwind('$tags')
		->match()
			->field('tags')
			->equals("ccc")
		// ->group()
			// ->field('id')
			// ->expression('$name')
		// ->count('id')
		// ->addFields()
			// ->field('purchaseYear')
			// ->expression('$name');
			;
			
		// $results = $builder->execute()->toArray();
		$results = $builder->getAggregation();
		
		foreach($results as $user){
        echo ($user->getId())."<br/>";
    }

		// $results->getIterator();

// while ($animal = $results->current()) {

    // echo $animal->getName();

    // $results->next();
// }
		
		
		dd($results);
    // ->field('name')->equals('foo')
    // ->sort('price', 'ASC')
    // ->limit(10)
    // ->getQuery()
    // ->execute();
        return new JsonResponse($results);
    }
	
	
}