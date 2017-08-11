<?php
namespace Tests\TicketBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TicketingControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/billetterie');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Billetterie Officiella', $crawler->filter('h1')->text());
    }
}