<?php

namespace AppBundle\Controller\Api;

use AppBundle\Repository\ConcertRepository;
use FOS\RestBundle\Controller\FOSRestController;

class ConcertController extends FOSRestController
{
    public function getConcertsAction()
    {
        $em = $this->get('doctrine')->getManager();
        /** @var ConcertRepository $repository */
        $repository = $em->getRepository('AppBundle:Concert');
        $concertList = $repository->findAll();

        return $concertList;
    }
}
