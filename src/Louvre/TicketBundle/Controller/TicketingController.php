<?php

namespace Louvre\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\GlobalTicketType;
use Louvre\TicketBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Request;
use Louvre\TicketBundle\Entity\GlobalTicket;

class TicketingController extends Controller
{
    public function indexAction(Request $request )
    {
    	$globalticket = new GlobalTicket();
    	$form = $this->get('form.factory')->create(GlobalTicketType::class, $globalticket);

    	if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

		      $em = $this->getDoctrine()->getManager();
              
		      $em->persist($globalticket);
		      $em->flush();

		      return $this->redirectToRoute('louvre_ticket_view', array('id' => $globalticket->getId()));

		 }
		return $this->render('LouvreTicketBundle:Ticketing:index.html.twig'
			, array(
			'form' => $form->createView(),
			
			));
    }

    public function viewAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    // Pour récupérer une seule annonce, on utilise la méthode find($id)
    $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);
   
    if (null === $globalticket) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    // Récupération de la liste des tickets de l'annonce
    $listTickets = $em
    ->getRepository('LouvreTicketBundle:Ticket')
    ->findBy(array('globalticket' => $globalticket ))
    ;
    
    
    return $this->render('LouvreTicketBundle:Ticketing:view.html.twig', array(
      'globalticket'           => $globalticket,
      'listTickets'      => $listTickets,
      
    ));
  }


  public function exempleAction(Request $request )
    {
        
        return $this->render('LouvreTicketBundle:Ticketing:exemple.html.twig'
            , array(
            
            
            ));
    }


}
 
  
