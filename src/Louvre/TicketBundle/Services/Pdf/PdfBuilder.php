<?php
namespace Louvre\TicketBundle\Services\Pdf;
use Doctrine\ORM\EntityManagerInterface;

class PdfBuilder
{
   public function __construct(EntityManagerInterface $em, $template , $pdf)
  {
    $this->em   = $em;
    $this->template = $template;
    $this->pdf = $pdf;
    
  }

  public function buildPdf($id)
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

        $filename = sprintf('TicketLouvre-%s.pdf', md5($id.microtime()));
        $dir = '/tmp/';
        
        
        $this->pdf->generateFromHtml($html, $dir.$filename);

        return $filename;
    
  }
}
