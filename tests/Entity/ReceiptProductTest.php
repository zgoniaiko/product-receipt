<?php

namespace App\Tests\Entity;

use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\ReceiptProduct;
use PHPUnit\Framework\TestCase;

class ReceiptProductTest extends TestCase
{
    public function testAccessors(): void
    {
        $receipt = $this->createMock(Receipt::class);
        $receipt->method('getId')->willReturn(1);

        $tea = $this->createMock(Product::class);
        $tea->method('getName')->willReturn('tea');
        $tea->method('getCost')->willReturn('1.01');
        $tea->method('getVatClass')->willReturn(6);

        $receiptProduct = new ReceiptProduct($receipt, $tea);

        $amount = 2;
        self::assertEquals(0, $receiptProduct->getAmount(), "amount of product should be 0");
        $receiptProduct->setAmount($amount);
        self::assertEquals($amount, $receiptProduct->getAmount(), "should return amount of product");

        $subtotal = 10;
        self::assertEquals(2.02, $receiptProduct->getSubTotal(), "sub-total of product should be 0");
        $receiptProduct->setSubTotal($subtotal);
        self::assertEquals($subtotal, $receiptProduct->getSubTotal(), "should return sub-total of product");

        $vat = 2;
        self::assertEquals(0.12, $receiptProduct->getVat(), "vat of product should be 0");
        $receiptProduct->setVat($vat);
        self::assertEquals($vat, $receiptProduct->getVat(), "should return vat of product");

        $total = 12;
        self::assertEquals(2.14, $receiptProduct->getTotal(), "total of product should be 0");
        $receiptProduct->setTotal($total);
        self::assertEquals($total, $receiptProduct->getTotal(), "should return total of product");
    }

    public function testProductPropertiesCopied(): void
    {
        $receipt = $this->createMock(Receipt::class);
        $receipt->method('getId')->willReturn(1);

        $tea = $this->createMock(Product::class);
        $tea->method('getName')->willReturn('tea');
        $tea->method('getCost')->willReturn('1.01');
        $tea->method('getVatClass')->willReturn(6);

        $receiptProduct = new ReceiptProduct($receipt, $tea);
        self::assertSame($tea, $receiptProduct->getProduct(), "should return link to product");
        self::assertSame($tea->getName(), $receiptProduct->getName(), "should return product name");
        self::assertSame($tea->getCost(), $receiptProduct->getCost(), "should return product cost");
        self::assertSame($tea->getVatClass(), $receiptProduct->getVatClass(), "should return product vat class");
    }

    public function testProductPropertiesCalculated(): void
    {
        $receipt = $this->createMock(Receipt::class);
        $receipt->method('getId')->willReturn(1);

        $tea = $this->createMock(Product::class);
        $tea->method('getName')->willReturn('tea');
        $tea->method('getCost')->willReturn('1.01');
        $tea->method('getVatClass')->willReturn(6);

        $receiptProduct = new ReceiptProduct($receipt, $tea);
        $receiptProduct->setAmount(1);
        self::assertEquals(1.01, $receiptProduct->getSubTotal());
        self::assertEquals(0.06, $receiptProduct->getVat());
        self::assertEquals(1.07, $receiptProduct->getTotal());
    }
}
