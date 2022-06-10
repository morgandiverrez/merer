<?php

namespace App\Entity;

use App\Repository\RoleIdentificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleIdentificationRepository::class)]
class RoleIdentification
{
    
    #[ORM\Column(type: 'date', nullable: true)]
    private $date_peremption;

    #[ORM\ManyToOne(targetEntity: Identification::class, inversedBy: 'roleIdentification')]
    #[ORM\Id]
    #[ORM\JoinColumn(name:"identification_ID", referencedColumnName:"id")]
    private $identification;

    #[ORM\ManyToOne(targetEntity: Role::class, inversedBy: 'roleIdentification')]
    #[ORM\Id]
    #[ORM\JoinColumn(name: "role_ID", referencedColumnName: "id")]
    private $role;



    public function getDatePeremption(): ?\DateTimeInterface
    {
        return $this->date_peremption;
    }

    public function setDatePeremption(?\DateTimeInterface $date_peremption): self
    {
        $this->date_peremption = $date_peremption;

        return $this;
    }

    public function getIdentification(): ?Identification
    {
        return $this->identification;
    }

    public function setIdentification(?Identification $identification): self
    {
        $this->identification = $identification;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }
}
