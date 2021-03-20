<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Product;
use App\Entity\Receipt;
use App\Entity\ReceiptProduct;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class ReceiptProductAdd
{
    public function __invoke(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, ManagerRegistry $registry, Receipt $data)
    {
        $content = $request->getContent();
        if (empty($content)) {
            return new JsonResponse([], 400);
        }

        $format = $request->getRequestFormat();
        $product = $serializer->deserialize($content, Product::class, $format);

        $product = $registry->getRepository(Product::class)->findOneBy(['barcode' => $product->getBarcode()]);

        if (!$product) {
            return new JsonResponse([], 404);
        }

        $errors = $validator->validate($product);
        if ($errors) {
            $output = [];
            foreach ($errors as $error) {
                $output[$error->getPropertyPath()][] = $error->getMessage();
            }

            return new JsonResponse($output, 400);
        }

        $receiptProduct = $registry->getRepository(ReceiptProduct::class)->findOneBy([
            'receipt' => $data->getId(),
            'product' => $product->getBarcode(),
        ]);

        if (!$receiptProduct) {
            $receiptProduct = new ReceiptProduct($data, $product);
            $receiptProduct->setAmount(1);
        }

        $em = $registry->getManagerForClass(ReceiptProduct::class);
        $em->persist($receiptProduct);
        $em->flush();

        return $data;
    }
}
