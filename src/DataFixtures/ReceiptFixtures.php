<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Receipt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReceiptFixtures extends Fixture
{
    public CONST RECEIPT_OPEN = 'receipt-open';
    public CONST RECEIPT_FINISHED = 'receipt-finished';

    public function load(ObjectManager $manager)
    {
        $receiptFinished = (new Receipt())
            ->setStatus('finished');
        $manager->persist($receiptFinished);

        $receiptOpen = (new Receipt())
            ->setStatus('open');
        $manager->persist($receiptOpen);

        $manager->flush();

        $this->addReference(self::RECEIPT_OPEN, $receiptOpen);
        $this->addReference(self::RECEIPT_FINISHED, $receiptFinished);
    }
}
