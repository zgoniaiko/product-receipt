<?php

namespace App\Controller;

use App\Entity\Receipt;
use App\Handler\ReceiptFinishHandler;

class ReceiptFinish
{
    private $receiptFinishHandler;

    public function __construct(ReceiptFinishHandler $receiptFinishHandler)
    {
        $this->receiptFinishHandler = $receiptFinishHandler;
    }

    public function __invoke(Receipt $data): Receipt
    {
        $this->receiptFinishHandler->handle($data);

        return $data;
    }
}