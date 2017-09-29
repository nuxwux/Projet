<?php
namespace Tests\TicketBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TicketingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/billetterie');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie');


        $this->assertTrue($client->getResponse()->isSuccessful());


        $this->assertEquals(1, $crawler->filter('h1:contains("Selection des Billets ")')->count());

        $form = $crawler->selectButton('Etape suivante')->form();

        $values = $form->getPhpValues();


        $values['louvre_ticketbundle_globalticket[ticketype]'] = 'Journée';
        $values['louvre_ticketbundle_globalticket[mail]'] = 'ludovic.siennat@gmail.com';
//        $values['louvre_ticketbundle_globalticket[datevisit]'] = new \DateTime('1991-02-06');

        $values['louvre_ticketbundle_globalticket']['tickets'][0]['name'] = "siennat";
        $values['louvre_ticketbundle_globalticket']['tickets'][0]['firstname'] = "ludodic";
        $values['louvre_ticketbundle_globalticket']['tickets'][0]['country'] = 'france';
//        $values['louvre_ticketbundle_globalticket']['tickets'][0]['birthdate'] = array(
//            'year' => 1991,
//            'month' => 03,
//            'day' => 06);


        $crawler = $client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());
        $crawler = $client->submit($form);
//
        var_dump($client->getResponse());



         $client->followRedirect();
//        echo ->getContent();
    }
}
