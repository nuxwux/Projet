<?php

namespace Tests\TicketBundle\Services;


use Louvre\TicketBundle\Entity\GlobalTicket;
use Louvre\TicketBundle\Entity\Ticket;
use Louvre\TicketBundle\Form\Type\GlobalTicketType;
use Louvre\TicketBundle\Services\Pdf\PdfBuilder;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Twig_Environment;
use Knp\Bundle\SnappyBundle\KnpSnappyBundle;

use PHPUnit\Framework\TestCase;



class PdfBuilderTest extends TestCase
{
    public function testFilenameReturningDifferentId()
    {
        $GlobalTicketRepository = $this->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $globalticket = New GlobalTicket();
        $ticket1 = New Ticket();
        $ticket2 = New Ticket();
        $globalticket->addTicket($ticket1);
        $globalticket->addTicket($ticket2);

        $GlobalTicketRepository->expects($this->any())
            ->method('find')
            ->will($this->returnValue($globalticket));

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
//            ->setMethods(['getRepository'])
            ->getMock();

        $em->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($GlobalTicketRepository));

        $template = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdf = $this->getMockBuilder(KnpSnappyBundle::class)
            ->setMethods(['generateFromHtml'])
        ->getMock();

        $PdfBuilder = new PdfBuilder($em,$template,$pdf);

        $filename1 = $PdfBuilder->buildPdf('1');
        $filename2 = $PdfBuilder->buildPdf('2');

        $this->AssertNotEquals($filename1,$filename2);


    }

}