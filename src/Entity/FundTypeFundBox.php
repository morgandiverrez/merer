<?php

namespace App\Entity;

use App\Repository\FundTypeFundBoxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundTypeFundBoxRepository::class)]
class FundTypeFundBox
{
    
    #[ORM\ManyToOne(inversedBy: 'fundTypeFundBoxes')]
    #[ORM\Id]
    #[ORM\JoinColumn(nullable: false)]
    private ?FundType $fundType = null;

    #[ORM\ManyToOne(inversedBy: 'fundTypeFundBoxes')]
    #[ORM\Id]
    #[ORM\JoinColumn(nullable: false)]
    private ?FundBox $fundBox = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFundType(): ?FundType
    {
        return $this->fundType;
    }

    public function setFundType(?FundType $fundType): self
    {
        $this->fundType = $fundType;

        return $this;
    }

    public function getFundBox(): ?FundBox
    {
        return $this->fundBox;
    }

    public function setFundBox(?FundBox $fundBox): self
    {
        $this->fundBox = $fundBox;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
