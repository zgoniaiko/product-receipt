<?php

namespace App\Tests\Entity;

use App\Entity\Receipt;
use PHPUnit\Framework\TestCase;

class ReceiptTest extends TestCase
{
    public function testAccessors(): void
    {
        $receipt = new Receipt();

        $status = 'finished';
        self::assertSame('open', $receipt->getStatus(), "receipt status should be open");
        $receipt->setStatus($status);
        self::assertSame($status, $receipt->getStatus(), "receipt status should be finished");
    }
}
