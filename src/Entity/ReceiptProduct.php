<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReceiptProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
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
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $cost;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=3)
     */
    private $amount = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $vatClass;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $subTotal = 0;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $vat = 0;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=2)
     */
    private $total = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceipt(): ?Receipt
    {
        return $this->receipt;
    }

    public function setReceipt(?Receipt $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        $this->name = $product->getName();
        $this->cost = $product->getCost();
        $this->vatClass = $product->getVatClass();

        return $this;
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

    public function setSubTotal(string $subTotal): self
    {
        $this->subTotal = $subTotal;

        return $this;
    }

    public function getVat(): ?string
    {
        return $this->vat;
    }

    public function setVat(string $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): self
    {
        $this->total = $total;

        return $this;
    }
}
