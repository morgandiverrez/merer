<?php

namespace App\Entity;

use App\Repository\FundTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundTypeRepository::class)]
class FundType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fundName = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToMany(targetEntity: FundBox::class, inversedBy: 'fundTypes')]
    private Collection $fundBox;

    public function __construct()
    {
        $this->fundBox = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFundName(): ?string
    {
        return $this->fundName;
    }

    public function setFundName(string $fundName): self
    {
        $this->fundName = $fundName;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return Collection<int, FundBox>
     */
    public function getFundBox(): Collection
    {
        return $this->fundBox;
    }

    public function addFundBox(FundBox $fundBox): self
    {
        if (!$this->fundBox->contains($fundBox)) {
            $this->fundBox->add($fundBox);
        }

        return $this;
    }

    public function removeFundBox(FundBox $fundBox): self
    {
        $this->fundBox->removeElement($fundBox);

        return $this;
    }
}
