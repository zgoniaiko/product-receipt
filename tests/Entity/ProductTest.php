<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testAccessors(): void
    {
        $barcode = '0123456789123';
        $product = new Product($barcode);

        self::assertSame($barcode, $product->getBarcode(), "should return product barcode");

        $name = 'Product name';
        self::assertSame('', $product->getName(), "product name should be empty string");
        $product->setName($name);
        self::assertSame($name, $product->getName(), "should return product name");

        $cost = 1.01;
        self::assertEquals(0, $product->getCost(), "product cost should be 0");
        $product->setCost($cost);
        self::assertEquals($cost, $product->getCost(), "should return product cost");

        $vatClass = 6;
        self::assertSame(0, $product->getVatClass(), "product vat class should be empty");
        $product->setVatClass($vatClass);
        self::assertEquals($vatClass, $product->getVatClass());
    }
}
