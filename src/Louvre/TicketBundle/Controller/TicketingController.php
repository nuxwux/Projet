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
    
    $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);
   
    if (null === $globalticket) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    // Récupération de la liste des tickets de l'annonce
    $listTickets = $em
    ->getRepository('LouvreTicketBundle:Ticket')
    ->findBy(array('globalticket' => $globalticket ))
    ;
    $pricer = $this->container->get('louvre_ticket.pricer');
    $totalPrice = 0;

    foreach( $listTickets as $ticket) {

        $prix = $pricer->ticketPricer($ticket->getBirthdate(), $globalticket->getTicketype(), $ticket->getReduction());
        $ticket->setPrice($prix);

        $totalPrice = $totalPrice + $ticket->getPrice();
    }

    
    return $this->render('LouvreTicketBundle:Ticketing:view.html.twig', array(
      'globalticket'           => $globalticket,
      'listTickets'      => $listTickets,
      'totalPrice'     => $totalPrice,
      
      
      
      
    ));
  }


    public function chargeAction(Request $request) {

        \Stripe\Stripe::setApiKey("sk_test_T5IcFYxjMSW9bWGIQvQ4DD9M");
        
         $token  = $request->get('stripeToken');
         $stripeinfo = \Stripe\Token::retrieve($token);
         $email = $stripeinfo->email;
     

      $customer = \Stripe\Customer::create([
          'email' => $email,
          'source'  => $token
      ]);

      $charge = \Stripe\Charge::create([
          'customer' => $customer->id,
          'amount'   => '1000',
          'currency' => 'eur'
      ]);



        return $this->render('LouvreTicketBundle:Ticketing:charge.html.twig', array(
            
            ));


    }


}
 
  
