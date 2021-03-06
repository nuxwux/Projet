<?php

namespace Louvre\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Louvre\TicketBundle\Form\Type\GlobalTicketType;

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
    public function test2Action()
    {


        return $this->render('LouvreTicketBundle:Ticketing:test2.html.twig');
    }

    public function viewAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $globalticket = $em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);
   
    if (null === $globalticket) {
      throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
    }
    $listTickets = $em
    ->getRepository('LouvreTicketBundle:Ticket')
    ->findBy(array('globalticket' => $globalticket ))
    ;
    $pricer = $this->container->get('louvre_ticket.pricer');
    $totalPrice = 0;
    foreach( $listTickets as $ticket) {
        $prix = $pricer->ticketPricer($ticket->getBirthdate(), $globalticket->getTicketype(), $ticket->getReduction());
        $ticket->setPrice($prix);
        $typeTarif = $pricer->ticketTyper($ticket->getPrice(),$globalticket->getTicketype());
        $ticket->setType($typeTarif);
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
        $mail = $globalticket->getMail();
        $mailer = $this->container->get('louvre_ticket.email.mailer');
        $pdf = $this->container->get('louvre_ticket.pdf.pdfbuilder');

    
        \Stripe\Stripe::setApiKey($this->getParameter('stripe_secret_key'));
        
         $token  = $request->get('stripeToken');
         $stripeinfo = \Stripe\Token::retrieve($token);
         $email = $stripeinfo->email;
         $totalPrice = $globalticket->getTotalprice() * 100 ;
     
      $customer = \Stripe\Customer::create([
          'email' => $email,
          'source'  => $token
      ]);

     
      if ( $globalticket->getPaid() == 0) {
         $charge = \Stripe\Charge::create([
              'customer' => $customer->id,  
              'amount'   => $totalPrice,
              'currency' => 'eur'
          ]);
          $globalticket->setPaid(1);
          $em->flush();
          

      }
      

      $filename = $pdf->buildPdf($id);

      $mailer->sendEmail($mail, $filename);
      
      
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

    

   
    

}
 
  
