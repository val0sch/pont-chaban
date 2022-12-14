<?php

namespace App\Controller;

use App\Service\CallApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CallApi $callApi): Response
    {

        dd($callApi->getAllDatas());
        $today = mktime(11, 14, 54, 7, 22, 2022);
        $today =  date("d M Y,  h:i a", $today);
        return $this->render('home/index.html.twig', ['today' => $today]);
    }
}
