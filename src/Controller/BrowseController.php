<?php

namespace App\Controller;

use App\Repository\EstateRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrowseController extends AbstractController
{
    /**
     * @Route("/browse", name="browse")
     * @Route("/browse-p{page}", name="browse_page", requirements={"page"="\d+"})
     */
    public function index(EstateRepository $estateRepository,int $page = 0): Response
    {
        $itemsPerSite = 6;
        $currentPage = ($page==0?1:$page);
        $allEstates = $estateRepository->findBy([],['_id'=>'asc'],$itemsPerSite,($currentPage-1)*$itemsPerSite);

        $countEstates = $estateRepository->countAllEstates();

        return $this->render('Portal/browse.html.twig', [
            'allEstates' => $allEstates,
            'currentPage' => $currentPage,
            'lastPage' => ceil($countEstates/$itemsPerSite)
        ]);
    }
}
