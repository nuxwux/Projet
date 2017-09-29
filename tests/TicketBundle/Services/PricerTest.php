<?php
/**
 * Created by PhpStorm.
 * User: Nux
 * Date: 30/08/2017
 * Time: 15:15
 */
namespace Tests\TicketBundle\Services;
use Louvre\TicketBundle\Services\Pricer\LouvrePricer;

use PHPUnit\Framework\TestCase;


class LouvrePricerTest extends TestCase
{
    public function testNormalWithoutReduction()
    {
        $pricer = new LouvrePricer();
        $date = new \DateTime('1991-10-10');

        $prix = $pricer->ticketPricer($date, "Journée", false);

       $this->assertEquals(16, $prix);
    }

    public function testEnfantWithoutReduction()
    {
        $pricer = new LouvrePricer();
        $date = new \DateTime('2006-10-10');

        $prix = $pricer->ticketPricer($date, "Journée", false);

        $this->assertEquals(8, $prix);
    }

    public function testEnfantWithReduction()
    {
        $pricer = new LouvrePricer();
        $date = new \DateTime('2006-10-10');

        $prix = $pricer->ticketPricer($date, "Journée", true);

        $this->assertEquals(10, $prix);
    }
    public function testSeniorWithoutReduction()
    {
        $pricer = new LouvrePricer();
        $date = new \DateTime('1940-10-10');

        $prix = $pricer->ticketPricer($date, "Journée", false);

        $this->assertEquals(12, $prix);
    }
    public function testEnfantWithoutReductionDemiJournee()
    {
        $pricer = new LouvrePricer();
        $date = new \DateTime('2006-10-10');

        $prix = $pricer->ticketPricer($date, "Demi-journée", false);

        $this->assertEquals(4, $prix);
    }
}
