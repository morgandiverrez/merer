<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?ChartOfAccounts $chartOfAccounts = null;

    #[ORM\ManyToOne(inversedBy: 'suppliers')]
    private ?Location $location = null;

    #[ORM\ManyToOne(inversedBy: 'suppliers')]
    private ?AdministrativeIdentifier $administrativeIdentifier = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    public function __construct()
    {
        $this->administrativeIdentifiers = new ArrayCollection();
    }

    public function  __toString()
    {
        return $this->getName();
    }
    
    public function getId(): ?int
    {
        return $this->id;
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



    public function getAdministrativeIdentifier(): ?AdministrativeIdentifier
    {
        return $this->administrativeIdentifier;
    }

    public function setAdministrativeIdentifier(?AdministrativeIdentifier $administrativeIdentifier): self
    {
        $this->administrativeIdentifier = $administrativeIdentifier;

        return $this;
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
}
