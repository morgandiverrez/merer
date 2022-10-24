<?php

namespace App\Entity;

use App\Repository\InstitutionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
class Institution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $headquarter = null;

    #[ORM\Column]
    private ?bool $open = null;

    #[ORM\ManyToOne(inversedBy: 'institutions')]
    private ?AdministrativeIdentifier $administrativeIdentifier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isHeadquarter(): ?bool
    {
        return $this->headquarter;
    }

    public function setHeadquarter(bool $headquarter): self
    {
        $this->headquarter = $headquarter;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->open;
    }

    public function setOpen(bool $open): self
    {
        $this->open = $open;

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
}
