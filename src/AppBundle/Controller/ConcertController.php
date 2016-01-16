<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConcertController extends Controller
{
    /**
     * @Route("/concert/")
     */
    public function indexAction()
    {
        $concertList = [
            [
                'date' => '2015年5月3日',
                'place' => '東京文化会館（満席）',
                'time' => '14:00',
            ],
            [
                'date' => '2015年7月12日',
                'place' => '鎌倉芸術館',
                'time' => '14:00',
            ],
            [
                'date' => '2015年9月20日',
                'place' => '横浜みなとみらいホール',
                'time' => '15:00',
            ],
        ];

        return $this->render('Concert/index.html.twig', [
            'concertList' => $concertList
        ]);
    }
}
