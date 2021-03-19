<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\ReceiptProduct;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ReceiptProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $receiptFinished = $this->getReference(ReceiptFixtures::RECEIPT_FINISHED);
        $receiptOpen = $this->getReference(ReceiptFixtures::RECEIPT_OPEN);

        $tea = $this->getReference(ProductFixtures::PRODUCT_TEA);
        $coffee = $this->getReference(ProductFixtures::PRODUCT_COFFEE);
        $cup = $this->getReference(ProductFixtures::PRODUCT_CUP);

        $receiptFinishedTea = (new ReceiptProduct())
            ->setReceipt($receiptFinished)
            ->setProduct($tea)
            ->setAmount(2);
        $manager->persist($receiptFinishedTea);

        $receiptOpenCoffee = (new ReceiptProduct())
            ->setReceipt($receiptOpen)
            ->setProduct($coffee)
            ->setAmount(1);
        $manager->persist($receiptOpenCoffee);

        $receiptOpenCup = (new ReceiptProduct())
            ->setReceipt($receiptOpen)
            ->setProduct($cup)
            ->setAmount(2);
        $manager->persist($receiptOpenCup);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ProductFixtures::class,
            ReceiptFixtures::class,
        ];
    }
}
