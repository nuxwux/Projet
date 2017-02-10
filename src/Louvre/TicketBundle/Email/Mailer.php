<?php
namespace Louvre\TicketBundle\Email;
use Doctrine\ORM\EntityManagerInterface;


class Mailer
{
  /**
   * @var \Swift_Mailer
   */
  private $mailer;

  

  public function __construct(\Swift_Mailer $mailer, EntityManagerInterface $em, $template , $pdf)
  {
    $this->mailer = $mailer;
    $this->em   = $em;
    $this->template = $template;
    $this->pdf = $pdf;
  }

  public function sendEmail($email, $id )
  {
     
        $globalticket = $this->em->getRepository('LouvreTicketBundle:GlobalTicket')->find($id);
        $listTickets = $this->em
          ->getRepository('LouvreTicketBundle:Ticket')
          ->findBy(array('globalticket' => $globalticket ))
          ;

        $html = $this->template->render('LouvreTicketBundle:Ticketing:pdf.html.twig', array(
          'globalticket'           => $globalticket, 
          'listTickets'            => $listTickets, 
          ));

        $filename = sprintf('TicketLouvre-%s.pdf', date('U'));

        
        $this->pdf->generateFromHtml($html, "var/cache/$filename");


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

        ->attach(\Swift_Attachment::fromPath("var/cache/$filename"))
    ;
    $this->mailer->send($message);
}
}