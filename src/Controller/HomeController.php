<?php

namespace App\Controller;

use App\Service\CallApi;
use App\Service\FictiveDate;
use App\Service\ServiceApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServiceApi $serviceApi,): Response
    {
        $arrayNewDatas = $serviceApi->getAllDatas();

        $today = new FictiveDate();
        $today = $today->createFictiveDate();
        $today =  date("d M Y,  h:i a", $today);
        
        return $this->render('home/index.html.twig', ['today' => $today, 'datas' => $arrayNewDatas]);
    }
}
