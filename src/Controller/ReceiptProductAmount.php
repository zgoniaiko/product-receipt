<?php

namespace App\Controller;

use App\Entity\Receipt;
use App\Entity\ReceiptProduct;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class ReceiptProductAmount
{
    public function __invoke(Request $request, DecoderInterface $decoder, ManagerRegistry $registry, Receipt $data)
    {
        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse([], 400);
        }

        $format = $request->getRequestFormat();
        $amount = $decoder->decode($content, $format)['amount'];

        if (!$amount) {
            return new JsonResponse([], 400);
        }

        $receiptProducts = $registry->getRepository(ReceiptProduct::class)->findBy(['receipt' => $data->getId()], ['id' => 'desc'], 1);
        if (empty($receiptProducts)) {
            return new JsonResponse([], 400);
        }
        $receiptProduct = $receiptProducts[0];

        $receiptProduct->setAmount($amount);

        $em = $registry->getManagerForClass(ReceiptProduct::class);
        $em->persist($receiptProduct);
        $em->flush();

        return $data;
    }
}
