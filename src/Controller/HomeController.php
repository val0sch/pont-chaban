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

        // $data récupère le tableau de données trié par ordre chronologique
        $datas = $serviceApi->getAllDatas();

        // $nextclosing récupère le premier élement du tableau qui sera la prochaine date de fermeture
        $nextclosing = $datas[0]['date_timestamp'];

        //On recupère la date fictive
        $fictiveDate = $dateService->createFictiveDate();
        $today =  date("d M Y,  h:i a", $fictiveDate);
        $nextclosing =  date("d M Y,  h:i a", $nextclosing);

        // La variable $interval récupère le nombre de jour s'écoulant
        //  entre la date fictive $today et la prochaine fermeture $nextclosing
        $dateAndTime = new DateTime($today);
        $nextclosing = new DateTime($nextclosing);
        $interval = $dateAndTime->diff($nextclosing);
        $interval = $interval->format('%d jours %h heures %i minutes %s secondes');

        return $this->render('home/index.html.twig', ['today' => $today, 'datas' => $datas, 'interval' => $interval]);
    }
}
