<?php

namespace App\Controller;

use App\Service\DateService;
use App\Service\ServiceApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ServiceApi $serviceApi, DateService $dateService): Response
    {


        // Je récupère le tableau de données trié par ordre croissant (date)
        $datas = $serviceApi->getAllDatas();

        // Je récupère le premier élement du tableau qui sera la prochaine date de fermeture
        $nextclosing = $datas[0]['date_passage'];
        //Je recupère la date fictive
        $fictiveDate = $dateService->createFictiveDate();
        $dateAndTime =  date("d M Y,  h:i a", $fictiveDate);
        $today = date("Y-m-d", $fictiveDate);

        //Je récupère dans la variable $interval le nombre de jour s'écoulant
        //  entre la date fictive $today et la prochaine fermeture $nextclosing
        $today = new DateTime($today);
        $nextclosing = new DateTime($nextclosing);
        $interval = $today->diff($nextclosing);
        $interval = $interval->format('%d jours');

        return $this->render('home/index.html.twig', ['today' => $dateAndTime, 'datas' => $datas, 'interval' => $interval]);
    }
}
