<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="bigint")
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name = '';

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $cost = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $vatClass = 0;

    public function __construct(string $barcode)
    {
        $this->barcode = (int)$barcode;
    }

    public function getBarcode(): ?string
    {
        return sprintf("%013d", $this->barcode);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getVatClass(): ?int
    {
        return $this->vatClass;
    }

    public function setVatClass(int $vatClass): self
    {
        $this->vatClass = $vatClass;

        return $this;
    }
}
