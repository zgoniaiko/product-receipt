<?php

namespace App\Entity;

use App\Repository\ReceiptProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReceiptProductRepository::class)
 */
class ReceiptProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Receipt::class, inversedBy="receiptProducts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $receipt;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(referencedColumnName="barcode", nullable=false)
     */
    private $product;

    /**
     * @Groups("read")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups("read")
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $cost;

    /**
     * @Groups("read")
     * @ORM\Column(type="decimal", precision=6, scale=3)
     */
    private $amount = 0;

    /**
     * @Groups("read")
     * @ORM\Column(type="integer")
     */
    private $vatClass;

    /**
     * @Groups("read")
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $subTotal = 0;

    /**
     * @Groups("read")
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $vat = 0;

    /**
     * @Groups("read")
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $total = 0;

    public function __construct(Receipt $receipt, Product $product)
    {
        $this->receipt = $receipt;
        $this->product = $product;

        $this->name = $product->getName();
        $this->cost = $product->getCost();
        $this->vatClass = $product->getVatClass();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        $this->calculate();

        return $this;
    }

    public function getVatClass(): ?int
    {
        return $this->vatClass;
    }

    public function getSubTotal(): ?string
    {
        return $this->subTotal;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    protected function calculate(): void
    {
        $this->subTotal = $this->amount * $this->getCost();
        $this->vat = round($this->subTotal * $this->getVatClass() / 100, 2);
        $this->total = $this->subTotal + $this->vat;
    }
}
