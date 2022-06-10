<?php

namespace App\Entity;

use App\Repository\IdentificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IdentificationRepository::class)]
class Identification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $identifiant;

    #[ORM\Column(type: 'string', length: 255)]
    private $password;

    #[ORM\OneToOne(targetEntity: profil::class, cascade: ['persist', 'remove'])]
    private $profil;

    #[ORM\OneToMany(mappedBy: 'identification', targetEntity: RoleIdentification::class)]
    private $roleIdentification;

    public function __construct()
    {
        $this->roleIdentification = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfil(): ?profil
    {
        return $this->profil;
    }

    public function setProfil(?profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, RoleIdentification>
     */
    public function getRoleIdentification(): Collection
    {
        return $this->roleIdentification;
    }

    public function addRoleIdentification(RoleIdentification $roleIdentification): self
    {
        if (!$this->roleIdentification->contains($roleIdentification)) {
            $this->roleIdentification[] = $roleIdentification;
            $roleIdentification->setIdentification($this);
        }

        return $this;
    }

    public function removeRoleIdentification(RoleIdentification $roleIdentification): self
    {
        if ($this->roleIdentification->removeElement($roleIdentification)) {
            // set the owning side to null (unless already changed)
            if ($roleIdentification->getIdentification() === $this) {
                $roleIdentification->setIdentification(null);
            }
        }

        return $this;
    }
}
