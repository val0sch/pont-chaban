<?php

namespace App\Controller;

use App\Service\FictiveDate;
use App\Service\Interval;
use App\Service\ServiceApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServiceApi $serviceApi, FictiveDate $fictiveDate): Response
    {
        $arrayNewDatas = $serviceApi->getAllDatas();

        $nextclosing = $arrayNewDatas[0]['fields']['date_passage'];
        $fictiveDate = $fictiveDate->createFictiveDate();
        $dateAndTime =  date("d M Y,  h:i a", $fictiveDate);
        $today = date("Y-m-d", $fictiveDate);

        $today = new DateTime($today);
        $nextclosing = new DateTime($nextclosing);
        $interval = $today->diff($nextclosing);
        $interval = $interval->format('%d jours');
        dump($interval);


        return $this->render('home/index.html.twig', ['today' => $dateAndTime, 'datas' => $arrayNewDatas, 'interval' => $interval]);
    }
}
