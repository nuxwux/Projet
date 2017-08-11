<?php
namespace Louvre\TicketBundle\Services\Email;
use Doctrine\ORM\EntityManagerInterface;


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

  public function sendEmail($email, $filename )
  {
    $dir = '/tmp/';

     $message = \Swift_Message::newInstance()
        ->setSubject("Confirmation d'achat de vos tickets")
        ->setFrom('louvre@louvre.fr')
        ->setTo($email)
        ->setBody('Voici en pièce jointe vos tickets')
        ->attach(\Swift_Attachment::fromPath($dir.$filename))
    ;

    $this->mailer->send($message);

}
}
