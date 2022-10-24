<?php

namespace App\Entity;

use App\Repository\FundBoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundBoxRepository::class)]
class FundBox
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1024, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $lastCountDate = null;

    #[ORM\ManyToOne]
    private ?ChartOfAccounts $chartOfAccounts = null;

    #[ORM\ManyToOne]
    private ?Location $location = null;

    #[ORM\ManyToMany(targetEntity: FundType::class, mappedBy: 'fundBox')]
    private Collection $fundTypes;

    public function __construct()
    {
        $this->fundTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLastCountDate(): ?\DateTimeInterface
    {
        return $this->lastCountDate;
    }

    public function setLastCountDate(\DateTimeInterface $lastCountDate): self
    {
        $this->lastCountDate = $lastCountDate;

        return $this;
    }

    public function getChartOfAccounts(): ?ChartOfAccounts
    {
        return $this->chartOfAccounts;
    }

    public function setChartOfAccounts(?ChartOfAccounts $chartOfAccounts): self
    {
        $this->chartOfAccounts = $chartOfAccounts;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, FundType>
     */
    public function getFundTypes(): Collection
    {
        return $this->fundTypes;
    }

    public function addFundType(FundType $fundType): self
    {
        if (!$this->fundTypes->contains($fundType)) {
            $this->fundTypes->add($fundType);
            $fundType->addFundBox($this);
        }

        return $this;
    }

    public function removeFundType(FundType $fundType): self
    {
        if ($this->fundTypes->removeElement($fundType)) {
            $fundType->removeFundBox($this);
        }

        return $this;
    }
}
