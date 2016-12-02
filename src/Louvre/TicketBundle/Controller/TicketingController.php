<?php

namespace Louvre\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketingController extends Controller
{
    public function indexAction()
    {
        return $this->render('LouvreTicketBundle:Ticketing:index.html.twig');
    }
}
