<?php

namespace Louvre\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Louvre\TicketBundle\Form\GlobalTicketType;
use Louvre\TicketBundle\Form\TicketType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     
     $globalticket->setTotalprice($totalPrice);
     $id = $globalticket->getId();
     $md5 = md5($id);
     $globalticket->setMd5($md5);

     $em->flush();

     $public_key = $this->getParameter('stripe_public_key');

    
    return $this->render('LouvreTicketBundle:Ticketing:view.html.twig', array(
      'globalticket'           => $globalticket,
      'listTickets'            => $listTickets,
      'totalPrice'             => $totalPrice,
      'stripe_public_key'      => $public_key,
    ));
  }


    public function chargeAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);

        // \Stripe\Stripe::setApiKey("sk_test_T5IcFYxjMSW9bWGIQvQ4DD9M");
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        
         $token  = $request->get('stripeToken');
         $stripeinfo = \Stripe\Token::retrieve($token);
         $email = $stripeinfo->email;
         $totalPrice = $globalticket->getTotalprice() * 100 ;
     
      $customer = \Stripe\Customer::create([
          'email' => $email,
          'source'  => $token
      ]);
      //  Verifier le champ paid ici 
      if ( $globalticket->getPaid() == 0) {
         $charge = \Stripe\Charge::create([
              'customer' => $customer->id,  
              'amount'   => $totalPrice,
              'currency' => 'eur'
          ]);
          $globalticket->setPaid(1);
          $em->flush();
      }
       return $this->redirectToRoute('louvre_ticket_confirm', array('id' => $id)); 
    } 

    public function confirmAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);

        // if

        return $this->render('LouvreTicketBundle:Ticketing:confirm.html.twig', array(  
          'globalticket'           => $globalticket,  
            ));



    }

    

   
    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);
        $listTickets = $em
          ->getRepository('LouvreTicketBundle:Ticket')
          ->findBy(array('globalticket' => $globalticket ))
          ;

        $html = $this->renderView('LouvreTicketBundle:Ticketing:pdf.html.twig', array(
          'globalticket'           => $globalticket, 
          'listTickets'            => $listTickets, 
          ));

        $filename = sprintf('TicketLouvre-%s.pdf', date('Y-m-d'));



        return  new Response( // a mettre surement dans swiftmailer, enregister dans var cache
            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]


        );


    }


}
 
  
