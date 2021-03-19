<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReceiptRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ReceiptRepository::class)
 */
class Receipt
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=ReceiptProduct::class, mappedBy="receiptId", orphanRemoval=true)
     */
    private $receiptProducts;

    public function __construct()
    {
        $this->receiptProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|ReceiptProduct[]
     */
    public function getReceiptProducts(): Collection
    {
        return $this->receiptProducts;
    }

    public function addReceiptProduct(ReceiptProduct $receiptProduct): self
    {
        if (!$this->receiptProducts->contains($receiptProduct)) {
            $this->receiptProducts[] = $receiptProduct;
            $receiptProduct->setReceipt($this);
        }

        return $this;
    }

    public function removeReceiptProduct(ReceiptProduct $receiptProduct): self
    {
        if ($this->receiptProducts->removeElement($receiptProduct)) {
            // set the owning side to null (unless already changed)
            if ($receiptProduct->getReceipt() === $this) {
                $receiptProduct->setReceipt(null);
            }
        }

        return $this;
    }
}
