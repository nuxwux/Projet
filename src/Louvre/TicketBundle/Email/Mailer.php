<?php
namespace Louvre\TicketBundle\Email;


class Mailer
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  public function __construct(\Swift_Mailer $mailer)
  {
    $this->mailer = $mailer;
  }

  public function sendEmail($email, $id )
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

        
        $this->get('knp_snappy.pdf')->generateFromHtml($html, "var/cache/$filename")


     $message = \Swift_Message::newInstance()
        ->setSubject("Confirmation d'achat de vos tickets")
        ->setFrom('louvre@louvre.fr')
        ->setTo($email)
        ->setBody('Voici en pièce jointe vos tickets')
          /*
            $this->renderView(
                // app/Resources/views/Emails/registration.html.twig
                'Emails/registration.html.twig',
                array('name' => $name)
            ),
            'text/html'
        )
        /*
         * If you also want to include a plaintext version of the message
        ->addPart(
            $this->renderView(
                'Emails/registration.txt.twig',
                array('name' => $name)
            ),
            'text/plain'
        )
        */

        ->attach(Swift_Attachment::fromPath("var/cache/$filename"))
    ;
    $this->get('mailer')->send($message);
}