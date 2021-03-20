<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\ReceiptFinish;
use App\Repository\ReceiptRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(itemOperations={
 *     "get",
 *     "finish"={
 *         "method"="PUT",
 *         "path"="receipts/{id}/finish",
 *         "controller"=ReceiptFinish::class,
 *     }},
 *     normalizationContext={"groups"={"read"}},
 * )
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
     * @Groups("read")
     * @Assert\Regex("/^open|finished/", message="This value should be either ""open"" or ""fihished"".")
     * @ORM\Column(type="string", length=255)
     */
    private $status = 'open';

    /**
     * @Groups("read")
     * @ORM\OneToMany(targetEntity=ReceiptProduct::class, mappedBy="receipt", orphanRemoval=true)
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

    public function finish(): self
    {
        $this->status = 'finished';

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
        }

        return $this;
    }

    public function removeReceiptProduct(ReceiptProduct $receiptProduct): self
    {
        $this->receiptProducts->removeElement($receiptProduct);

        return $this;
    }
}
