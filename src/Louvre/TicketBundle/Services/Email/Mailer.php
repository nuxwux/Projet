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
        ->setSubject("Musée du Louvre - Confirmation d'achat")
        ->setFrom('louvre@louvre.fr')
        ->setTo($email)
        ->setBody(
            '<html>'.
                    '<head>'.
                        '<style type="text/css">'.
                        'body{text-align:center;}'.


                        '</style>'.
                    '</head>'.
                    '<body>'.
                        '<h1> Musée du Louvre </h1>'.
                        '<p> Vous venez de commander grace à notre billetterie en ligne et nous vous en remercions </p>'.
                        '<p> Vous trouvere ci-joint votre ticket à imprimer pour accéder au Musee du Louvre</p>'.
                        '<p>A tres vite au Louvre !</p>'.
                    '</body>'.
            '</html>',
            'text/html'
            )
        ->attach(\Swift_Attachment::fromPath($dir.$filename))
    ;

    $this->mailer->send($message);

}
}
