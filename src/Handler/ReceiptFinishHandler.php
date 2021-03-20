<?php

namespace App\Handler;

use App\Entity\Receipt;

class ReceiptFinishHandler
{
    public function handle(Receipt $receipt): Receipt
    {
        $receipt->finish();

        return $receipt;
    }
}