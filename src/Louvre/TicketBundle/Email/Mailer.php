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

  public function sendEmail($email)
  {
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
        ->attach(Swift_Attachment::fromPath('my-document.pdf'))
    ;
    $this->get('mailer')->send($message);
}