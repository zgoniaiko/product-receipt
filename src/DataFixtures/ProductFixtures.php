<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public CONST PRODUCT_TEA = 'tea';
    public CONST PRODUCT_COFFEE = 'coffee';
    public CONST PRODUCT_CUP = 'cup';

    public function load(ObjectManager $manager)
    {
        $tea = (new Product())
            ->setBarcode('0780658801111')
            ->setName('Dilmah Premium 100% Pure Ceylon Tea, 100-Count Tea Bags (Pack of 3)')
            ->setCost(28.99)
            ->setVatClass(6);
        $manager->persist($tea);

        $coffee = (new Product())
            ->setBarcode('0672201000037')
            ->setName('Jacobs Cronat Gold Instant Coffee 200g')
            ->setCost(12.02)
            ->setVatClass(6);
        $manager->persist($coffee);

        $cup = (new Product())
            ->setBarcode('0026102689783')
            ->setName('Luminarc White 29cl Trianon Tea Coffee Mug Serveware Kitchen')
            ->setCost(6.01)
            ->setVatClass(21);
        $manager->persist($cup);

        $manager->flush();

        $this->addReference(self::PRODUCT_TEA, $tea);
        $this->addReference(self::PRODUCT_COFFEE, $coffee);
        $this->addReference(self::PRODUCT_CUP, $cup);
    }
}
