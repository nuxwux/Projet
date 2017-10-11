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


    }
    public function testNotValidData()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/recaptilatif/{id}');

        $this->assertFalse($client->getResponse()->isSuccessful());
    }
}
